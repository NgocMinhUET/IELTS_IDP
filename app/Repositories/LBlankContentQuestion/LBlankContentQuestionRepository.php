<?php

namespace App\Repositories\LBlankContentQuestion;

use App\Models\LBlankContentQuestion;
use App\Repositories\BaseRepository;

class LBlankContentQuestionRepository extends BaseRepository implements LBlankContentQuestionInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return LBlankContentQuestion::class;
    }

    public function unsetExistedContentInheritQuestion($partId)
    {
        return $this->model->where('part_id', $partId)
            ->where('content_inherit', LBlankContentQuestion::IS_CONTENT_INHERIT)
            ->update(['content_inherit' => LBlankContentQuestion::IS_CONTENT_NOT_INHERIT]);
    }

    public function isHavingQuestionInherit($partId)
    {
        return $this->model->where('part_id', $partId)
            ->where('content_inherit', LBlankContentQuestion::IS_CONTENT_INHERIT)
            ->exists();
    }
}
