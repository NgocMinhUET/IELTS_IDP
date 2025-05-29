<?php

namespace App\Models;

use App\Enum\QuestionTypeAPI;
use App\Models\Traits\HasInputIdentify;
use App\Models\Traits\HasQuestionOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpeakingQuestion extends Model
{
    use HasFactory;
    use HasQuestionOrder;
    use HasInputIdentify;

    protected $fillable = ['part_id', 'content', 'score'];

    protected $with = ['part'];

    protected $appends = [
        'input_identify',
        'type',
    ];

    const INPUT_IDENTIFY_PREFIX = 'SQ';

    public function part(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Part::class, 'part_id');
    }

    public function getTypeAttribute(): int
    {
        return QuestionTypeAPI::SPEAKING->value;
    }
}
