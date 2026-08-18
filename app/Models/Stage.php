<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];


    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    protected static function booted()
    {
        static::updated(function ($stage) {
            if ($stage->wasChanged('is_active') && !$stage->is_active) {
                $stage->grades->each(function ($grade) {
                    $grade->update(['is_active' => false]);
                });
            }
        });
    }

}
