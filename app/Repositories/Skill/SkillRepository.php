<?php

namespace App\Repositories\Skill;

use App\Models\Skill;
use App\Repositories\BaseRepository;

class SkillRepository extends BaseRepository implements SkillInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Skill::class;
    }
}
