<?php

namespace App\Models;

use App\Enum\Models\ApproveStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'desc', 'created_by', 'approve_status', 'approved_by'];

    protected $casts = [
        'approve_status' => ApproveStatus::class,
    ];

    public function skills(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Skill::class, 'exam_id');
    }

    public function createdBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function tests(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Test::class);
    }

    public function scopeIsApproved(Builder $query): Builder
    {
        return $query->where('approve_status', ApproveStatus::APPROVED);
    }

    public function scopeIsPending(Builder $query): Builder
    {
        return $query->where('approve_status', ApproveStatus::PENDING);
    }
}
