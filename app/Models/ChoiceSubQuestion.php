<?php

namespace App\Models;

use App\Models\Traits\HasInputIdentify;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChoiceSubQuestion extends Model
{
    use HasFactory;
    use HasInputIdentify;

    protected $fillable = ['choice_question_id', 'question', 'min_option', 'max_option'];

    protected $appends = [
        'input_identify',
    ];

    const INPUT_IDENTIFY_PREFIX = 'CSQ';

    public function choiceQuestion(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ChoiceQuestion::class, 'choice_question_id');
    }

    public function choiceOptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ChoiceOptions::class, 'choice_sub_question_id');
    }
}
