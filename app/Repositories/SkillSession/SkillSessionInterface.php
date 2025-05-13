<?php

namespace App\Repositories\SkillSession;

use App\Repositories\BaseInterface;

interface SkillSessionInterface extends BaseInterface
{
    public function firstOrCreateSkillSession($uniquePair, $attributes);
}
