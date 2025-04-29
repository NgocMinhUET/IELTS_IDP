<?php

namespace App\Repositories\WritingQuestion;

use App\Models\WritingQuestion;
use App\Repositories\BaseRepository;

class WritingQuestionRepository extends BaseRepository implements WritingQuestionInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return WritingQuestion::class;
    }
}
