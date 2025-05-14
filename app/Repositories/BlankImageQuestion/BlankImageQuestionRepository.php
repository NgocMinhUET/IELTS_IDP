<?php

namespace App\Repositories\BlankImageQuestion;

use App\Models\BlankImageQuestion;
use App\Repositories\BaseRepository;

class BlankImageQuestionRepository extends BaseRepository implements BlankImageQuestionInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return BlankImageQuestion::class;
    }
}
