<?php

namespace SneakerPeak\Core;

final class Router
{
    public static function resolve(string $requestUri): ?string
    {
        $routes = require dirname(__DIR__) . '/Config/routes.php';
        $path = parse_url($requestUri, PHP_URL_PATH);
        if (!is_string($path)) {
            return null;
        }

        $path = preg_replace('~^/SneakerPeak(?=/|$)~i', '', $path);
        return $routes[strtolower(trim($path, '/'))] ?? null;
    }
}
