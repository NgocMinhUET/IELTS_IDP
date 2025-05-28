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

        return [
            'exam_session_token' => $examSessionToken,
            'desc' => $test->desc ?? '',
            'start_time' => $test->start_time,
            'end_time' => $test->end_time,
            'exam' => $this->buildExamResponse($exam)
        ];
    }

    public function buildExamResponse($exam, $skillSessions = null): array
    {
        $skills = $this->skillRepository->findByField('exam_id', $exam->id);

        return [
            'title' => $exam->title,
            'desc' => $exam->desc ?? '',
            'id' => $exam->id,
            'skills' => $skills->map(function ($skill) use ($skillSessions) {
                $baseResponse = [
                    'id' => $skill->id,
                    'code' => $skill->code,
                    'type' => $skill->type->value,
                    'desc' => $skill->desc,
                    'duration' => $skill->duration,
                    'bonus_time' => $skill->bonus_time
                ];

                // for history
                if (!is_null($skillSessions)) {
                    $skillSession = $skillSessions->where('skill_id', $skill->id)->first();
                    $baseResponse['total_question'] = $skillSession->total_question ?? 0;
                    $baseResponse['total_submitted_answer'] = $skillSession->total_submitted_answer ?? 0;
                    $baseResponse['total_correct_answer'] = $skillSession->total_correct_answer ?? 0;
                    $baseResponse['total_pending_answer'] = $skillSession->total_pending_answer ?? 0;
                    $baseResponse['total_score'] = $skillSession->total_score ?? 0;
                    $baseResponse['total_correct_score'] = $skillSession->total_correct_score ?? 0;
                    $baseResponse['start_at'] = $skillSession->created_at ? $skillSession->created_at->format( 'Y-m-d H:i:s') : '';
                    $baseResponse['submit_at'] = $skillSession->updated_at ? $skillSession->updated_at->format( 'Y-m-d H:i:s') : '';
                }

                return $baseResponse;
            })
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
                'last_submit_at' => $examSession->updated_at->format('Y-m-d H:i:s'),
                'status' => $examSession->status,
                'exam_id' => $exam->id,
                'exam_title' => $exam->title,
                'exam_desc' => $exam->desc ?? '',
            ];
        });
    }

    public function buildAnswersResponse($skillAnswers)
    {
        return $skillAnswers->map(function ($skillAnswer) {
            return [
                'question_id' => $skillAnswer->question_id,
                'answer' => $skillAnswer->answer,
                'answer_result' => $skillAnswer->answer_result,
                'question_type' => $skillAnswer->question_type,
                'score' => $skillAnswer->score,
            ];
        });
    }
}
