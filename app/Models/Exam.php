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
        if (str_starts_with($media->mime_type, 'audio/')) {
            return 'audio';
        }
        if (str_starts_with($media->mime_type, 'image/')) {
            return 'image';
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
