<?php

require_once __DIR__ . '/app.php';

return [
    'host' => sneakerpeakSetting('DB_HOST'),
    'port' => sneakerpeakSetting('DB_PORT'),
    'database' => sneakerpeakSetting('DB_DATABASE'),
    'username' => sneakerpeakSetting('DB_USERNAME'),
    'password' => sneakerpeakSetting('DB_PASSWORD'),
];
