<?php

namespace App\Models;

use App\Models\Traits\HasAnswerIdentify;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChoiceOptions extends Model
{
    use HasFactory;
    use HasAnswerIdentify;

    protected $fillable = ['choice_sub_question_id', 'answer', 'is_correct', 'score'];

    protected $appends = [
        'answer_identify',
    ];

    const ANSWER_IDENTIFY_PREFIX = 'CO';

    public function choiceSubQuestion(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ChoiceSubQuestion::class, 'choice_sub_question_id');
    }
}
