<?php

namespace App\Repositories\ChoiceOption;

use App\Models\ChoiceOptions;
use App\Repositories\BaseRepository;

/**
 * Class ExamRepository.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class ChoiceOptionRepository extends BaseRepository implements ChoiceOptionInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return ChoiceOptions::class;
    }
}
