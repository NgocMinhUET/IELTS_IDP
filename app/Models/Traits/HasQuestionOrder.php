<?php

namespace App\Models\Traits;

use App\Models\QuestionOrder;

trait HasQuestionOrder
{
    protected static function bootHasQuestionOrder(): void
    {
        static::created(function ($model) {
            QuestionOrder::create([
                'part_id' => $model->part_id,
                'table' => $model->getTable(),
                'question_id' => $model->id,
            ]);
        });
    }
}