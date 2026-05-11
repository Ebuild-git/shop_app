<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    public static function uploadAndConvert($file, $directory, $disk = 'public', $quality = 80): string
    {
        $manager = new ImageManager(new Driver());

        try {
            $image = $manager->read($file->getRealPath());
        } catch (\Exception $e) {
            throw new \RuntimeException('Could not read image: '.$e->getMessage());
        }

        // Auto rotate using EXIF
        try {
            method_exists($image, 'orient')
                ? $image->orient()
                : $image->orientate();
        } catch (\Throwable $e) {
            \Log::warning('EXIF orientation skipped: '.$e->getMessage());
        }

        // Resize large images
        if ($image->width() > 1600 || $image->height() > 1600) {
            $image->scaleDown(1600, 1600);
        }

        // Convert to WebP
        $encoded = $image->toWebp($quality);

        $filename = uniqid().'_'.Str::random(10).'.webp';

        $path = rtrim($directory, '/').'/'.$filename;

        $saved = Storage::disk($disk)->put($path, (string) $encoded);

        if (! $saved) {
            throw new \RuntimeException('Failed to save image.');
        }

        return $path;
    }
}
