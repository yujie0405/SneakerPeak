<?php

namespace SneakerPeak\App\Middleware;

final class RequireSession
{
    private const ADMIN_ACTIONS = [
        'available_item.php', 'delete_item.php', 'update_status.php',
        'set_session.php', 'changestatus.php', 'telluser.php', 'linechar.php',
    ];

    private const CUSTOMER_ACTIONS = [
        'delete_wish.php', 'fetch_address.php', 'plush.php', 'minus.php', 'updateask.php',
    ];

    private const CUSTOMER_PAGES = [
        'customerprofilecontroller.php', 'ordercontroller.php', 'addresscontroller.php',
        'paymentcontroller.php',
    ];

    public static function check(string $route): bool
    {
        $name = strtolower(basename($route));
        $adminAction = in_array($name, self::ADMIN_ACTIONS, true);
        $customerAction = in_array($name, self::CUSTOMER_ACTIONS, true);
        $adminPage = str_starts_with($route, 'resources/views/admin/')
            || str_starts_with($route, 'src/App/Controllers/Admin/');
        $customerPage = in_array($name, self::CUSTOMER_PAGES, true);

        if (!$adminAction && !$customerAction && !$adminPage && !$customerPage) {
            return true;
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
        }
        $sessionKey = $adminAction || $adminPage ? 'AdminID' : 'CustomerID';
        if (isset($_SESSION[$sessionKey])) {
            return true;
        }

        if (($adminPage || $customerPage) && ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET') {
            header('Location: Login.php');
        } else {
            http_response_code(403);
            echo 'Access denied.';
        }
        return false;
    }
}
