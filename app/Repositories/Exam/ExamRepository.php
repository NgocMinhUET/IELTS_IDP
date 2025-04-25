<?php

namespace App\Repositories\Exam;

use App\Models\Exam;
use App\Repositories\BaseRepository;

/**
 * Class ExamRepository.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class ExamRepository extends BaseRepository implements ExamInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Exam::class;
    }
}
