<?php

namespace App\Repositories\Part;

use App\Models\Part;
use App\Repositories\BaseRepository;

class PartRepository extends BaseRepository implements PartInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Part::class;
    }
}
