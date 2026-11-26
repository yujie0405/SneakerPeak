<?php

use SneakerPeak\App\Models\UserModel;
use SneakerPeak\App\Services\AuthService;
use SneakerPeak\App\Services\CatalogInput;

require_once SNEAKERPEAK_ROOT . '/src/Core/Database.php';
$conn = \SneakerPeak\Core\Database::getInstance()->getConnection();
require_once SNEAKERPEAK_ROOT . '/src/App/Models/UserModel.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Services/AuthService.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Services/CatalogInput.php';

$token = $_SESSION['create_admin_token'] ??= bin2hex(random_bytes(32));
$notice = $_SESSION['create_admin_notice'] ?? null;
unset($_SESSION['create_admin_notice']);
$errors = [];
$name = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($token, CatalogInput::text($_POST, 'admin_token'))) {
        http_response_code(403);
        exit('Please reload the administrator page and try again.');
    }
    $name = CatalogInput::text($_POST, 'name');
    $email = CatalogInput::text($_POST, 'email');
    $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
    if ($name === '' || mb_strlen($name) > 255) {
        $errors['name'] = 'Enter a name of up to 255 characters.';
    }
    if (strlen($email) > 255 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Enter a valid email address.';
    }
    if (!AuthService::canHash($password) || strlen($password) < 8
        || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password)
        || !preg_match('/[0-9]/', $password) || !preg_match('/[[:punct:]]/', $password)) {
        $errors['password'] = 'Use 8 to 72 bytes, with an uppercase letter, a lowercase letter, a number and a special character.';
    }
    if ($password === '' || $password !== ($_POST['cpassword'] ?? null)) {
        $errors['cpassword'] = 'Enter the matching password.';
    }

    if (!$errors) {
        $conn->begin_transaction();
        try {
            $conn->execute_query(
                'INSERT INTO T_User (DisplayName, Email, EmailKey, PasswordHash, IsAdmin, EmailVerified)
                 VALUES (?, ?, ?, ?, 1, 1)',
                [$name, $email, UserModel::emailKey($email), password_hash($password, PASSWORD_DEFAULT)]
            );
            $conn->execute_query(
                'INSERT INTO T_Admin (UserId, AdminFlag, GrantedDate, GrantedByUserId) VALUES (?, 1, UTC_TIMESTAMP(6), ?)',
                [$conn->insert_id, $_SESSION['AdminID']]
            );
            $conn->commit();
            $_SESSION['create_admin_notice'] = 'Admin added.';
            header('Location: add_newAdmin.php', true, 303);
            exit;
        } catch (Throwable $exception) {
            $conn->rollback();
            if ($exception->getCode() === 1062) {
                $errors['email'] = 'This email already exists.';
            } else {
                $errors['form'] = 'Could not add the administrator.';
            }
        }
    }
    http_response_code(422);
}

$admins = $conn->query(
    'SELECT u.DisplayName, u.Email FROM T_User u JOIN T_Admin a ON a.UserId = u.Id
     WHERE u.IsAdmin = 1 AND a.AdminFlag = 1 ORDER BY u.DisplayName, u.Id'
)->fetch_all(MYSQLI_ASSOC);
require SNEAKERPEAK_ROOT . '/resources/views/admin/add_newAdmin.php';
