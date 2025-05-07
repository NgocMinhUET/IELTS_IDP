<?php

namespace App\Repositories\Exam;

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

    public function getPickupExams()
    {
        return $this->model->select('id', 'title')->get();
    }
}
