<?php

namespace SneakerPeak\Manager;

use DomainException;

final class CatalogImageUpload
{
    public const PRODUCT = 'Product';
    public const PROFILE = 'UserProfile';
    public const LOGO = 'Logo';

    private const LEGACY_URLS = [
        self::PRODUCT => 'prod/',
        self::PROFILE => 'img/',
        self::LOGO => 'image/logo/',
    ];

    private const MIME_TYPES = [
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
        'webp' => 'image/webp',
        'gif' => 'image/gif',
    ];

    public static function baseDirectory(): string
    {
        $configured = trim((string) \sneakerpeakSetting('IMAGE_BASE_DIR'));
        if ($configured === '') {
            $configured = 'storage/catalog-images';
        }
        if (!preg_match('~^(?:[A-Za-z]:[/\\\\]|[/\\\\])~', $configured)) {
            $configured = \SNEAKERPEAK_ROOT . '/' . $configured;
        }
        return rtrim($configured, '/\\');
    }

    public static function save(array $upload, string $type): string
    {
        $limit = $type === self::PROFILE ? 1200000 : 2000000;
        if (!isset(self::LEGACY_URLS[$type])) {
            throw new DomainException('Unsupported image type.');
        }
        $temporary = $upload['tmp_name'] ?? '';
        if (($upload['error'] ?? null) !== UPLOAD_ERR_OK || !is_uploaded_file($temporary)
            || filesize($temporary) > $limit) {
            $maximum = $type === self::PROFILE ? '1.2 MB' : '2 MB';
            throw new DomainException("Choose an image smaller than $maximum.");
        }
        $info = @getimagesize($temporary);
        $extension = match ($info[2] ?? null) {
            IMAGETYPE_JPEG => 'jpg',
            IMAGETYPE_PNG => 'png',
            IMAGETYPE_WEBP => 'webp',
            IMAGETYPE_GIF => 'gif',
            default => null,
        };
        if ($extension === null || ($type === self::PROFILE && !in_array($extension, ['jpg', 'png'], true))) {
            throw new DomainException($type === self::PROFILE
                ? 'Choose a JPEG or PNG image.' : 'Choose a JPEG, PNG, WebP or GIF image.');
        }
        $directory = self::baseDirectory() . '/' . $type;
        if (!is_dir($directory) && !@mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new DomainException('The image directory could not be created.');
        }
        $relative = $type . '/' . bin2hex(random_bytes(16)) . '.' . $extension;
        if (!move_uploaded_file($temporary, self::baseDirectory() . '/' . $relative)) {
            throw new DomainException('The image could not be saved.');
        }
        return $relative;
    }

    public static function url(?string $stored, string $type = self::PRODUCT): string
    {
        $legacyPrefix = self::LEGACY_URLS[$type] ?? null;
        if ($legacyPrefix === null) {
            return '';
        }

        $stored ??= '';
        if (str_starts_with($stored, $type . '/') && self::managedFile($stored) !== null) {
            return 'catalog-image.php?path=' . rawurlencode($stored);
        }
        $filename = str_starts_with($stored, $legacyPrefix) ? substr($stored, strlen($legacyPrefix)) : $stored;
        if ($filename === '' || strpbrk($filename, "/\\\0") !== false || $filename === '.' || $filename === '..') {
            return $type === self::PROFILE ? 'img/noprofil.jpg' : '';
        }
        if ($type === self::PRODUCT) {
            return 'catalog-image.php?path=' . rawurlencode('Product/Imported/' . $filename);
        }
        return $legacyPrefix . rawurlencode($filename);
    }

    public static function managedFile(string $relative): ?array
    {
        if (preg_match('~^Product/Imported/([^/]+)$~D', $relative, $parts)) {
            $filename = $parts[1];
            if (strpbrk($filename, "\\\0") !== false || $filename === '.' || $filename === '..') {
                return null;
            }
            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $mime = self::MIME_TYPES[$extension === 'jpeg' ? 'jpg' : $extension] ?? null;
            return $mime === null ? null : [\SNEAKERPEAK_ROOT . '/storage/catalog-images/' . $relative, $mime];
        }
        if (!preg_match('~^(Product|UserProfile|Logo)/[a-f0-9]{32}\.(jpg|png|webp|gif)$~D', $relative, $parts)) {
            return null;
        }
        return [self::baseDirectory() . '/' . $relative, self::MIME_TYPES[$parts[2]]];
    }

    public static function remove(string $relative): void
    {
        if (str_starts_with($relative, 'Product/Imported/')) {
            return;
        }
        $file = self::managedFile($relative);
        if ($file !== null && is_file($file[0]) && !@unlink($file[0])) {
            error_log('SneakerPeak could not remove a managed image.');
        }
    }
}
