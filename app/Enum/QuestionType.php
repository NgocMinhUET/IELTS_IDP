<?php

namespace App\Enum;

use App\Enum\Traits\HasValue;

enum QuestionType: string
{
    use HasValue;
    case FILL_IN_CONTENT = 'fill_in_content';
    case DRAG_DROP_IN_CONTENT = 'drag_drop_content';
    case CHOICE = 'choice';
    case FILL_IN_IMAGE = 'fill_in_image';
    case DRAG_DROP_IMAGE = 'drag_drop_image';
    case SPEAKING = 'speaking';
    case WRITING = 'writing';

    public function view(): string
    {
        return match ($this) {
            self::FILL_IN_CONTENT => 'questions.fill_in_content.create',
            self::DRAG_DROP_IN_CONTENT => 'questions.drag_drop_in_content.create',
            self::CHOICE => 'questions.choice.create',
            self::FILL_IN_IMAGE => 'questions.fill_in_image.create',
            self::DRAG_DROP_IMAGE => 'questions.drag_drop_in_image.create',
            self::WRITING => 'questions.writing.create',
        };
    }
}
