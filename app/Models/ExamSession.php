<?php

namespace App\Models;

use App\Enum\Models\ExamSessionStatus;
use App\Models\Traits\HasEncryptedToken;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSession extends Model
{
    use HasFactory;
    use HasEncryptedToken;

    protected $fillable = ['test_id', 'exam_id', 'user_id', 'status'];

    protected $casts = [
        'status' => ExamSessionStatus::class,
    ];
}
