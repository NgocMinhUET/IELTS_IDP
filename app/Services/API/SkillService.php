<?php

namespace App\Services\API;

use App\Repositories\Skill\SkillInterface;

class SkillService
{
    public function __construct(
        public SkillInterface $skillRepository,
    ) {}

    public function getSkill($id)
    {
        return $this->skillRepository->find($id);
    }
}
