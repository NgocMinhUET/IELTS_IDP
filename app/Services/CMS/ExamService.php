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

    public function getPaginateExams()
    {
        return $this->examRepository->getPaginateExams();
    }

    public function getPickupExams()
    {
        return $this->examRepository->getPickupExams();
    }

    public function getExam($id)
    {
        return $this->examRepository->with('skills')->find($id);
    }

    public function storeExam($examPayload)
    {
        $examPayload['created_by'] = auth()->id();

        return $this->examRepository->create($examPayload);
    }

    public function updateExam($id, $payload)
    {
        return $this->examRepository->update($payload, $id);
    }

    public function updateApproveStatus($id, $status)
    {
        return $this->examRepository->update(['approve_status' => $status], $id);
    }
}
