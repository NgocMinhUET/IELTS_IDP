<?php

namespace App\Repositories\Test;

use App\Models\Test;
use App\Repositories\BaseRepository;

class TestRepository extends BaseRepository implements TestInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Test::class;
    }
}
