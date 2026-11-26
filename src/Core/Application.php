<?php

namespace SneakerPeak\Core;

use SneakerPeak\App\Middleware\RequireSession;

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
        return RequireSession::check($route) ? $root . '/' . $route : null;
    }
}
