<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Training extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'week_id',
        'semester_id',
        'title',
        'description',
        'type',
        'url',
        'publish_date',
        'is_published',
    ];

    protected $casts = [
        'publish_date' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function week()
    {
        return $this->belongsTo(Week::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('training_file')->singleFile();
    }
}
