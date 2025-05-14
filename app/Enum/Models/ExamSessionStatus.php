<?php

namespace App\Enum\Models;

enum ExamSessionStatus: int
{
    case ISSUE = 1;
    case IN_USE = 2; // after get skill question
    case END = 3; // after one of skill answers submit
    case CLOSE = 4; // end of test session
}
