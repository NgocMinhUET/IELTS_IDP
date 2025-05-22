<?php

namespace App\Enum\Models;

enum SkillSessionStatus: int
{
    case IN_PROGRESS = 1;
    case SUBMITTED = 2;

    public function label(): string
    {
        return match($this) {
            self::IN_PROGRESS => 'InProgress',
            self::SUBMITTED => 'Submitted',
        };
    }
}
