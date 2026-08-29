<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'user_id',
        'total_score',
        'status',
        'media_views_count',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'media_views_count' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function remainingMediaViews(): int
    {
        if ($this->exam?->isUnlimitedMediaViews()) {
            return -1; // unlimited
        }

        return max(0, ($this->exam?->media_views_limit ?? 0) - $this->media_views_count);
    }

    public function canViewMedia(): bool
    {
        if ($this->exam?->isUnlimitedMediaViews()) {
            return true;
        }

        return $this->media_views_count < ($this->exam?->media_views_limit ?? 0);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(StudentAnswer::class);
    }
}
