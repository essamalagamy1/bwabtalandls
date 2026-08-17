<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    use HasFactory;

    protected $fillable = ['semester_id', 'title', 'order', 'is_active', 'start_date', 'end_date'];

    protected $casts = [
        'is_active'  => 'boolean',
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

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
}
