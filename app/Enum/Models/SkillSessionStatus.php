<?php

namespace App\Enum\Models;

enum SkillSessionStatus: int
{
    case IN_PROGRESS = 1;
    case SUBMITTED = 2;
}
