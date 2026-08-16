<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageConversionService
{
    public static function convertToWebP(string $originalPath, string $folder, string $disk = 'public'): string
    {

        // Get the full path to the original image
        $fullPath = Storage::disk($disk)->path($originalPath);

        // Check if file exists
        if (! file_exists($fullPath)) {
            return $originalPath;
        }

        // Create image manager with GD driver
        $manager = new ImageManager(new Driver);

        // Read and convert image
        $image = $manager->read($fullPath);

        // Generate new filename with .webp extension
        $filename = pathinfo($originalPath, PATHINFO_FILENAME);
        $newPath = $folder.'/'.uniqid().'_'.$filename.'.webp';

        // Save as WebP with 100% quality
        $webpPath = Storage::disk($disk)->path($newPath);
        $image->toWebp(100)->save($webpPath);

        // Delete original file
        Storage::disk($disk)->delete($originalPath);

        Log::info($newPath);

        return $newPath;
    }
}
