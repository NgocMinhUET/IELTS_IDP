<?php

namespace App\Enum\Models;

enum ExamSessionStatus: int
{
    case ISSUE = 1;
    case IN_USE = 2;
    case ENDED = 3;
}
