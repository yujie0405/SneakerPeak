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

        if (session_status() !== PHP_SESSION_ACTIVE) session_start();

        $id = $_SESSION['AdminID'] ?? $_SESSION['CustomerID'] ?? null;
        if ($id !== null) {
            $isAdmin = isset($_SESSION['AdminID']);
            $valid = false;
            if (($_SESSION['schema_session'] ?? null) === 2) {
                require_once SNEAKERPEAK_ROOT . '/src/Core/Database.php';
                $db = \SneakerPeak\Core\Database::getInstance()->getConnection();
                $valid = (bool) $db->execute_query(
                    'SELECT 1 FROM T_User u LEFT JOIN T_Admin a ON a.UserId = u.Id AND a.AdminFlag = 1
                     WHERE u.Id = ? AND u.IsActive = 1 AND u.IsAdmin = ?
                       AND u.IsAdmin = (a.UserId IS NOT NULL) AND (u.IsAdmin = 1 OR u.EmailVerified = 1)',
                    [$id, (int) $isAdmin]
                )->fetch_row();
            }
            if (!$valid) {
                $_SESSION = [];
                session_regenerate_id(true);
            }
        }
        if (!$adminAction && !$customerAction && !$adminPage && !$customerPage) return true;
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
