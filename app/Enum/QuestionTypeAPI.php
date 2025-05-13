<?php

namespace App\Enum;

use App\Enum\Traits\HasValue;

enum QuestionTypeAPI: int
{
    use HasValue;
    case FILL_CONTENT = 1;
    case DRAG_DROP_CONTENT = 2;
    case FILL_IMAGE = 5;
    case DRAG_DROP_IMAGE = 3;
    case CHOICE = 4;
    case WRITING = 6;
}
