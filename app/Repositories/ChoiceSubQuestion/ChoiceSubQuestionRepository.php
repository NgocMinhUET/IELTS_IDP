<?php

namespace App\Repositories\ChoiceSubQuestion;

use App\Models\ChoiceSubQuestion;
use App\Repositories\BaseRepository;

/**
 * Class ExamRepository.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class ChoiceSubQuestionRepository extends BaseRepository implements ChoiceSubQuestionInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return ChoiceSubQuestion::class;
    }
}
