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

        // Read the image
        $image = $manager->read($file);

        // Encode to WebP
        $encoded = $image->toWebp($quality);

        // Generate a unique filename
        $filename = uniqid() . '_' . Str::random(10) . '.webp';
        $path = rtrim($directory, '/') . '/' . $filename;

        // Save to storage
        Storage::disk($disk)->put($path, (string) $encoded);

        return $path;
    }
}
