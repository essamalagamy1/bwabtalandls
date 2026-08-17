<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Semester extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['grade_id', 'name', 'is_active', 'start_date', 'end_date'];

    protected $casts = [
        'is_active'  => 'boolean',
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function weeks()
    {
        return $this->hasMany(Week::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }
}
