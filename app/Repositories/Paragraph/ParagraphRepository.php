<?php

namespace App\Repositories\Paragraph;

use App\Models\Paragraph;
use App\Repositories\BaseRepository;

class ParagraphRepository extends BaseRepository implements ParagraphInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Paragraph::class;
    }
}
