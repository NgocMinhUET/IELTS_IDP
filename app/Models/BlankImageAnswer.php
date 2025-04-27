<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlankImageAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'input_identify',
        'answer',
        'x',
        'y',
        'placeholder',
        'score',
    ];

    public function question()
    {
        return $this->belongsTo(BlankImageQuestion::class, 'question_id');
    }
}
