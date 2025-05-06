<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $fillable = ['exam_id', 'desc', 'start_time', 'end_time'];

    public function exam(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Exam::class, 'id', 'exam_id');
    }
}
