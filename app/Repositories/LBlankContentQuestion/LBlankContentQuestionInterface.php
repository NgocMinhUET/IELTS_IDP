<?php

namespace App\Repositories\LBlankContentQuestion;

use App\Repositories\BaseInterface;

interface LBlankContentQuestionInterface extends BaseInterface
{
    public function unsetExistedContentInheritQuestion($partId);

    public function isHavingQuestionInherit($partId);
}
