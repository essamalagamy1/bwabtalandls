<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Grade extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['stage_id', 'name'];

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function semesters()
    {
        return $this->hasMany(Semester::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }
}
