<?php

namespace App\Services\CMS;

use App\Repositories\Exam\ExamInterface;
use App\Services\BaseService;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        return $this->examRepository->with('skills')
            ->withCount('tests')
            ->find($id);
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
        $exam = $this->examRepository->withCount('tests')->find($id);
        if ($exam->tests_count) {
            throw new HttpException(409, 'The exam has already been assigned.');
        }

        return $this->examRepository->update(['approve_status' => $status], $id);
    }
}
