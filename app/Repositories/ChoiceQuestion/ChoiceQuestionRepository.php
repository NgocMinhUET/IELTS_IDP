<?php

namespace App\Repositories\ChoiceQuestion;

use App\Models\ChoiceQuestion;
use App\Repositories\BaseRepository;

/**
 * Class ExamRepository.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class ChoiceQuestionRepository extends BaseRepository implements ChoiceQuestionInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return ChoiceQuestion::class;
    }
}
