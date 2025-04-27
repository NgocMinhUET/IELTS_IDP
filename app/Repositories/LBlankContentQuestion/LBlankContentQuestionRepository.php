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
}
