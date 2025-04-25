<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChoiceSubQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['choice_question_id', 'question', 'min_option', 'max_option'];

    public function choiceQuestion(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ChoiceQuestion::class, 'choice_question_id');
    }

    public function choiceOptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ChoiceOptions::class, 'choice_sub_question_id');
    }
}
