<?php

namespace SneakerPeak\Core;

use SneakerPeak\App\Middleware\RequireSession;
use SneakerPeak\Manager\EncryptionManager;

require_once __DIR__ . '/Router.php';
require_once dirname(__DIR__) . '/Config/app.php';
require_once dirname(__DIR__) . '/App/Middleware/RequireSession.php';

final class Application
{
    public static function entryPoint(): ?string
    {
        $root = dirname(__DIR__, 2);
        $route = Router::resolve($_SERVER['REQUEST_URI'] ?? '/');
        if ($route === null) {
            http_response_code(404);
            echo 'Page not found.';
            return null;
        }

        define('SNEAKERPEAK_ROOT', $root);
        define('SNEAKERPEAK_ASSETS', $root . '/public/assets');
        require_once $root . '/Manager/CatalogImageUpload.php';

        if ($route === 'Controller/CatalogImageController.php') {
            return $root . '/' . $route;
        }
        if (!RequireSession::check($route)) {
            return null;
        }
        if (in_array($route, [
            'Controller/Admin/ProductController.php',
            'Controller/Admin/ProductDetailsController.php',
            'Controller/Admin/CreateProductController.php',
            'Controller/Admin/ProductStatusController.php',
        ], true)) {
            require_once $root . '/Manager/EncryptionManager.php';
            if (!EncryptionManager::isConfigured()) {
                error_log('SneakerPeak product links require OpenSSL and a valid PRODUCT_LINK_KEY.');
                http_response_code(503);
                echo 'Product management is temporarily unavailable.';
                return null;
            }
        }
        return $root . '/' . $route;
    }
}
