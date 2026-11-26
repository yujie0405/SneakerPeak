<?php

use SneakerPeak\App\Services\AuthService;
use SneakerPeak\App\Services\PasswordResetService;

require_once SNEAKERPEAK_ROOT . '/src/App/Services/AuthService.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Services/PasswordResetService.php';
require SNEAKERPEAK_ROOT . '/database/Database_Connection.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$email = trim((string) ($_POST['email'] ?? ''));
$name = trim((string) ($_POST['name'] ?? ''));
$password = (string) ($_POST['password'] ?? '');
$confirmation = (string) ($_POST['cpassword'] ?? '');
$errors = [];

$findAccount = static function (string $role, string $address) use ($conn): ?array {
    $table = $role === 'admin' ? 'admin' : 'customer';
    $statement = $conn->prepare("SELECT * FROM `$table` WHERE email = ? LIMIT 1");
    $statement->execute([$address]);
    return $statement->get_result()->fetch_assoc();
};

$redirect = static function (string $page): never {
    header('Location: ' . $page);
    exit;
};

if (isset($_POST['register'])) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $name === '') {
        $errors['email'] = 'Enter a name and valid email address.';
    } elseif ($password !== $confirmation || strlen($password) < 8 || !AuthService::canHash($password)) {
        $errors['password'] = 'Enter matching passwords between 8 and 72 bytes.';
    } elseif ($findAccount('customer', $email) || $findAccount('admin', $email)) {
        $errors['email'] = 'This email address is already registered.';
    } else {
        $insert = $conn->prepare(
            "INSERT INTO customer (name, Customer_Address, Customer_ContractNumber, email, password, code, status)
             VALUES (?, ?, ?, ?, ?, 0, 'notverified')"
        );
        $insert->execute([
            $name, (string) ($_POST['address'] ?? ''), (string) ($_POST['phone'] ?? ''), $email,
            password_hash($password, PASSWORD_DEFAULT),
        ]);
        if (PasswordResetService::issue($conn, ['id' => $conn->insert_id, 'email' => $email], 'verify')) {
            $_SESSION['email'] = $email;
            $_SESSION['info'] = 'Check your email for your verification code.';
            $redirect('otp_user.php');
        }
        $errors['otp-error'] = 'Your account was created. Sign in to request another verification email.';
    }
} elseif (isset($_POST['login'])) {
    $role = 'customer';
    $account = $findAccount($role, $email);
    if ($account === null) {
        $role = 'admin';
        $account = $findAccount($role, $email);
    }
    if ($account === null || !AuthService::verify($password, $account['password'])) {
        $errors['email'] = 'Incorrect email or password!';
    } elseif ($role === 'customer' && $account['status'] !== 'verified') {
        if (PasswordResetService::issue($conn, $account, 'verify')) {
            $_SESSION['email'] = $account['email'];
            $_SESSION['info'] = 'Check your email for your verification code.';
            $redirect('otp_user.php');
        }
        $errors['email'] = 'Could not deliver your verification code. Try again later.';
    } else {
        if (password_needs_rehash($account['password'], PASSWORD_DEFAULT)) {
            AuthService::replacePassword($conn, $role, (string) $account['id'], $account['password'], $password);
        }
        session_regenerate_id(true);
        $_SESSION = [
            $role === 'admin' ? 'AdminID' : 'CustomerID' => $account['id'],
            'email' => $account['email'],
            'name' => $account['name'],
        ];

        $remember = isset($_POST['remember']);
        $options = [
            'path' => '/', 'httponly' => true, 'samesite' => 'Lax',
            'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        ];
        setcookie('remember_email', $remember ? $account['email'] : '', $options + [
            'expires' => $remember ? time() + 86400 * 30 : time() - 3600,
        ]);
        setcookie('remember_password', '', $options + ['expires' => time() - 3600]);
        $redirect($role === 'admin' ? 'DashBoad.php' : 'index.php');
    }
} elseif (isset($_POST['check-email'])) {
    unset($_SESSION['verified_reset'], $_SESSION['otp']);
    $account = $findAccount('customer', $email);
    if ($account && $account['status'] === 'verified' && PasswordResetService::issue($conn, $account, 'reset')) {
        $_SESSION['email'] = $account['email'];
        $_SESSION['info'] = 'Check your email for your password reset code.';
        $redirect('otp_reset.php');
    }
    $errors['email'] = 'Could not send a reset code for this address.';
} elseif (isset($_POST['check']) || isset($_POST['check-reset-otp'])) {
    $purpose = isset($_POST['check-reset-otp']) ? 'reset' : 'verify';
    $verified = PasswordResetService::verify($conn, $purpose, (string) ($_POST['otp'] ?? ''));
    if ($verified === null) {
        $errors['otp-error'] = 'The code is incorrect or has expired.';
    } elseif ($purpose === 'reset') {
        $_SESSION['verified_reset'] = $verified;
        $_SESSION['info'] = 'Choose a new password.';
        $redirect('chg_pwd.php');
    } else {
        $update = $conn->prepare("UPDATE customer SET code = 0, status = 'verified' WHERE id = ? AND code = ?");
        $update->execute([$verified['id'], $verified['code']]);
        unset($_SESSION['info']);
        $redirect('Login.php');
    }
} elseif (isset($_POST['change-password'])) {
    $verified = $_SESSION['verified_reset'] ?? [];
    if (($verified['expires'] ?? 0) <= time() || $password !== $confirmation
        || strlen($password) < 8 || !AuthService::canHash($password)) {
        $errors['password'] = 'Use a valid reset code and matching passwords between 8 and 72 bytes.';
    } else {
        $update = $conn->prepare('UPDATE customer SET code = 0, password = ? WHERE id = ? AND code = ?');
        $update->execute([password_hash($password, PASSWORD_DEFAULT), $verified['id'], $verified['code']]);
        unset($_SESSION['verified_reset']);
        if ($update->affected_rows === 1) {
            $_SESSION['info'] = 'Your password changed. Sign in with your new password.';
            $redirect('user_changed_password.php');
        }
        $errors['password'] = 'The reset code is no longer valid.';
    }
} elseif (isset($_POST['login_for_Now'])) {
    $redirect('Login.php');
} elseif (isset($_POST['cancel'])) {
    $redirect('index.php');
}
