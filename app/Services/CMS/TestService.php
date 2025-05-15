<?php

namespace App\Services\CMS;

use App\Repositories\Exam\ExamInterface;
use App\Repositories\Test\TestInterface;
use App\Repositories\User\UserInterface;
use App\Services\BaseService;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TestService extends BaseService
{
    public function __construct(
        public TestInterface $testRepository,
        public ExamInterface $examRepository,
        public UserInterface $userRepository,
    ) {}

    public function getPaginateTests()
    {
        return $this->testRepository->getPaginateTests();
    }

    public function getTest($id)
    {
        return $this->testRepository->with(['exam', 'exams', 'users'])->find($id);
    }

    public function storeTest($payload)
    {
        $examIds = $payload['exams'];
        $userIds = $payload['students'];
        $payload['created_by'] = auth()->id();

        $test = $this->testRepository->create($payload);

        $this->syncExamToTest($test->id, $examIds);
        $this->syncUserToTest($test->id, $userIds);

        return $test;
    }

    public function updateTest($id, $payload)
    {
        $examIds = $payload['exams'];
        $userIds = $payload['students'];

        $this->syncExamToTest($id, $examIds);
        $this->syncUserToTest($id, $userIds);

        return $this->testRepository->update($payload, $id);
    }

    public function syncExamToTest($testId, $examIds)
    {
        $this->validateExamIds($examIds);

        return $this->testRepository->sync($testId, 'exams', $examIds);

    }

    public function syncUserToTest($testId, $userIds)
    {
        $this->validateUserIds($userIds);

        return $this->testRepository->sync($testId, 'users', $userIds);
    }

    public function validateExamIds($examIds): void
    {
        if (count($examIds) != $this->examRepository->countApprovedExamByIds($examIds)) {
            throw new BadRequestHttpException('Invalid exam ids');
        }
    }

    public function validateUserIds($userIds): void
    {
        if (count($userIds) != $this->userRepository->countActiveUserByIds($userIds)) {
            throw new BadRequestHttpException('Invalid user ids');
        }
    }

    public function updateApproveStatus($id, $status)
    {
        return $this->testRepository->update(['approve_status' => $status], $id);
    }
}
