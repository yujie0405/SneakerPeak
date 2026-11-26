<?php

use SneakerPeak\Manager\CatalogImageUpload;

$path = $_GET['path'] ?? null;
$file = is_string($path) ? CatalogImageUpload::managedFile($path) : null;
if ($file === null || !is_file($file[0]) || !is_readable($file[0])) {
    http_response_code(404);
    exit;
}

header('Content-Type: ' . $file[1]);
header('X-Content-Type-Options: nosniff');
header('Cache-Control: public, max-age=86400');
readfile($file[0]);
