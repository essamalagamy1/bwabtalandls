<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Semester extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'grade_id',
        'name',
        'is_active',
        'start_date',
        'end_date',
        'academic_year_from',
        'academic_year_to',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'academic_year_from' => 'integer',
        'academic_year_to' => 'integer',
    ];

    protected $appends = [
        'academic_year',
        'name_with_academic_year',
        'name_with_year',
    ];

    public function getAcademicYearAttribute(): ?string
    {
        $from = $this->academic_year_from ?: ($this->start_date?->format('Y') ?? null);
        $to = $this->academic_year_to ?: ($this->end_date?->format('Y') ?? null);

        if ($from && $to) {
            return "{$from} - {$to}";
        }

        return $from ?: ($to ?: null);
    }

    public function getNameAttribute($value): string
    {
        $name = $value ?? ($this->attributes['name'] ?? '');
        $academicYear = $this->academic_year;

        return $academicYear ? "{$name}  {$academicYear}" : $name;
    }

    public function getRawNameAttribute(): string
    {
        return $this->getRawOriginal('name') ?? ($this->attributes['name'] ?? '');
    }

    public function getNameWithAcademicYearAttribute(): string
    {
        return $this->name;
    }

    public function getNameWithYearAttribute(): string
    {
        return $this->name;
    }

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

    protected static function booted()
    {
        static::updated(function ($semester) {
            if ($semester->wasChanged('is_active') && ! $semester->is_active) {
                $semester->weeks->each(function ($week) {
                    $week->update(['is_active' => false]);
                });
            }
        });
    }
}
