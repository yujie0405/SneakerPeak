<?php

namespace SneakerPeak\Manager;

use RuntimeException;

final class EncryptionManager
{
    public const PRODUCT_DETAILS = 'sneakerpeak:admin-product-id:v1';

    private const CIPHER = 'aes-256-gcm';
    private const NONCE_LENGTH = 12;
    private const TAG_LENGTH = 16;

    public static function isConfigured(): bool
    {
        return extension_loaded('openssl') && self::key() !== null;
    }

    public static function encrypt(string $value, string $purpose): string
    {
        $nonce = random_bytes(self::NONCE_LENGTH);
        $ciphertext = openssl_encrypt(
            $value, self::CIPHER, self::key() ?? throw new RuntimeException('Missing encryption key.'),
            OPENSSL_RAW_DATA, $nonce, $tag, $purpose, self::TAG_LENGTH
        );
        if ($ciphertext === false) {
            throw new RuntimeException('Could not encrypt the link token.');
        }
        return self::encode($nonce . $tag . $ciphertext);
    }

    public static function decrypt(mixed $token, string $purpose): ?string
    {
        if (!is_string($token) || !preg_match('/\A[A-Za-z0-9_-]{1,256}\z/', $token)) {
            return null;
        }
        $decoded = base64_decode(strtr($token, '-_', '+/'), true);
        $headerLength = self::NONCE_LENGTH + self::TAG_LENGTH;
        if ($decoded === false || strlen($decoded) <= $headerLength || self::encode($decoded) !== $token) {
            return null;
        }
        $plaintext = openssl_decrypt(
            substr($decoded, $headerLength), self::CIPHER, self::key() ?? throw new RuntimeException('Missing encryption key.'),
            OPENSSL_RAW_DATA, substr($decoded, 0, self::NONCE_LENGTH),
            substr($decoded, self::NONCE_LENGTH, self::TAG_LENGTH), $purpose
        );
        return $plaintext === false ? null : $plaintext;
    }

    private static function key(): ?string
    {
        static $key = false;
        if ($key === false) {
            $decoded = base64_decode((string) \sneakerpeakSetting('PRODUCT_LINK_KEY'), true);
            $key = $decoded !== false && strlen($decoded) === 32 ? $decoded : null;
        }
        return $key;
    }

    private static function encode(string $bytes): string
    {
        return rtrim(strtr(base64_encode($bytes), '+/', '-_'), '=');
    }
}
