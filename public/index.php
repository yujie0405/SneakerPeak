<?php

require dirname(__DIR__) . '/src/Core/Application.php';

$entryPoint = \SneakerPeak\Core\Application::entryPoint();
if ($entryPoint !== null) {
    require $entryPoint;
}
