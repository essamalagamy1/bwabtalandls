<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Exam extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'week_id',
        'semester_id',
        'title',
        'description',
        'duration_minutes',
        'passing_score',
        'media_views_limit',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'media_views_limit' => 'integer',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachment')->singleFile();
    }

    public function hasAttachment(): bool
    {
        return $this->hasMedia('attachment');
    }

    public function getAttachmentTypeAttribute(): ?string
    {
        $media = $this->getFirstMedia('attachment');
        if (! $media) {
            return null;
        }

        $mimeType = strtolower($media->mime_type ?? '');
        $extension = strtolower(pathinfo($media->file_name ?? '', PATHINFO_EXTENSION));

        $audioExtensions = ['mp3', 'wav', 'ogg', 'm4a', 'aac', 'flac', 'wma', 'opus', 'weba', 'mid', 'midi'];
        $imageExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'bmp', 'ico', 'avif'];
        $videoExtensions = ['mp4', 'webm', 'ogg', 'mov', 'avi', 'wmv', 'mkv'];
        $pdfExtensions = ['pdf'];

        if (str_starts_with($mimeType, 'audio/') || in_array($extension, $audioExtensions, true)) {
            return 'audio';
        }

        if (str_starts_with($mimeType, 'image/') || in_array($extension, $imageExtensions, true)) {
            return 'image';
        }

        if (str_starts_with($mimeType, 'video/') || in_array($extension, $videoExtensions, true)) {
            return 'video';
        }

        if ($mimeType === 'application/pdf' || in_array($extension, $pdfExtensions, true)) {
            return 'pdf';
        }

        return 'file';
    }

    public function isUnlimitedMediaViews(): bool
    {
        return (int) $this->media_views_limit === 0;
    }

    public function week()
    {
        return $this->belongsTo(Week::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }
}
