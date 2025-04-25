<?php

namespace App\Enum\Models;

use App\Enum\Models\Traits\HasLabel;

/**
 * enum type of Skill model
 */
enum SkillType: int
{
    use HasLabel;
    case LISTENING = 1;
    case SPEAKING = 2;
    case READING = 3;
    case WRITING = 4;

    public function label(): string
    {
        return match($this) {
            self::LISTENING => 'Listening123',
            self::SPEAKING => 'Speaking',
            self::READING => 'Reading',
            self::WRITING => 'Writing',
        };
    }
}
