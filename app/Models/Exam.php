<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'desc'];

    public function skills(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Skill::class, 'exam_id');
    }
}
