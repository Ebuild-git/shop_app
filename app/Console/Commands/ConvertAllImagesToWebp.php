<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
// Alternative: use Intervention\Image\Drivers\Imagick\Driver;

class ConvertAllImagesToWebp extends Command
{
    protected $signature = 'images:convert-webp {--delete-original : Delete original files after conversion}';
    protected $description = 'Convert existing images to WebP format';

    public function handle()
    {
        $disk = Storage::disk('public');
        $manager = new ImageManager(new Driver());

        $deleteOriginal = $this->option('delete-original');

        $converted = 0;
        $skipped = 0;
        $errors = 0;

        $this->info('Starting image conversion to WebP...');
        $this->newLine();

        foreach ($disk->allFiles() as $file) {
            // Only process JPEG and PNG files
            if (!preg_match('/\.(jpe?g|png)$/i', $file)) {
                continue;
            }

            $sourcePath = $disk->path($file);
            $webpPath = preg_replace('/\.(jpe?g|png)$/i', '.webp', $sourcePath);

            // Skip if WebP already exists
            if (file_exists($webpPath)) {
                $this->warn("⊘ Already exists: {$file}");
                $skipped++;
                continue;
            }

            // Check if source is readable
            if (!is_readable($sourcePath)) {
                $this->error("✖ Unreadable: {$file}");
                $errors++;
                continue;
            }

            try {
                // Read and convert image
                $image = $manager->read($sourcePath);

                // Encode to WebP with quality 80
                $encoded = $image->toWebp(80);

                // Save the WebP file
                $encoded->save($webpPath);

                $this->info("✔ Converted: {$file}");
                $converted++;

                // Optionally delete original
                if ($deleteOriginal) {
                    unlink($sourcePath);
                    $this->comment("  → Original deleted");
                }

            } catch (\Exception $e) {
                $this->error("✖ Failed: {$file}");
                $this->error("  → " . $e->getMessage());
                $errors++;
            }
        }

        $this->newLine();
        $this->info("=== Conversion Complete ===");
        $this->table(
            ['Status', 'Count'],
            [
                ['Converted', $converted],
                ['Skipped', $skipped],
                ['Errors', $errors],
            ]
        );

        return $errors > 0 ? Command::FAILURE : Command::SUCCESS;
    }
}
