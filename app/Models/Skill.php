<?php

namespace App\Models;

use App\Enum\Models\SkillType;
use App\Models\Traits\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Skill extends Model
{
    use HasFactory;
    use HasMedia;

    protected $fillable = ['exam_id', 'type', 'desc', 'duration', 'bonus_time'];

    protected $casts = [
        'type' => SkillType::class
    ];

    protected $with = ['parts'];

    protected $appends = [
        'code',
        'skill_type',
    ];

    public string $defaultMediaCollection = 'audio';

    public function exam(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function parts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Part::class, 'skill_id');
    }

    public function getCodeAttribute(): string
    {
        return $this->id;
    }

    public function getSkillTypeAttribute()
    {
        return $this->getRawOriginal('type');
    }
}
