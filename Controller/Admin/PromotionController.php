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
$promotionTypes = $conn->query('SELECT Id, Name FROM E_PromoteType ORDER BY Id')->fetch_all(MYSQLI_ASSOC);
$filters = [];
foreach (['search', 'date_from', 'date_to'] as $field) {
    $filters[$field] = CatalogInput::text($_GET, $field);
}
$filterQuery = array_filter($filters, static fn(string $value): bool => $value !== '');
$promotionUrl = static function (?string $editId = null) use ($filterQuery): string {
    $params = $editId === null ? $filterQuery : $filterQuery + ['edit' => $editId];
    return 'admin_promote.php' . ($params ? '?' . http_build_query($params) : '');
};
$listUrl = $promotionUrl();
$editId = CatalogInput::id($_GET['edit'] ?? null);
$editing = isset($_GET['edit']);
$fields = array_fill_keys(['Promote_Code', 'Promote_CodeValue', 'ExpireDate', 'type_id', 'CodeDetails'], '');

if ($editing) {
    $stored = $editId === null ? null : $conn->execute_query(
        'SELECT Code, PromotionValue, ExpiresOn, TypeId, Details FROM T_Promotecode WHERE Id = ?', [$editId]
    )->fetch_assoc();
    if ($stored === null) {
        http_response_code(404);
        exit('Promotion not found.');
    }
    $fields = [
        'Promote_Code' => $stored['Code'], 'Promote_CodeValue' => $stored['PromotionValue'],
        'ExpireDate' => $stored['ExpiresOn'] ?? '', 'type_id' => (string) $stored['TypeId'],
        'CodeDetails' => $stored['Details'],
    ];
}
$formUrl = $promotionUrl($editId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($token, CatalogInput::text($_POST, 'promotion_token'))) {
        http_response_code(403);
        exit('Please reload the promotions page and try again.');
    }
    foreach ($fields as $field => $_) {
        $fields[$field] = CatalogInput::text($_POST, $field);
    }
    $code = $fields['Promote_Code'];
    $value = $fields['Promote_CodeValue'];
    $date = $fields['ExpireDate'];
    $details = $fields['CodeDetails'];
    $typeId = CatalogInput::id($fields['type_id']);
    $type = $typeId === null ? null : $conn->execute_query(
        'SELECT Id, Code FROM E_PromoteType WHERE Id = ?', [$typeId]
    )->fetch_assoc();

    if ($type === null) {
        $error = 'Choose a valid promotion type.';
    } elseif ($code === '' || mb_strlen($code) > 255
        || !CatalogInput::validPrice($value) || ($type['Code'] === 'PERCENTAGE' && (float) $value > 100)
        || ((!$editing || $date !== '') && !AdminDateRange::valid($date)) || mb_strlen($details) > 255) {
        $error = 'Enter a valid promotion code, value, expiry and description.';
    } else {
        try {
            $columns = [
                'Code' => $code, 'TypeId' => $type['Id'], 'PromotionValue' => $value,
                'ExpiresOn' => $date === '' ? null : $date, 'Details' => $details,
            ];
            // Dual-write only between the prepare and finalize migration steps.
            if ($conn->query("SHOW COLUMNS FROM T_Promotecode LIKE 'PromotionType'")->num_rows !== 0) {
                $columns['PromotionType'] = $type['Code'];
            }
            $assignments = implode(', ', array_map(static fn(string $column): string => "$column = ?", array_keys($columns)));
            $values = array_values($columns);
            if ($editing) {
                $conn->execute_query("UPDATE T_Promotecode SET $assignments WHERE Id = ?", [...$values, $editId]);
            } else {
                $conn->execute_query("INSERT INTO T_Promotecode SET $assignments, IsActive = 1", $values);
            }
        } catch (mysqli_sql_exception $exception) {
            $error = $exception->getCode() === 1062 ? 'This promotion code already exists.' : 'Could not save the promotion.';
        }
        if ($error === null) {
            if (!$editing) {
                $recipients = $conn->query('SELECT Email FROM T_User WHERE IsActive = 1 AND IsAdmin = 0 AND EmailVerified = 1');
                foreach ($recipients as $recipient) {
                    EmailService::send($recipient['Email'], 'New Promotional Code Available',
                        "A new SneakerPeak promotional code is available: $code.");
                }
            }
            $_SESSION['promotion_admin_notice'] = $editing ? 'Promotion updated.' : 'Promotion added.';
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
    foreach (['search' => 'LOCATE(?, p.Code) > 0', 'date_from' => 'p.ExpiresOn >= ?', 'date_to' => 'p.ExpiresOn <= ?'] as $field => $condition) {
        if ($filters[$field] !== '') {
            $conditions[] = $condition;
            $parameters[] = $filters[$field];
        }
    }
    $where = $conditions ? ' WHERE ' . implode(' AND ', $conditions) : '';
    $promotions = $conn->execute_query(
        "SELECT p.Id, p.Code, pt.Name AS PromotionTypeName, p.PromotionValue, p.ExpiresOn, p.Details,
            IF(p.IsActive = 0, 'Inactive', IF(p.ExpiresOn < CURRENT_DATE, 'Expired', 'Available')) AS Status
         FROM T_Promotecode p JOIN E_PromoteType pt ON pt.Id = p.TypeId" . $where . ' ORDER BY p.Id DESC', $parameters
    )->fetch_all(MYSQLI_ASSOC);
}
require SNEAKERPEAK_ROOT . '/resources/views/admin/admin_promote.php';
