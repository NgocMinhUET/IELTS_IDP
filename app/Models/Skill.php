<?php

namespace App\Models;

use App\Enum\Models\SkillType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = ['exam_id', 'type', 'desc', 'duration', 'bonus_time'];

    protected $casts = [
        'type' => SkillType::class
    ];

    protected $with = ['parts'];

    public function exam(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function parts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Part::class, 'skill_id');
    }
}
