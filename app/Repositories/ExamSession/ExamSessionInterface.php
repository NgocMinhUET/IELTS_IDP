<?php

namespace App\Repositories\ExamSession;

use App\Repositories\BaseInterface;

interface ExamSessionInterface extends BaseInterface
{
    public function getIssuedExamIds($testId, $userId);
}
