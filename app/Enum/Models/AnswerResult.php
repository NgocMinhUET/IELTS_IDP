<?php

namespace App\Enum\Models;

enum AnswerResult: int
{
    case PENDING = 1;
    case CORRECT = 2;
    case INCORRECT = 3;
}
