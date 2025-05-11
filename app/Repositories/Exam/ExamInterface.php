<?php

namespace App\Repositories\Exam;

use App\Repositories\BaseInterface;

interface ExamInterface extends BaseInterface
{
    public function getPaginateExams();

    public function getPickupExams();
}
