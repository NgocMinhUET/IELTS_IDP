<?php

namespace App\Models;

use App\Enum\Models\ExamSessionStatus;
use App\Models\Traits\HasEncryptedToken;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSession extends Model
{
    use HasFactory;
    use HasEncryptedToken;

    protected $fillable = ['test_id', 'exam_id', 'user_id', 'expired_at', 'status'];

    protected $casts = [
        'status' => ExamSessionStatus::class,
    ];

    public function exam(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function skillSessions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SkillSession::class, 'exam_session_id');
    }
}
