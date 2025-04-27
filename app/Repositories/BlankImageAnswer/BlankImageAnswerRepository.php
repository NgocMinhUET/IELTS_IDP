<?php

namespace App\Repositories\BlankImageAnswer;

use App\Models\BlankImageAnswer;
use App\Repositories\BaseRepository;

class BlankImageAnswerRepository extends BaseRepository implements BlankImageAnswerInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return BlankImageAnswer::class;
    }
}
