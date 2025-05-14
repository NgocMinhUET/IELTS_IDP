<?php

namespace App\Models;

use App\Models\Traits\HasAnswerIdentify;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LBlankContentAnswer extends Model
{
    use HasFactory;
    use HasAnswerIdentify;

    protected $fillable = [
        'question_id',
        'input_identify',
        'answer',
        'placeholder',
        'score',
    ];

    protected $appends = [
        'answer_identify',
    ];

    const ANSWER_IDENTIFY_PREFIX = 'BCA';

    public function question(): void
    {
        $this->belongsTo(LBlankContentQuestion::class, 'question_id');
    }
}
