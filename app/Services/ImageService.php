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
        $mime     = $file->getMimeType();
        $ext      = strtolower($file->getClientOriginalExtension());
        $realPath = $file->getRealPath();

        \Log::info('ImageService: processing file', [
            'mime'     => $mime,
            'ext'      => $ext,
            'realPath' => $realPath,
            'exists'   => file_exists($realPath),
            'size'     => file_exists($realPath) ? filesize($realPath) : 0,
        ]);

        // Convert HEIC/HEIF to JPG using system ImageMagick
        $isHeic = in_array($mime, ['image/heic', 'image/heif'])
               || in_array($ext, ['heic', 'heif']);

        if ($isHeic) {
            $tempPath = sys_get_temp_dir().'/'.uniqid().'.jpg';

            \Log::info('ImageService: running convert', ['cmd' => $cmd]);

            $result = shell_exec(
                'convert '.escapeshellarg($realPath).' '.escapeshellarg($tempPath).' 2>&1'
            );

            \Log::info('ImageService: convert result', [
                'output'   => $result,
                'tempPath' => $tempPath,
                'exists'   => file_exists($tempPath),
            ]);

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
        // if ($image->width() > 1600 || $image->height() > 1600) {
        //     $image->scaleDown(1600, 1600);
        // }
        $width  = $image->width();
        $height = $image->height();

        // Resize: cap max dimension
        if ($image->width() > 1600 || $image->height() > 1600) {
            $image->scaleDown(1600, 1600);
        }

        // Upscale if too small for crisp display
        if ($image->width() < 480 || $image->height() < 600) {
            $image->resize(480, 600, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize(); // remove this line to ALLOW upscaling
            });
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
