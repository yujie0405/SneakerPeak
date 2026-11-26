<?php

namespace SneakerPeak\App\Services;

use mysqli;

require_once __DIR__ . '/EmailService.php';

final class PasswordResetService
{
    public static function issue(mysqli $connection, array $account, string $purpose): bool
    {
        $code = random_int(100000, 999999);
        $subject = $purpose === 'reset' ? 'Password reset code' : 'Email verification code';
        $statement = $connection->prepare('UPDATE customer SET code = ? WHERE id = ?');
        $statement->execute([$code, $account['id']]);
        if (!EmailService::send($account['email'], $subject, "Your SneakerPeak code is $code. It expires in 10 minutes.")) {
            return false;
        }
        $_SESSION['otp'] = [
            'id' => $account['id'], 'purpose' => $purpose,
            'expires' => time() + 600, 'attempts' => 0,
        ];
        return true;
    }

    public static function verify(mysqli $connection, string $purpose, string $code): ?array
    {
        $attempt = $_SESSION['otp'] ?? [];
        if (($attempt['purpose'] ?? null) !== $purpose
            || ($attempt['expires'] ?? 0) <= time() || ($attempt['attempts'] ?? 5) >= 5) {
            return null;
        }
        ++$_SESSION['otp']['attempts'];
        if (!preg_match('/^\d{6}$/D', $code)) {
            return null;
        }
        $statement = $connection->prepare('SELECT id FROM customer WHERE id = ? AND code = ?');
        $statement->execute([$attempt['id'], $code]);
        if (!$statement->get_result()->fetch_row()) {
            return null;
        }
        unset($_SESSION['otp']);
        return $attempt + ['code' => $code];
    }
}
