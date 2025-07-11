<?php

namespace App\Repositories\Exam;

use App\Repositories\BaseInterface;

interface ExamInterface extends BaseInterface
{
    public function getPaginateExams($search);

    public function getPickupExams();

    public function countApprovedExamByIds(array $ids);
}
