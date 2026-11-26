<?php

namespace SneakerPeak\App\Models;

use mysqli;

final class PromotionModel
{
    public static function availableFor(mysqli $connection, string $customerId, string $code): ?array
    {
        $statement = $connection->prepare(
            "SELECT p.* FROM promote_code p WHERE p.Promote_Code = ?
             AND p.PromoteCode_Status = 'Available' AND NOT EXISTS (
                 SELECT 1 FROM oder o WHERE o.UID = ? AND o.Promote_Code = p.Promote_Code
             ) LIMIT 1"
        );
        $statement->execute([$code, $customerId]);
        return $statement->get_result()->fetch_assoc();
    }
}
