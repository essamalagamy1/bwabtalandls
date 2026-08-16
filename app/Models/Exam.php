<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Exam extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'week_id',
        'semester_id',
        'title',
        'description',
        'duration_minutes',
        'passing_score',
        'assignment_date',
    ];

    protected $casts = [
        'assignment_date' => 'datetime',
    ];

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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }
}
