<?php

namespace App\Models;

use App\Enum\Models\ApproveStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $fillable = ['exam_id', 'desc', 'start_time', 'end_time', 'created_by', 'approve_status', 'approved_by'];

    protected $casts = [
        'approve_status' => ApproveStatus::class,
    ];

    public function exam(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Exam::class, 'id', 'exam_id');
    }

    public function exams(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Exam::class);
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function createdBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
