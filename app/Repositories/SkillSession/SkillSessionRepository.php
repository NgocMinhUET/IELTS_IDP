<?php

namespace App\Repositories\SkillSession;

use App\Models\SkillSession;
use App\Repositories\BaseRepository;

class SkillSessionRepository extends BaseRepository implements SkillSessionInterface
{
    public function model(): string
    {
        return SkillSession::class;
    }

    public function firstOrCreateSkillSession($uniquePair, $attributes)
    {
        return $this->model->firstOrCreate($uniquePair, $attributes);
    }
}