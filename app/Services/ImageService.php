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
    public static function uploadAndConvert($file, $directory, $disk = 'public', $quality = 80)
    {
        $manager = new ImageManager(new Driver());

        $mimeType = $file->getMimeType();
        $originalPath = $file->getRealPath();
        $tempJpegPath = null; // 👈 properly initialize this

        // Convert HEIC/HEIF to JPEG first (GD can't read HEIC)
        if (
            in_array($mimeType, ['image/heic', 'image/heif']) ||
            in_array(strtolower($file->getClientOriginalExtension()), ['heic', 'heif'])
        ) {
            $converted = self::convertHeicToJpeg($originalPath);

            if (!$converted) {
                throw new \Exception('HEIC conversion failed. Please upload a JPEG or PNG image.');
            }

            $tempJpegPath = $converted; // 👈 track it for cleanup
            $originalPath = $converted;
        }

        // Read image
        $image = $manager->read($originalPath);

        // Fix EXIF orientation — method name differs by version
        try {
            if (method_exists($image, 'orient')) {
                $image->orient();       // Intervention Image v3.x
            } elseif (method_exists($image, 'orientate')) {
                $image->orientate();    // Intervention Image v2.x
            }
        } catch (\Exception $e) {
            // Don't crash if orientation fix fails — just skip it
            \Log::warning('Image orientation fix failed: ' . $e->getMessage());
        }

        // Resize if larger than 1600px on any side (keeps aspect ratio, never upscales)
        if ($image->width() > 1600 || $image->height() > 1600) {
            $image->scaleDown(1600, 1600);
        }

        // Encode to WebP
        $encoded = $image->toWebp($quality);

        // Generate unique filename
        $filename = uniqid() . '_' . Str::random(10) . '.webp';
        $path = rtrim($directory, '/') . '/' . $filename;

        // Save to storage
        Storage::disk($disk)->put($path, (string) $encoded);

        // Clean up temp HEIC conversion file
        if ($tempJpegPath && file_exists($tempJpegPath)) { // 👈 now correctly tracked
            @unlink($tempJpegPath);
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
