<?php

namespace SneakerPeak\Core;

use mysqli;
use RuntimeException;
use Throwable;

final class Database
{
    private static ?self $instance = null;
    private mysqli $connection;

    private function __construct()
    {
        $this->connection = self::connect();
    }

    public static function getInstance(): self
    {
        return self::$instance ??= new self();
    }

    public function getConnection(): mysqli
    {
        return $this->connection;
    }

    public static function connect(): mysqli
    {
        $settings = require dirname(__DIR__) . '/Config/database.php';

        try {
            $port = filter_var($settings['port'], FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 1, 'max_range' => 65535],
            ]);

            if (!$settings['host'] || !$settings['database'] || !$settings['username']
                || $settings['password'] === null || $port === false) {
                throw new RuntimeException('Invalid database configuration');
            }

            $connection = @new mysqli(
                $settings['host'], $settings['username'], $settings['password'],
                $settings['database'], $port
            );

            if ($connection->connect_errno) {
                throw new RuntimeException('Database connection failed');
            }

            return $connection;
        } catch (Throwable $error) {
            error_log('SneakerPeak database unavailable. Check local database configuration and service.');
            http_response_code(503);
            exit('The service is temporarily unavailable.');
        }
    }
}
