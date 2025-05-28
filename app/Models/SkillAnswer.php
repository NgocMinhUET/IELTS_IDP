<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['skill_session_id', 'question_model', 'question_id', 'question_type', 'answer', 'answer_result', 'score'];

    public function skillSession(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SkillSession::class, 'skill_session_id');
    }
}
