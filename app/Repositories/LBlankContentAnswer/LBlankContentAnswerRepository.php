<?php

namespace App\Repositories\LBlankContentAnswer;

use App\Models\LBlankContentAnswer;
use App\Repositories\BaseRepository;

class LBlankContentAnswerRepository extends BaseRepository implements LBlankContentAnswerInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return LBlankContentAnswer::class;
    }
}
