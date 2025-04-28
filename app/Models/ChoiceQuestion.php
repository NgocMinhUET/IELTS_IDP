<?php

namespace App\Models;

use App\Models\Traits\HasQuestionOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChoiceQuestion extends Model
{
    use HasFactory;
    use HasQuestionOrder;

    protected $fillable = ['part_id', 'title', 'order'];

    public function choiceSubQuestions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ChoiceSubQuestion::class, 'choice_question_id');
    }
}