<?php

namespace App\Enum\Models;

enum AnswerResult: int
{
    case UNANSWERED = 0;
    case PENDING = 1;
    case CORRECT = 2;
    case INCORRECT = 3;
    case GRADED = 4;
}
