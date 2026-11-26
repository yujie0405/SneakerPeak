<?php

function sneakerpeakSetting(string $name): ?string
{
    static $settings = null;

    if ($settings === null) {
        $path = dirname(__DIR__, 2) . '/.env';
        $settings = is_readable($path) ? (@parse_ini_file($path, false, INI_SCANNER_RAW) ?: []) : [];
    }

    $value = getenv($name);
    return $value !== false ? $value : ($settings[$name] ?? null);
}
