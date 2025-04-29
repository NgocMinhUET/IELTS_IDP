<?php

namespace App\Repositories\QuestionOrder;

use App\Enum\Models\SkillType;
use App\Repositories\BaseInterface;

interface QuestionOrderInterface extends BaseInterface
{
    public function getAllQuestionOrdersOfPart($partId, SkillType $skillType);
}
