<?php

namespace SneakerPeak\App\Services;

require_once __DIR__ . '/EmailService.php';

final class PasswordResetService
{
    public static function issue(array $account, string $purpose): bool
    {
        $code = random_int(100000, 999999);
        $subject = $purpose === 'reset' ? 'Password reset code' : 'Email verification code';
        if (($_SESSION['otp_issued_at'] ?? 0) > time() - 30) return false;
        $_SESSION['otp_issued_at'] = time();
        if (!EmailService::send($account['email'], $subject, "Your SneakerPeak code is $code. It expires in 10 minutes.")) {
            return false;
        }
        $_SESSION['otp'] = [
            'id' => (string) $account['id'], 'email' => $account['email'], 'purpose' => $purpose,
            'hash' => password_hash((string) $code, PASSWORD_DEFAULT),
            'password' => $account['password'] ?? null,
            'expires' => time() + 600, 'attempts' => 0,
        ];
        return true;
    }

    public static function verify(string $purpose, string $code): ?array
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
        if (empty($attempt['hash']) || !password_verify($code, $attempt['hash'])) {
            return null;
        }
        unset($_SESSION['otp']);
        unset($attempt['hash']);
        return $attempt;
    }
}
