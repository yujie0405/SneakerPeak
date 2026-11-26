<?php

namespace SneakerPeak\App\Models;

use mysqli;

final class PromotionModel
{
    public static function availableFor(mysqli $connection, string $customerId, string $code): ?array
    {
        $statement = $connection->prepare(
            "SELECT p.Id, p.Code, p.PromotionValue AS Value, p.PromotionType AS Promote_type, p.Details
             FROM T_Promotecode p WHERE p.Code = ?
             AND p.IsActive = 1 AND (p.ExpiresOn IS NULL OR p.ExpiresOn >= CURRENT_DATE) AND NOT EXISTS (
                 SELECT 1 FROM T_Order o WHERE o.UserId = ? AND (o.PromotecodeId = p.Id OR o.PromotionCodeSnapshot = p.Code)
             ) LIMIT 1"
        );
        $statement->execute([$code, $customerId]);
        return $statement->get_result()->fetch_assoc();
    }
}
