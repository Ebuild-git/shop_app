<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Upload an image, convert it to WebP, and save it.
     *
     * @param mixed $file The uploaded file (UploadedFile or TemporaryUploadedFile)
     * @param string $directory The directory to save the file in (relative to specific disk root)
     * @param string $disk The storage disk to use (default: public)
     * @param int $quality WebP quality (default: 80)
     * @return string The relative path to the saved file
     */
    public static function uploadAndConvert($file, $directory, $disk = 'public', $quality = 80): string
    {
        $manager = new ImageManager(new Driver());

        $realPath = $file->getRealPath();
        $mime = $file->getMimeType();
        $ext  = strtolower($file->getClientOriginalExtension());
        $tempPath = null;

        // HEIC fallback — should rarely hit this now that the client converts it
        $isHeic = in_array($mime, ['image/heic', 'image/heif'])
            || in_array($ext, ['heic', 'heif']);

        if ($isHeic) {
            if (!extension_loaded('imagick')) {
                throw new \RuntimeException('HEIC images are not supported. Please upload a JPEG or PNG.');
            }
            $tempPath = self::convertHeicToJpeg($realPath);
            if (!$tempPath) {
                throw new \RuntimeException('HEIC conversion failed. Please upload a JPEG or PNG.');
            }
            $realPath = $tempPath;
        }

        try {
            $image = $manager->read($realPath);
        } catch (\Exception $e) {
            if ($tempPath) @unlink($tempPath);
            throw new \RuntimeException('Could not read image: ' . $e->getMessage());
        }

        // Auto-rotate based on EXIF — try both v2 and v3 method names
        try {
            method_exists($image, 'orient') ? $image->orient() : $image->orientate();
        } catch (\Throwable $e) {
            \Log::warning('EXIF orientation skipped: ' . $e->getMessage());
        }

        if ($image->width() > 1600 || $image->height() > 1600) {
            $image->scaleDown(1600, 1600);
        }

        $encoded = $image->toWebp($quality);

        $filename = uniqid() . '_' . Str::random(10) . '.webp';
        $path = rtrim($directory, '/') . '/' . $filename;
        Storage::disk($disk)->put($path, (string) $encoded);

        if ($tempPath && file_exists($tempPath)) {
            @unlink($tempPath);
        }

        return $path;
    }

    /**
     * Convert a HEIC/HEIF file to a temporary JPEG using ImageMagick.
     * Returns the temp JPEG path, or null on failure.
     */
    private static function convertHeicToJpeg(string $heicPath): ?string
    {
        // Requires ImageMagick installed on the server
        if (!extension_loaded('imagick')) {
            return null;
        }

        try {
            $imagick = new \Imagick();
            $imagick->readImage($heicPath);
            $imagick->setImageFormat('jpeg');
            $imagick->setImageCompressionQuality(90);

            $tempPath = sys_get_temp_dir() . '/' . uniqid('heic_', true) . '.jpg';
            $imagick->writeImage($tempPath);
            $imagick->destroy();

            return $tempPath;
        } catch (\Exception $e) {
            \Log::warning('HEIC conversion failed: ' . $e->getMessage());
            return null;
        }
    }
}
