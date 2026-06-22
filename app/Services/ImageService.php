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

        $isHeic = in_array($mime, ['image/heic', 'image/heif'])
               || in_array($ext, ['heic', 'heif']);

        $tempPath = null;

        if ($isHeic) {
            $tempPath = sys_get_temp_dir() . '/' . uniqid() . '.jpg';
            $cmd      = 'convert ' . escapeshellarg($realPath) . ' ' . escapeshellarg($tempPath) . ' 2>&1';

            \Log::info('ImageService: running convert', ['cmd' => $cmd]);

            $result = shell_exec($cmd);

            \Log::info('ImageService: convert result', [
                'output'   => $result,
                'tempPath' => $tempPath,
                'exists'   => file_exists($tempPath),
            ]);

            if (! file_exists($tempPath)) {
                throw new \RuntimeException('HEIC conversion failed: ' . $result);
            }

            $realPath = $tempPath;
        }

        $manager = new ImageManager(new Driver());

        try {
            $image = $manager->read($realPath);
        } catch (\Exception $e) {
            throw new \RuntimeException('Could not read image: ' . $e->getMessage());
        }

        try {
            $image->orient();
        } catch (\Throwable $e) {
            \Log::warning('EXIF orientation skipped: ' . $e->getMessage());
        }

        if ($image->width() > 1600 || $image->height() > 1600) {
            $image->scaleDown(1600, 1600);
        }

        $encoded  = $image->toWebp($quality);
        $filename = uniqid() . '_' . Str::random(10) . '.webp';
        $path     = rtrim($directory, '/') . '/' . $filename;
        $saved    = Storage::disk($disk)->put($path, (string) $encoded);

        if (! $saved) {
            throw new \RuntimeException('Failed to save image.');
        }

        if ($isHeic && $tempPath && file_exists($tempPath)) {
            unlink($tempPath);
        }

        return $path;
    }
}
