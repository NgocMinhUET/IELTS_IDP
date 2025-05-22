<?php

namespace App\Enum\Models;

enum ExamSessionStatus: int
{
    case ISSUE = 1;
    case IN_USE = 2; // after get skill question
    case SKILL_SUBMITTED = 3; // after one of skill answers submit

    case COMPLETE = 4; // end of test session
    case IN_COMPLETE =  5;

    public function label(): string
    {
        return match($this) {
            self::ISSUE, self::SKILL_SUBMITTED, self::IN_USE => 'InProgress',
            self::COMPLETE => 'Complete',
            self::IN_COMPLETE => 'InComplete',
        };
    }
}
