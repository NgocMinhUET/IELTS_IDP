<?php

namespace App\Services\CMS;

use App\Repositories\SkillSession\SkillSessionInterface;
use App\Services\BaseService;

class SkillSessionService extends BaseService
{
    public function __construct(
        public SkillSessionInterface $skillSessionRepository,
    ) {
    }

    public function getSkillSession($id)
    {
        return $this->skillSessionRepository->with(['examSession', 'skillAnswers'])->find($id);
    }

    public function updateSkillSessionAfterChangeScore($id, $scoreDiff, $isPendingAnswer)
    {
        return $this->skillSessionRepository->updateSkillSessionAfterChangeScore($id, $scoreDiff, $isPendingAnswer);
    }
}