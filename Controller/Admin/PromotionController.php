<?php

use SneakerPeak\App\Services\AdminDateRange;
use SneakerPeak\App\Services\CatalogInput;
use SneakerPeak\App\Services\EmailService;

require SNEAKERPEAK_ROOT . '/database/config.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Services/AdminDateRange.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Services/CatalogInput.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Services/EmailService.php';

$token = $_SESSION['promotion_admin_token'] ??= bin2hex(random_bytes(32));
$notice = $_SESSION['promotion_admin_notice'] ?? null;
unset($_SESSION['promotion_admin_notice']);
$error = null;
$promotionTypes = ['FIXED' => 'Value off', 'PERCENTAGE' => 'Percentage off', 'FREE_SHIPPING' => 'Free shipping'];
$filters = [];
foreach (['search', 'date_from', 'date_to'] as $field) {
    $filters[$field] = CatalogInput::text($_GET, $field);
}
$query = http_build_query(array_filter($filters, static fn(string $value): bool => $value !== ''));
$listUrl = 'admin_promote.php' . ($query === '' ? '' : '?' . $query);
$fields = array_fill_keys(['Promote_Code', 'Promote_CodeValue', 'ExpireDate', 'promotetype', 'CodeDetails'], '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($token, CatalogInput::text($_POST, 'promotion_token'))) {
        http_response_code(403);
        exit('Please reload the promotions page and try again.');
    }
    foreach ($fields as $field => $_) {
        $fields[$field] = CatalogInput::text($_POST, $field);
    }
    $code = $fields['Promote_Code'];
    $kind = match ($fields['promotetype']) {
        'Value Off' => 'FIXED', 'Percentage Off' => 'PERCENTAGE', 'Free Shipping' => 'FREE_SHIPPING',
        default => $fields['promotetype']
    };
    $fields['promotetype'] = $kind;
    $value = $fields['Promote_CodeValue'];
    $date = $fields['ExpireDate'];
    $details = $fields['CodeDetails'];
    if ($code === '' || mb_strlen($code) > 255 || !isset($promotionTypes[$kind])
        || !CatalogInput::validPrice($value) || ($kind === 'PERCENTAGE' && (float) $value > 100)
        || !AdminDateRange::valid($date) || mb_strlen($details) > 255) {
        $error = 'Enter a valid promotion code, value, type, expiry and description.';
    } else {
        try {
            $conn->execute_query(
                'INSERT INTO T_Promotecode (Code, PromotionType, PromotionValue, ExpiresOn, Details, IsActive)
                 VALUES (?, ?, ?, ?, ?, 1)', [$code, $kind, $value, $date, $details]
            );
        } catch (mysqli_sql_exception $exception) {
            $error = $exception->getCode() === 1062 ? 'This promotion code already exists.' : 'Could not save the promotion.';
        }
        if ($error === null) {
            $recipients = $conn->query('SELECT Email FROM T_User WHERE IsActive = 1 AND IsAdmin = 0 AND EmailVerified = 1');
            foreach ($recipients as $recipient) {
                EmailService::send($recipient['Email'], 'New Promotional Code Available',
                    "A new SneakerPeak promotional code is available: $code.");
            }
            $_SESSION['promotion_admin_notice'] = 'Promotion added.';
            header('Location: ' . $listUrl, true, 303);
            exit;
        }
    }
}

$filterError = AdminDateRange::error($_GET['date_from'] ?? '', $_GET['date_to'] ?? '');
$promotions = [];
if ($filterError === null) {
    $conditions = [];
    $parameters = [];
    foreach (['search' => 'LOCATE(?, Code) > 0', 'date_from' => 'ExpiresOn >= ?', 'date_to' => 'ExpiresOn <= ?'] as $field => $condition) {
        if ($filters[$field] !== '') {
            $conditions[] = $condition;
            $parameters[] = $filters[$field];
        }
    }
    $where = $conditions ? ' WHERE ' . implode(' AND ', $conditions) : '';
    $promotions = $conn->execute_query(
        "SELECT Code, PromotionType, PromotionValue, ExpiresOn, Details,
            IF(IsActive = 0, 'Inactive', IF(ExpiresOn < CURRENT_DATE, 'Expired', 'Available')) AS Status
         FROM T_Promotecode" . $where . ' ORDER BY Id DESC', $parameters
    )->fetch_all(MYSQLI_ASSOC);
}
require SNEAKERPEAK_ROOT . '/resources/views/admin/admin_promote.php';
