<?php

use SneakerPeak\App\Services\AuthService;
use SneakerPeak\Manager\CatalogImageUpload;

require_once SNEAKERPEAK_ROOT . '/src/Core/Database.php';
$conn = \SneakerPeak\Core\Database::getInstance()->getConnection();
require_once SNEAKERPEAK_ROOT . '/src/App/Services/AuthService.php';

require_once SNEAKERPEAK_ROOT . '/src/App/Models/CustomerAddressModel.php';
use SneakerPeak\App\Models\CustomerAddressModel;
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
$addAddress = !$isAdmin && isset($_POST['add_address']);
$deleteAddress = !$isAdmin && isset($_POST['delete_address']);
$recipient = trim((string) ($_POST['recipient'] ?? ''));
$addressPhone = trim((string) ($_POST['phone'] ?? ''));
$address = trim((string) ($_POST['address'] ?? ''));
if ($addAddress || $deleteAddress) {
    $page = $_SESSION['page'] = 1;
}
if ($save || $change || $upload || $addAddress || $deleteAddress) {
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
            $conn->execute_query('UPDATE T_User SET DisplayName = ?, Phone = ? WHERE Id = ?', [$name, $phone, $profileId]);
            $_SESSION['name'] = $name;
            $_SESSION['profile_message'] = 'Your information has been updated.';
        } elseif ($addAddress) {
            CustomerAddressModel::validate($recipient, $addressPhone, $address);
            $conn->begin_transaction();
            try {
                CustomerAddressModel::add($conn, $profileId, $recipient, $addressPhone, $address);
                $conn->commit();
            } catch (Throwable $error) {
                $conn->rollback();
                throw $error;
            }
            $_SESSION['profile_message'] = 'Your address has been saved.';
        } elseif ($deleteAddress) {
            $addressId = (string) ($_POST['delete_address'] ?? '');
            if (!ctype_digit($addressId)) {
                throw new DomainException('Choose a saved address to delete.');
            }
            CustomerAddressModel::delete($conn, $profileId, $addressId);
            $_SESSION['profile_message'] = 'Your address has been deleted.';
        } elseif ($change) {
            if (!AuthService::changePassword($conn, $table, $profileId, (string) ($_POST['old_password'] ?? ''),
                (string) ($_POST['password'] ?? ''), (string) ($_POST['cpassword'] ?? ''))) {
                throw new DomainException('Check your current password and enter a matching new password.');
            }
            $_SESSION['profile_message'] = 'Your password has been updated.';
        } else {
            $filename = CatalogImageUpload::save($_FILES['image'], CatalogImageUpload::PROFILE);
            try {
                $update = $conn->prepare("UPDATE T_User SET ProfileImage = ? WHERE Id = ?");
                $update->execute([$filename, $profileId]);
            } catch (Throwable $error) {
                CatalogImageUpload::remove($filename);
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

$profile = $conn->execute_query(
    "SELECT Id AS id, DisplayName AS name, Email AS email, COALESCE(ProfileImage,'noprofil.jpg') AS image, Phone FROM T_User WHERE Id = ?",
    [$profileId]
)->fetch_assoc();
if (!$profile) {
    header('Location: Logout.php');
    exit;
}

if ($isAdmin) {
    $admin = $profile;
    $admin_name = $profile['name'];
    $admin_email = $profile['email'];
    $admin_phone = ($profile['Phone'] ?? '');
    require SNEAKERPEAK_ROOT . '/resources/views/admin/admin_profile.php';
} else {
    $user = $profile;
    $cus_name = $profile['name'];
    $cus_email = $profile['email'];
    $cus_phone = ($profile['Phone'] ?? '');
    $addresses = CustomerAddressModel::all($conn, $profileId);
    require SNEAKERPEAK_ROOT . '/resources/views/account/profile_1.php';
}
