<?php

namespace App\Services\API;

use App\Enum\Models\ExamSessionStatus;
use App\Models\ExamSession;
use App\Models\Skill;
use App\Repositories\ExamSession\ExamSessionInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExamSessionService
{
    public function __construct(
        public ExamSessionInterface $examSessionRepository,
    ) {}

    public function decryptExamSessionId($encryptedToken): int
    {
        $examSessionId = ExamSession::decryptTokenId($encryptedToken);

        if (!$examSessionId) {
            throw new HttpException('400', 'Invalid token');
        }

        return $examSessionId;
    }

    public function validateExamSessionFromToken($encryptedToken, $includeStatus = true)
    {
        $examSessionId = $this->decryptExamSessionId($encryptedToken);

        $userId = auth()->id();

        $findConditions = [
            'id' => $examSessionId,
            'user_id' => $userId,
        ];

        if ($includeStatus) {
            $findConditions['status'] = ['status', 'NOTIN', [
                ExamSessionStatus::IN_USE,
                ExamSessionStatus::COMPLETE,
                ExamSessionStatus::IN_COMPLETE,
            ]];
        }

        $examSession = $this->examSessionRepository->findWhere($findConditions)->first();

        if (!$examSession) {
            throw new HttpException('400', 'Invalid token');
        }

        return $examSession;
    }

    public function decryptExamSession($encryptedToken)
    {
        return ExamSession::decryptToken($encryptedToken);
    }

    public function setExamSessionStatusAndExpiredAfterGetSkillQuestion($examSessionId, Skill $skill)
    {
        $afterSeconds = ($skill->duration ?? 0) + ($skill->bonus_time ?? 0) + config('const.token_expiration_bonus');


        return $this->examSessionRepository->update([
            'expired_at' => now()->addSeconds($afterSeconds),
            'status' => ExamSessionStatus::IN_USE,
        ], $examSessionId);
    }

    public function getExamSessionFromId($id)
    {
        return $this->examSessionRepository->find($id);
    }


    // when last skill submits, update exam session status to complete
    public function updateExamSessionStatusAfterLastSkillSubmit(ExamSession $examSession): bool
    {
        $examSession->update([
            'status' => ExamSessionStatus::COMPLETE,
        ]);
    }

    public function updateExamSessionStatusAfterSkillSubmit(ExamSession $examSession): bool
    {
        return $examSession->update([
            'status' => ExamSessionStatus::SKILL_SUBMITTED,
        ]);
    }

    public function getListExamSessionOfHistoryTest($testId)
    {
        $userId = auth()->id();

        return $this->examSessionRepository->with('exam')
            ->orderBy('id', 'DESC')
            ->findWhere([
                'test_id' => $testId,
                'user_id' => $userId,
                'status' => ['status', 'IN', [ExamSessionStatus::COMPLETE, ExamSessionStatus::IN_COMPLETE]]
            ]);
    }

    public function getExamSessionOfHistoryTest($examSessionId, $testId)
    {
        $userId = auth()->id();

        return $this->examSessionRepository->with('exam')
            ->findWhere([
                'id' => $examSessionId,
                'test_id' => $testId,
                'user_id' => $userId,
                'status' => ['status', 'IN', [ExamSessionStatus::COMPLETE, ExamSessionStatus::IN_COMPLETE]]
            ])->first();
    }
}
