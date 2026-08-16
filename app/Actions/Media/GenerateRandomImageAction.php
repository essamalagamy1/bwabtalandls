<?php

namespace App\Actions\Media;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class GenerateRandomImageAction
{
    public function execute(HasMedia&Model $model, string $collection = 'image', string $disk = 'public'): void
    {
        $stubPath = base_path('public/logo.png');
        if (! file_exists($stubPath)) {
            throw new \RuntimeException('Stub image not found.');
        }
        // Prevent duplicates if re-seeding
        if ($model->getMedia($collection)->isNotEmpty()) {
            return;
        }

        $model->addMedia($stubPath)->preservingOriginal()->toMediaCollection($collection, $disk);
    }
}
