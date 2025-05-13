<?php

namespace App\Services\API;

use App\Models\Skill;
use App\Repositories\SkillSession\SkillSessionInterface;

class SkillSessionService
{
    public function __construct(
        public SkillSessionInterface $skillSessionRepository,
    ) {}

    public function makeSkillSessionService($examSessionId, Skill $skill)
    {
        return $this->skillSessionRepository->create([
            'exam_session_id' => $examSessionId,
            'skill_id' => $skill->id,
            'expired_at' => now()->addSeconds(($skill->duration ?? 0) + ($skill->bonus_time ?? 0) + 300),
        ]);
    }
}
