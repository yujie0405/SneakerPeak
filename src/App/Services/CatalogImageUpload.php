<?php

namespace SneakerPeak\App\Services;

use DomainException;

final class CatalogImageUpload
{
    public static function save(array $upload, string $directory): string
    {
        if (($upload['error'] ?? null) !== UPLOAD_ERR_OK || !is_uploaded_file($upload['tmp_name'])) {
            throw new DomainException('Choose an image to upload.');
        }
        $info = @getimagesize($upload['tmp_name']);
        $extension = match ($info[2] ?? null) {
            IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png',
            IMAGETYPE_WEBP => 'webp', IMAGETYPE_GIF => 'gif', default => null,
        };
        if (!$extension) throw new DomainException('Choose a JPEG, PNG, WebP or GIF image.');
        $name = bin2hex(random_bytes(16)) . '.' . $extension;
        if (!move_uploaded_file($upload['tmp_name'], $directory . $name)) {
            throw new DomainException('Could not save the image.');
        }
        return $name;
    }
}
