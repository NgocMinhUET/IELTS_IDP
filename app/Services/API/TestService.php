<?php

namespace App\Services\API;

use App\Enum\Models\ExamSessionStatus;
use App\Models\Test;
use App\Repositories\Exam\ExamInterface;
use App\Repositories\ExamSession\ExamSessionInterface;
use App\Repositories\Skill\SkillInterface;
use App\Repositories\Test\TestInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TestService
{
    public function __construct(
        public TestInterface $testRepository,
        public ExamInterface $examRepository,
        public SkillInterface $skillRepository,
        public ExamSessionInterface $examSessionRepository,
    ) {}

    public function enrollTest($id): array
    {
        $userId = auth()->id();
        $test = $this->validateTest($id, $userId);

        $exam = $this->getRandomExamOfTest($test, $userId);

        $examSession = $this->examSessionRepository->create([
            'test_id' => $test->id,
            'user_id' => $userId,
            'exam_id' => $exam->id,
            'status' => ExamSessionStatus::ISSUE
        ]);
        $examSessionToken = $examSession->generateEncryptedToken();

        $skills = $this->skillRepository->findByField('exam_id', $exam->id);

        return [
            'exam_session_token' => $examSessionToken,
            'desc' => $test->desc ?? '',
            'start_time' => $test->start_time,
            'end_time' => $test->end_time,
            'exam' => [
                'title' => $exam->title,
                'desc' => $exam->desc ?? '',
                'id' => $exam->id,
                'skills' => $skills->map(function ($skill) {
                    return [
                        'id' => $skill->id,
                        'code' => $skill->code,
                        'type' => $skill->type->value,
                        'desc' => $skill->desc,
                        'duration' => $skill->duration,
                        'bonus_time' => $skill->bonus_time,
                    ];
                })
            ]
        ];
    }

    public function validateTest($id, $userId)
    {
        $test = $this->testRepository->getAssignedToUserTest($id, $userId);

        if (!$test) {
            throw new HttpException(403, 'You are not assigned to this test.');
        }

        return $test;
    }

    public function getRandomExamOfTest(Test $test, $userId)
    {
        // exam ids belong to this test
        $testExamIds = $test->exams()->pluck('id')->toArray();
        if (empty($testExamIds)) {
            throw new HttpException(409, 'Test has no exam');
        }

        $issuedExamIds = $this->examSessionRepository->getIssuedExamIds($test->id, $userId)
            ->pluck('exam_id')->toArray();

        $availableExamIds = array_diff($testExamIds, $issuedExamIds);

        if (empty($availableExamIds)) {
            $availableExamIds = $testExamIds;
        }

        return $this->examRepository->find($availableExamIds[array_rand($availableExamIds)]);
    }

    public function getAssignedToUserTests()
    {
        $userId = auth()->id();

        return $this->testRepository->getAssignedToUserTests($userId);
    }

    public function getTestHistories()
    {
        $userId = auth()->id();

        return $this->testRepository->getTestHistories($userId);
    }

    public function buildDetailTestHistoryResponse($examSessions)
    {
        return $examSessions->map(function ($examSession) {
            $exam = $examSession->exam;

            return [
                'id' => $examSession->id,
                'lastest_submit_at' => $examSession->updated_at->format('Y-m-d H:i:s'),
                'status' => $examSession->status,
                'exam_id' => $exam->id,
                'exam_title' => $exam->title,
                'exam_desc' => $exam->desc ?? '',
            ];
        });
    }
}
