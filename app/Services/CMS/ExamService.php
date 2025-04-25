<?php

namespace App\Services\CMS;

use App\Repositories\Exam\ExamInterface;
use App\Services\BaseService;

/**
 * Class ExamService.
 *
 * @package namespace App\Services\CMS;
 */
class ExamService extends BaseService
{
    public function __construct(
        public ExamInterface $examRepository,
    ) {}

    public function getExam($id)
    {
        return $this->examRepository->with('skills')->find($id);
    }

    public function storeExam($examPayload)
    {
        return $this->examRepository->create($examPayload);
    }
}
