<?php

use SneakerPeak\App\Services\AuthService;
use SneakerPeak\App\Services\PasswordResetService;

require_once SNEAKERPEAK_ROOT . '/src/App/Services/AuthService.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Models/UserModel.php';
use SneakerPeak\App\Models\UserModel;
require_once SNEAKERPEAK_ROOT . '/src/App/Services/PasswordResetService.php';
require_once SNEAKERPEAK_ROOT . '/src/Core/Database.php';
$conn = \SneakerPeak\Core\Database::getInstance()->getConnection();

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$email = trim((string) ($_POST['email'] ?? ''));
$name = trim((string) ($_POST['name'] ?? ''));
$password = (string) ($_POST['password'] ?? '');
$confirmation = (string) ($_POST['cpassword'] ?? '');
$errors = [];

$findAccount = static fn(string $address): ?array => UserModel::find($conn, $address);

$redirect = static function (string $page): never {
    header('Location: ' . $page);
    exit;
};

if (isset($_POST['register'])) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $name === '') {
        $errors['email'] = 'Enter a name and valid email address.';
    } elseif ($password !== $confirmation || strlen($password) < 8 || !AuthService::canHash($password)) {
        $errors['password'] = 'Enter matching passwords between 8 and 72 bytes.';
    } elseif ($findAccount($email)) {
        $errors['email'] = 'This email address is already registered.';
    } else {
        $conn->begin_transaction();
        try {
            $conn->execute_query(
                'INSERT INTO T_User (DisplayName, Email, EmailKey, Phone, PasswordHash) VALUES (?, ?, ?, ?, ?)',
                [$name, $email, UserModel::emailKey($email), (string) ($_POST['phone'] ?? ''), password_hash($password, PASSWORD_DEFAULT)]
            );
            $userId = (string) $conn->insert_id;
            UserModel::saveAddress($conn, $userId, 1, (string) ($_POST['address'] ?? ''));
            $conn->commit();
        } catch (mysqli_sql_exception $error) {
            $conn->rollback();
            if ($error->getCode() !== 1062) throw $error;
            $errors['email'] = 'This email address is already registered.';
            return;
        }
        if (PasswordResetService::issue(['id' => $userId, 'email' => $email], 'verify')) {
            $_SESSION['email'] = $email;
            $_SESSION['info'] = 'Check your email for your verification code.';
            $redirect('otp_user.php');
        }
        $errors['otp-error'] = 'Your account was created. Sign in to request another verification email.';
    }
} elseif (isset($_POST['login'])) {
    $account = $findAccount($email);
    $role = !empty($account['IsAdmin']) ? 'admin' : 'customer';
    if ($account && $account['IsActive'] && $account['password'] === null) {
        $errors['email'] = 'Use Forgot Password to set a password for this account.';
    } elseif (!UserModel::canAuthenticate($account) || !AuthService::verify($password, $account['password'])) {
        $errors['email'] = 'Incorrect email or password!';
    } elseif ($role === 'customer' && !$account['EmailVerified']) {
        if (PasswordResetService::issue($account, 'verify')) {
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
            'schema_session' => 2,
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
    $account = $findAccount($email);
    if (UserModel::canAuthenticate($account) && PasswordResetService::issue($account, 'reset')) {
        $_SESSION['email'] = $account['email'];
        $_SESSION['info'] = 'Check your email for your password reset code.';
        $redirect('otp_reset.php');
    }
    $errors['email'] = 'Could not send a reset code for this address.';
} elseif (isset($_POST['check']) || isset($_POST['check-reset-otp'])) {
    $purpose = isset($_POST['check-reset-otp']) ? 'reset' : 'verify';
    $verified = PasswordResetService::verify($purpose, (string) ($_POST['otp'] ?? ''));
    if ($verified === null) {
        $errors['otp-error'] = 'The code is incorrect or has expired.';
    } elseif ($purpose === 'reset') {
        $account = $findAccount($verified['email']);
        if (!UserModel::canAuthenticate($account) || (string) $account['id'] !== $verified['id'] || $account['password'] !== $verified['password']) {
            $errors['otp-error'] = 'The reset code is no longer valid.';
            return;
        }
        $_SESSION['verified_reset'] = $verified;
        $_SESSION['info'] = 'Choose a new password.';
        $redirect('chg_pwd.php');
    } else {
        $conn->execute_query('UPDATE T_User SET EmailVerified = 1 WHERE Id = ? AND EmailKey = ? AND IsActive = 1',
            [$verified['id'], UserModel::emailKey($verified['email'])]);
        unset($_SESSION['info']);
        $redirect('Login.php');
    }
} elseif (isset($_POST['change-password'])) {
    $verified = $_SESSION['verified_reset'] ?? [];
    if (($verified['expires'] ?? 0) <= time() || $password !== $confirmation
        || strlen($password) < 8 || !AuthService::canHash($password)) {
        $errors['password'] = 'Use a valid reset code and matching passwords between 8 and 72 bytes.';
    } else {
        $conn->execute_query('UPDATE T_User SET PasswordHash = ?, EmailVerified = 1 WHERE Id = ? AND EmailKey = ? AND IsActive = 1 AND BINARY PasswordHash <=> ?',
            [password_hash($password, PASSWORD_DEFAULT), $verified['id'], UserModel::emailKey($verified['email']), $verified['password']]);
        unset($_SESSION['verified_reset']);
        if ($conn->affected_rows === 1) {
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
