<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = ['stage_id', 'name', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];


    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function semesters()
    {
        return $this->hasMany(Semester::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    protected static function booted()
    {
        static::updated(function ($grade) {
            if ($grade->wasChanged('is_active') && !$grade->is_active) {
                $grade->semesters->each(function ($semester) {
                    $semester->update(['is_active' => false]);
                });
                $grade->sections->each(function ($section) {
                    $section->update(['is_active' => false]);
                });
            }
        });
    }

}
