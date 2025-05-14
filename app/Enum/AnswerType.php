<?php

namespace App\Enum;

use App\Enum\Traits\HasValue;

enum AnswerType: int
{
    use HasValue;
    case FILL = 1;
    case DRAG_DROP = 2;
}
