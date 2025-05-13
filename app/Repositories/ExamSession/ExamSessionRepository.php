<?php

namespace App\Repositories\ExamSession;

use App\Models\ExamSession;
use App\Repositories\BaseRepository;

class ExamSessionRepository extends BaseRepository implements ExamSessionInterface
{
    public function model(): string
    {
        return ExamSession::class;
    }

    public function getIssuedExamIds($testId, $userId)
    {
        return $this->model->where('test_id', $testId)
            ->where('user_id', $userId)
            ->get();
    }
}