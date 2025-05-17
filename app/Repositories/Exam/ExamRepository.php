<?php

namespace App\Repositories\Exam;

use App\Enum\Models\ApproveStatus;
use App\Models\Exam;
use App\Repositories\BaseRepository;

class ExamRepository extends BaseRepository implements ExamInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Exam::class;
    }

    public function getPaginateExams()
    {
        $query = $this->model->with('createdBy')->withCount('tests');

        $user = auth()->user();

        if ($user->isTeacher()) {
            $query->where('approve_status', ApproveStatus::APPROVED)
                ->orWhere(function ($query) use ($user) {
                    $query->where('created_by', $user->id);
                });
        }

        return $query->paginate(10);
    }

    public function getPickupExams()
    {
        return $this->model->select('id', 'title')->with('skills:id,exam_id,type')
            ->isApproved()->get();
    }

    public function countApprovedExamByIds(array $ids)
    {
        return $this->model->whereIn('id', $ids)
            ->isApproved()->count();
    }
}
