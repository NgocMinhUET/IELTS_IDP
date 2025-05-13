<?php

namespace App\Services\API;

use App\Enum\Models\ExamSessionStatus;
use App\Models\ExamSession;
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

    public function validateExamSessionFromToken($encryptedToken)
    {
        $examSessionId = $this->decryptExamSessionId($encryptedToken);

        $userId = auth()->id();

        $examSession = $this->examSessionRepository->findWhere([
            'id' => $examSessionId,
            'user_id' => $userId,
            'status' => ['status', '<>', ExamSessionStatus::ENDED]
        ])->first();

        if (!$examSession) {
            throw new HttpException('403', 'You are not allowed to access this exam session');
        }

        return $examSession;
    }

    public function decryptExamSession($encryptedToken)
    {
        return ExamSession::decryptToken($encryptedToken);
    }
}
