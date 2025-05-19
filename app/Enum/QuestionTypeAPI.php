<?php

namespace App\Enum;

use App\Enum\Traits\HasValue;
use App\Models\BlankImageAnswer;
use App\Models\BlankImageQuestion;
use App\Models\ChoiceOptions;
use App\Models\ChoiceSubQuestion;
use App\Models\LBlankContentAnswer;
use App\Models\LBlankContentQuestion;
use App\Models\WritingQuestion;

enum QuestionTypeAPI: int
{
    use HasValue;
    case FILL_CONTENT = 1;
    case DRAG_DROP_CONTENT = 2;
    case FILL_IMAGE = 5;
    case DRAG_DROP_IMAGE = 3;
    case CHOICE = 4;
    case WRITING = 6;

    public function toQuestionModel()
    {
        $modelClass = match ($this) {
            self::FILL_CONTENT, self::DRAG_DROP_CONTENT => LBlankContentQuestion::class,
            self::FILL_IMAGE, self::DRAG_DROP_IMAGE => BlankImageQuestion::class,
            self::CHOICE => ChoiceSubQuestion::class,
            self::WRITING => WritingQuestion::class,
        };

        return new $modelClass;
    }

    public static function fromValueToQuestionModel(int $value): \Illuminate\Database\Eloquent\Model
    {
        return self::from($value)->toQuestionModel();
    }

    public function toAnswerModel()
    {
        $modelClass = match ($this) {
            self::DRAG_DROP_CONTENT => LBlankContentAnswer::class,
            self::DRAG_DROP_IMAGE => BlankImageAnswer::class,
            self::CHOICE => ChoiceOptions::class,
        };

        return new $modelClass;
    }

    public static function fromValueToAnswerModel(int $value): \Illuminate\Database\Eloquent\Model
    {
        return self::from($value)->toAnswerModel();
    }

    public static function getHasInputIdentifyQuestionType(): array
    {
        return [
            self::FILL_CONTENT->value,
            self::DRAG_DROP_CONTENT->value,
            self::FILL_IMAGE->value,
            self::DRAG_DROP_IMAGE->value,
        ];
    }
}
