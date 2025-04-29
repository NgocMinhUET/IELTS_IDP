<?php

namespace App\Models;

use App\Models\Traits\HasQuestionOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WritingQuestion extends Model
{
    use HasFactory;
    use HasQuestionOrder;

    protected $fillable = ['part_id', 'content'];

    protected $with = ['part'];

    public function part(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Part::class, 'part_id');
    }
}
