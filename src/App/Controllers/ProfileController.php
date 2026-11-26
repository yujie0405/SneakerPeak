<?php

use SneakerPeak\App\Services\AuthService;

require SNEAKERPEAK_ROOT . '/database/config.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Services/AuthService.php';

$table = $isAdmin ? 'admin' : 'customer';
$profileId = (string) $_SESSION[$isAdmin ? 'AdminID' : 'CustomerID'];
$profileUrl = $isAdmin ? 'admin_profile.php' : 'profile_1.php';
$token = $_SESSION['profile_token'] ??= bin2hex(random_bytes(32));
$message = $_SESSION['profile_message'] ?? null;
unset($_SESSION['profile_message']);

$routeName = strtolower(basename((string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
$tab = match ($routeName) {
    'account_setting.php', 'admin_account_setting.php' => 1,
    'password_setting.php', 'admin_password_setting.php' => 2,
    default => (int) ($_POST['page'] ?? $_SESSION['page'] ?? 1),
};
$page = $_SESSION['page'] = $tab === 2 ? 2 : 1;

$save = isset($_POST['update']);
$change = isset($_POST['change']);
$upload = isset($_FILES['image']);
if ($save || $change || $upload) {
    if (!hash_equals($token, (string) ($_POST['profile_token'] ?? ''))) {
        http_response_code(403);
        exit('Please reload your profile and try again.');
    }
    try {
        if ($save) {
            $prefix = $isAdmin ? 'admin' : 'cus';
            $name = trim((string) ($_POST[$prefix . '_name'] ?? ''));
            $phone = trim((string) ($_POST[$prefix . '_number'] ?? ''));
            if ($name === '') {
                throw new DomainException('Enter your name.');
            }
            if ($isAdmin) {
                $update = $conn->prepare('UPDATE admin SET name = ?, Admin_ContactNumber = ? WHERE id = ?');
                $update->execute([$name, $phone, $profileId]);
            } else {
                $update = $conn->prepare(
                    'UPDATE customer SET name = ?, Customer_Address = ?, Customer_Address_2 = ?, Customer_ContractNumber = ? WHERE id = ?'
                );
                $update->execute([
                    $name, (string) ($_POST['cus_address'] ?? ''), (string) ($_POST['cus_address_2'] ?? ''), $phone, $profileId,
                ]);
            }
            $_SESSION['name'] = $name;
            $_SESSION['profile_message'] = 'Your information has been updated.';
        } elseif ($change) {
            if (!AuthService::changePassword($conn, $table, $profileId, (string) ($_POST['old_password'] ?? ''),
                (string) ($_POST['password'] ?? ''), (string) ($_POST['cpassword'] ?? ''))) {
                throw new DomainException('Check your current password and enter a matching new password.');
            }
            $_SESSION['profile_message'] = 'Your password has been updated.';
        } else {
            $image = $_FILES['image'];
            if ($image['error'] !== UPLOAD_ERR_OK || $image['size'] > 1200000 || !is_uploaded_file($image['tmp_name'])) {
                throw new DomainException('Choose a JPEG or PNG under 1.2 MB.');
            }
            $info = @getimagesize($image['tmp_name']);
            $extension = match ($info[2] ?? null) {
                IMAGETYPE_JPEG => 'jpg',
                IMAGETYPE_PNG => 'png',
                default => throw new DomainException('Choose a JPEG or PNG image.'),
            };
            $filename = bin2hex(random_bytes(16)) . '.' . $extension;
            $destination = SNEAKERPEAK_ASSETS . '/img/' . $filename;
            if (!move_uploaded_file($image['tmp_name'], $destination)) {
                throw new DomainException('The image could not be saved.');
            }
            try {
                $update = $conn->prepare("UPDATE `$table` SET image = ? WHERE id = ?");
                $update->execute([$filename, $profileId]);
            } catch (Throwable $error) {
                unlink($destination);
                throw $error;
            }
            $_SESSION['profile_message'] = 'Your profile photo has been updated.';
        }
        header('Location: ' . $profileUrl);
        exit;
    } catch (DomainException $error) {
        $message = $error->getMessage();
    } catch (Throwable $error) {
        error_log('SneakerPeak profile could not save the requested change.');
        $message = 'Your profile could not be saved. Please try again.';
    }
}

$contactField = $isAdmin ? 'Admin_ContactNumber' : 'Customer_ContractNumber';
$addressFields = $isAdmin ? '' : ', Customer_Address, Customer_Address_2';
$statement = $conn->prepare("SELECT id, name, email, image, `$contactField` $addressFields FROM `$table` WHERE id = ?");
$statement->execute([$profileId]);
$profile = $statement->get_result()->fetch_assoc();
if (!$profile) {
    header('Location: Logout.php');
    exit;
}

if ($isAdmin) {
    $admin = $profile;
    $admin_name = $profile['name'];
    $admin_email = $profile['email'];
    $admin_phone = $profile[$contactField];
    require SNEAKERPEAK_ROOT . '/resources/views/admin/admin_profile.php';
} else {
    $user = $profile;
    $cus_name = $profile['name'];
    $cus_email = $profile['email'];
    $cus_phone = $profile[$contactField];
    $cus_address = $profile['Customer_Address'];
    $cus_address2 = $profile['Customer_Address_2'];
    require SNEAKERPEAK_ROOT . '/resources/views/account/profile_1.php';
}