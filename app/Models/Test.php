<?php

namespace App\Models;

use App\Enum\Models\ApproveStatus;
use Illuminate\Database\Eloquent\Builder;
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

    public function examSessions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ExamSession::class, 'test_id');
    }

    public function createdBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function scopeIsApproved(Builder $query): Builder
    {
        return $query->where('approve_status', ApproveStatus::APPROVED);
    }

    public function scopeInPublicDateTime(Builder $query): Builder
    {
        $now = now();
        return $query->where(function ($subQuery) use ($now) {
            $subQuery->where(function ($query) use ($now) {
                $query->where('start_time', '<=', $now)
                    ->where('end_time', '>=', $now);
            })
                ->orWhere(function ($query) use ($now) {
                    $query->whereNull('start_time')
                        ->where('end_time', '>=', $now);
                })
                ->orWhere(function ($query) use ($now) {
                    $query->whereNull('end_time')
                        ->where('start_time', '<=', $now);
                })
                ->orWhere(function ($query) {
                    $query->whereNull('start_time')
                        ->whereNull('end_time');
                });
        });
    }

    public function scopeIsActive(Builder $query): Builder
    {
        return $query->isApproved()->inPublicDateTime();
    }

    public function scopeOrderByDefault(Builder $query): Builder
    {
        return $query->orderByDesc('start_time')
            ->orderByDesc('id');
    }
}
