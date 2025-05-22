<?php

namespace App\Repositories\Test;

use App\Enum\Models\ApproveStatus;
use App\Enum\Models\ExamSessionStatus;
use App\Models\Test;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class TestRepository extends BaseRepository implements TestInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Test::class;
    }

    public function getPaginateTests()
    {
        $query = $this->model->with('createdBy')->withCount('exams');

        $user = auth()->user();

        if ($user->isTeacher()) {
            $query->where('approve_status', ApproveStatus::APPROVED)
                ->orWhere(function ($query) use ($user) {
                    $query->where('created_by', $user->id);
                });
        }

        return $query->paginate(10);
    }

    public function getAssignedToUserTests($userId)
    {
        return $this->getAssignedToUserTestsQuery($userId)
            ->select('id', 'desc', 'start_time', 'end_time')
            ->orderByDefault()
            ->get();
    }

    private function getAssignedToUserTestsQuery($userId)
    {
        return $this->model
            ->isActive()
            ->whereHas('exams')
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            });
    }

    public function getAssignedToUserTest($id, $userId)
    {
        return $this->getAssignedToUserTestsQuery($userId)
            ->where('id', $id)
            ->first();
    }

    public function getTestHistories($userId)
    {
        $examSessionStatus = [
            ExamSessionStatus::COMPLETE->value,
            ExamSessionStatus::IN_COMPLETE->value,
        ];

        return $this->model
            ->select('id', 'desc', 'start_time', 'end_time')
            ->whereIn('id', DB::table('exam_sessions')
                ->select('test_id')
                ->where('user_id', $userId)
                ->whereIn('status', $examSessionStatus)
                ->distinct()
                ->pluck('test_id')
                ->toArray()
            )
            ->orderByDefault()
            ->get();
    }

    public function getPaginateHistoryTests()
    {
        return $this->model
            ->isApproved()
            ->withCount('exams')
            ->withCount('users')
            ->with('examSessions')
            ->orderByDefault()
            ->paginate(8);
    }

    public function getDetailHistoryTest($testId)
    {

    }
}
