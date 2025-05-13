<?php

namespace App\Repositories\SkillAnswer;

use App\Models\SkillAnswer;
use App\Repositories\BaseRepository;

class SkillAnswerRepository extends BaseRepository implements SkillAnswerInterface
{
    public function model(): string
    {
        return SkillAnswer::class;
    }
}