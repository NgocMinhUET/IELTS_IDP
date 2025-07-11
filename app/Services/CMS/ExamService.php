<?php

namespace App\Services\CMS;

use App\Enum\Models\ApproveStatus;
use App\Repositories\Exam\ExamInterface;
use App\Services\BaseService;
use Illuminate\Http\Request;
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

    public function getPaginateExams(Request $request)
    {
        return $this->examRepository->getPaginateExams($request->input('search'));
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
        $user = auth()->user();
        $examPayload['created_by'] = $user->id;

        if ($user->isAdmin()) {
            $examPayload['approve_status'] = ApproveStatus::APPROVED;
        }

        return $this->examRepository->create($examPayload);
    }

    public function updateExam($id, $payload)
    {
        $user = auth()->user();
        if ($user->isAdmin()) {
            $payload['approve_status'] = ApproveStatus::APPROVED;
        } else {
            $payload['approve_status'] = ApproveStatus::PENDING;
        }

        return $this->examRepository->update($payload, $id);
    }

    public function updateApproveStatus($id, $status)
    {
        $exam = $this->examRepository->withCount('tests')->find($id);
        if ($exam->tests_count) {
            throw new HttpException(409, 'The exam has already been assigned.');
        }

        return $this->examRepository->update([
            'approve_status' => $status,
            'approved_by' => auth()->id(),
        ], $id);
    }
}
