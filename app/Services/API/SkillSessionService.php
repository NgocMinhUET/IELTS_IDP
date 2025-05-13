<?php

namespace App\Services\API;

use App\Enum\Models\SkillSessionStatus;
use App\Models\Skill;
use App\Models\SkillSession;
use App\Repositories\SkillSession\SkillSessionInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SkillSessionService
{
    public function __construct(
        public SkillSessionInterface $skillSessionRepository,
    ) {}

    public function makeSkillSessionService($examSessionId, Skill $skill)
    {
        return $this->skillSessionRepository->firstOrCreateSkillSession(
            [
                'exam_session_id' => $examSessionId,
                'skill_id' => $skill->id,
            ],
            [
                'expired_at' => now()->addSeconds(($skill->duration ?? 0) + ($skill->bonus_time ?? 0) + config('const.token_expiration_bonus')),
                'submit_expired_at' => now()->addSeconds(($skill->duration ?? 0) + ($skill->bonus_time ?? 0)),
            ]
        );
    }

    public function getSkillSessionFromToken($token)
    {
        $sessionId = SkillSession::decryptTokenId($token);

        if (!$sessionId) {
            throw new HttpException(400, 'Invalid skill session token');
        }

        return $this->skillSessionRepository->find($sessionId);
    }

    public function validateSkillSessionToken($token)
    {
        $session = $this->getSkillSessionFromToken($token);

        if ($session->expired_at < now() || $session->status == SkillSessionStatus::SUBMITTED) {
            throw new HttpException(400,'Token expired');
        }

        return $session;
    }
}
