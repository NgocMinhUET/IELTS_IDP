<?php

namespace App\Models;

use App\Models\Traits\HasQuestionOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LBlankContentQuestion extends Model
{
    use HasFactory;
    use HasQuestionOrder;

    protected $fillable = [
        'content_inherit',
        'content',
        'part_id',
        'title',
        'type',
        'answer_type',
        'answer_label',
        'order'
    ];

    const IS_CONTENT_INHERIT = true;
    const IS_CONTENT_NOT_INHERIT = false;

    public function answers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LBlankContentAnswer::class, 'question_id');
    }
}
