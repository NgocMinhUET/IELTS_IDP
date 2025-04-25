<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChoiceOptions extends Model
{
    use HasFactory;

    protected $fillable = ['choice_sub_question_id', 'answer', 'is_correct', 'score'];

    public function choiceSubQuestion(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ChoiceSubQuestion::class, 'choice_sub_question_id');
    }
}
