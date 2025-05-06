<?php

namespace App\Models;

use App\Enum\QuestionTypeAPI;
use App\Models\Traits\HasQuestionOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChoiceQuestion extends Model
{
    use HasFactory;
    use HasQuestionOrder;

    protected $fillable = ['part_id', 'title', 'order'];

    protected $appends = [
        'type' // detail question type for API
    ];

    public function choiceSubQuestions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ChoiceSubQuestion::class, 'choice_question_id');
    }

    public function getTypeAttribute(): int
    {
        return QuestionTypeAPI::CHOICE->value;
    }
}