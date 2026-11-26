<?php

// Prepare the lookup before deploying the new PHP code. Finalize after verifying the switch.
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
$phase = $argv[1] ?? '';
if (!in_array($phase, ['--prepare', '--finalize'], true)) {
    fwrite(STDERR, "Usage: php database/migrations/003-promotion-types.php --prepare|--finalize\n");
    exit(1);
}
require dirname(__DIR__) . '/config.php';

try {
    $database = $conn->query('SELECT DATABASE()')->fetch_row()[0];
    $legacy = $conn->query("SHOW COLUMNS FROM T_Promotecode LIKE 'PromotionType'")->num_rows > 0;
    $typeColumn = $conn->query("SHOW COLUMNS FROM T_Promotecode LIKE 'TypeId'")->fetch_assoc();

    if ($phase === '--prepare') {
        if (!$legacy && $typeColumn === null) {
            throw new RuntimeException('No promotion type column is available to backfill.');
        }
        $conn->query(
            "CREATE TABLE IF NOT EXISTS E_PromoteType (
                Id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                Code VARCHAR(24) NOT NULL UNIQUE,
                Name VARCHAR(100) NOT NULL,
                CreatedDate DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
                AdjustedDate DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );
        $conn->query(
            "INSERT INTO E_PromoteType (Code, Name)
             VALUES ('FIXED', 'Value Off'), ('PERCENTAGE', 'Percentage Off'), ('FREE_SHIPPING', 'Free Shipping')
             ON DUPLICATE KEY UPDATE Code = E_PromoteType.Code"
        );
        if ($typeColumn === null) {
            $conn->query('ALTER TABLE T_Promotecode ADD COLUMN TypeId BIGINT UNSIGNED NULL AFTER Code');
            $typeColumn = $conn->query("SHOW COLUMNS FROM T_Promotecode LIKE 'TypeId'")->fetch_assoc();
        }
    }
    if ($typeColumn === null || !preg_match('/^bigint(?:\(\d+\))? unsigned$/i', $typeColumn['Type'])) {
        throw new RuntimeException('Expected a BIGINT UNSIGNED TypeId. Run --prepare before finalizing.');
    }

    $supported = (int) $conn->query(
        "SELECT COUNT(*) FROM E_PromoteType WHERE BINARY Code IN ('FIXED', 'PERCENTAGE', 'FREE_SHIPPING')"
    )->fetch_row()[0];
    if ($supported !== 3) {
        throw new RuntimeException('The lookup is missing a supported promotion code in its exact spelling.');
    }

    if ($phase === '--prepare' && $legacy) {
        $conn->query(
            'UPDATE T_Promotecode p JOIN E_PromoteType pt ON BINARY pt.Code = BINARY p.PromotionType
             SET p.TypeId = pt.Id, p.AdjustedDate = p.AdjustedDate WHERE p.TypeId IS NULL'
        );
    }

    $unmapped = (int) $conn->query(
        'SELECT COUNT(*) FROM T_Promotecode p LEFT JOIN E_PromoteType pt ON pt.Id = p.TypeId WHERE pt.Id IS NULL'
    )->fetch_row()[0];
    if ($unmapped !== 0) {
        throw new RuntimeException("$unmapped promotion(s) have a missing or invalid TypeId. Resolve them before proceeding.");
    }
    if ($legacy) {
        $mismatched = (int) $conn->query(
            'SELECT COUNT(*) FROM T_Promotecode p JOIN E_PromoteType pt ON pt.Id = p.TypeId
             WHERE p.PromotionType IS NOT NULL AND BINARY pt.Code <> BINARY p.PromotionType'
        )->fetch_row()[0];
        if ($mismatched !== 0) {
            throw new RuntimeException("$mismatched promotion(s) have conflicting type values. Resolve them before proceeding.");
        }
    }

    if ($phase === '--finalize') {
        $foreignKeys = $conn->query(
            "SELECT REFERENCED_TABLE_SCHEMA, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
             FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE() AND LOWER(TABLE_NAME) = 't_promotecode'
               AND COLUMN_NAME = 'TypeId' AND REFERENCED_TABLE_NAME IS NOT NULL"
        )->fetch_all(MYSQLI_ASSOC);
        foreach ($foreignKeys as $key) {
            if ($key['REFERENCED_TABLE_SCHEMA'] !== $database
                || strcasecmp($key['REFERENCED_TABLE_NAME'], 'E_PromoteType') !== 0
                || strcasecmp($key['REFERENCED_COLUMN_NAME'], 'Id') !== 0) {
                throw new RuntimeException('TypeId already has a foreign key to a different lookup.');
            }
        }
        $changes = [];
        if ($typeColumn['Null'] === 'YES') $changes[] = 'MODIFY COLUMN TypeId BIGINT UNSIGNED NOT NULL';
        if (!$foreignKeys) $changes[] = 'ADD CONSTRAINT fk_promotecode_type FOREIGN KEY (TypeId) REFERENCES E_PromoteType (Id) ON DELETE RESTRICT';
        if ($legacy) $changes[] = 'DROP COLUMN PromotionType';
        if ($changes) $conn->query('ALTER TABLE T_Promotecode ' . implode(', ', $changes));
    }

    $count = (int) $conn->query('SELECT COUNT(*) FROM T_Promotecode')->fetch_row()[0];
    echo "$database: verified $count promotion type mappings ($phase).", PHP_EOL;
} catch (Throwable $error) {
    fwrite(STDERR, $error->getMessage() . PHP_EOL);
    exit(1);
}
