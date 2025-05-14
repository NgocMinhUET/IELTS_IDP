<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use HasFactory;

    protected $fillable = ['skill_id', 'title', 'desc', 'layout'];

    public function skill(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Skill::class, 'skill_id');
    }

    public function paragraph(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Paragraph::class, 'part_id');
    }
}