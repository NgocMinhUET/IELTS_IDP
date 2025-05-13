<?php

namespace App\Models;

use App\Enum\Models\SkillSessionStatus;
use App\Models\Traits\HasEncryptedToken;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillSession extends Model
{
    use HasFactory;
    use HasEncryptedToken;

    protected $fillable = ['exam_session_id', 'skill_id', 'expired_at', 'submit_expired_at', 'status'];

    protected $casts = [
        'status' => SkillSessionStatus::class,
    ];

    public function skill(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Skill::class, 'skill_id');
    }

    public function examSession(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ExamSession::class, 'exam_session_id');
    }

    public function skillAnswers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SkillAnswer::class, 'skill_session_id');
    }

    public function getSecondsRemainingAttribute(): int
    {
        return max(0, now()->diffInSeconds($this->submit_expired_at, false));
    }
}
