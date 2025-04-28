<?php

namespace App\Repositories\QuestionOrder;

use App\Models\QuestionOrder;
use App\Repositories\BaseRepository;

class QuestionOrderRepository extends BaseRepository implements QuestionOrderInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return QuestionOrder::class;
    }
}
