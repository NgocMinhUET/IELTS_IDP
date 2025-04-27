<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LBlankContentAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'input_identify',
        'answer',
        'placeholder',
        'score',
    ];

    public function question(): void
    {
        $this->belongsTo(LBlankContentQuestion::class, 'question_id');
    }
}
