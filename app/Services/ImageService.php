<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ImageService
{
    // public static function uploadAndConvert($file, $directory, $disk = 'public', $quality = 80): string
    // {
    //     $manager = new ImageManager(new Driver());

    //     try {
    //         $image = $manager->read($file->getRealPath());
    //     } catch (\Exception $e) {
    //         throw new \RuntimeException('Could not read image: '.$e->getMessage());
    //     }

    //     // Auto rotate using EXIF
    //     try {
    //         method_exists($image, 'orient')
    //             ? $image->orient()
    //             : $image->orientate();
    //     } catch (\Throwable $e) {
    //         \Log::warning('EXIF orientation skipped: '.$e->getMessage());
    //     }

    //     // Resize large images
    //     if ($image->width() > 1600 || $image->height() > 1600) {
    //         $image->scaleDown(1600, 1600);
    //     }

    //     // Convert to WebP
    //     $encoded = $image->toWebp($quality);

    //     $filename = uniqid().'_'.Str::random(10).'.webp';

    //     $path = rtrim($directory, '/').'/'.$filename;

    //     $saved = Storage::disk($disk)->put($path, (string) $encoded);

    //     if (! $saved) {
    //         throw new \RuntimeException('Failed to save image.');
    //     }

    //     return $path;
    // }

    public static function uploadAndConvert($file, $directory, $disk = 'public', $quality = 80): string
    {
        $mime     = $file->getMimeType();
        $ext      = strtolower($file->getClientOriginalExtension());
        $realPath = $file->getRealPath();

        // Convert HEIC/HEIF to JPG using system ImageMagick
        $isHeic = in_array($mime, ['image/heic', 'image/heif'])
               || in_array($ext, ['heic', 'heif']);

        if ($isHeic) {
            $tempPath = sys_get_temp_dir().'/'.uniqid().'.jpg';

            $result = shell_exec(
                'convert '.escapeshellarg($realPath).' '.escapeshellarg($tempPath).' 2>&1'
            );

            if (! file_exists($tempPath)) {
                throw new \RuntimeException('HEIC conversion failed: '.$result);
            }

            $realPath = $tempPath;
        }

        $manager = new ImageManager(new Driver());

        try {
            $image = $manager->read($realPath);
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
        $path     = rtrim($directory, '/').'/'.$filename;
        $saved    = Storage::disk($disk)->put($path, (string) $encoded);

        if (! $saved) {
            throw new \RuntimeException('Failed to save image.');
        }

        // Cleanup temp file
        if ($isHeic && file_exists($tempPath)) {
            unlink($tempPath);
        }

        return $path;
    }
}
