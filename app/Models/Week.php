<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Week extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['semester_id', 'title', 'order'];

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function trainings()
    {
        return $this->hasMany(Training::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }
}
