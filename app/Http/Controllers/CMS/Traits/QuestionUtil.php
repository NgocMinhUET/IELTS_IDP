<?php

namespace App\Http\Controllers\CMS\Traits;

use App\Enum\Models\SkillType;
use App\Services\CMS\BlankContentQuestionService;
use App\Services\CMS\BlankImageQuestionService;
use App\Services\CMS\PartService;
use App\Services\CMS\QuestionService;
use App\Services\CMS\WritingQuestionService;

trait QuestionUtil
{
    public function getAllOrderedQuestionsOfPart($partId, $skillType): array
    {
        $partService = app()->make(PartService::class);
        if (!$partService instanceof PartService) {
            abort(500);
        }

        $questionOrders = $partService->getAllQuestionOrdersOfPart($partId, $skillType);

        if ($skillType == SkillType::WRITING) {
            return $this->getAllOrderedWritingQuestionsOfPart($questionOrders, $partId);
        } else {
            return $this->getAllOrderedOtherQuestionsOfPart($questionOrders, $partId);
        }
    }

    public function getAllOrderedWritingQuestionsOfPart($questionOrders, $partId): array
    {
        $writingQuestionService = app()->make(WritingQuestionService::class);
        if (!$writingQuestionService instanceof WritingQuestionService) {
            abort(500, 'Not implemented');
        }
        $writingQuestions = $writingQuestionService->getWritingQuestionByPart($partId);
        $sortMapWritingQuestions = $writingQuestions->mapWithKeys(function ($item) {
            return [$item->getTable() . '_' . $item->id => $item];
        })->all();

        $allQuestions = [];
        foreach ($questionOrders as $order) {
            if (isset($sortMapWritingQuestions[$order['table'] . '_' . $order['question_id']])) {
                $allQuestions[] = $sortMapWritingQuestions[$order['table'] . '_' . $order['question_id']];
            }
        }

        return $allQuestions;
    }

    public function getAllOrderedOtherQuestionsOfPart($questionOrders, $partId): array
    {
        $questionService = app()->make(QuestionService::class);
        $fillInBlankQuestionService = app()->make(BlankContentQuestionService::class);
        $blankImageQuestionService = app()->make(BlankImageQuestionService::class);

        if (!$blankImageQuestionService instanceof BlankImageQuestionService ||
            !$questionService instanceof QuestionService ||
            !$fillInBlankQuestionService instanceof BlankContentQuestionService
        ) {
            abort(500, 'Not implemented');
        }

        $choiceQuestions = $questionService->getChoiceQuestionByPart($partId);
        $sortMapChoiceQuestions = $choiceQuestions->mapWithKeys(function ($item) {
            return [$item->getTable() . '_' . $item->id => $item];
        })->all();

        $fillInBlankQuestions = $fillInBlankQuestionService->getFillInBlankContentQuestionByPart($partId);
        $sortFillInBlankQuestions = $fillInBlankQuestions->mapWithKeys(function ($item) {
            return [$item->getTable() . '_' . $item->id => $item];
        })->all();

        $fillInImageQuestions = $blankImageQuestionService->getFillInImageQuestionByPart($partId);
        $sortFillInImageQuestions = $fillInImageQuestions->mapWithKeys(function ($item) {
            return [$item->getTable() . '_' . $item->id => $item];
        })->all();

        $allMapQuestions = array_merge($sortMapChoiceQuestions, $sortFillInBlankQuestions, $sortFillInImageQuestions);

        $allQuestions = [];
        foreach ($questionOrders as $order) {
            if (isset($allMapQuestions[$order['table'] . '_' . $order['question_id']])) {
                $allQuestions[] = $allMapQuestions[$order['table'] . '_' . $order['question_id']];
            }
        }

        return $allQuestions;
    }
}
