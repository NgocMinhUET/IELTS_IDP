<?php

namespace App\Http\Controllers\CMS;

use App\Enum\Models\SkillType;
use App\Http\Requests\Part\UpdatePartRequest;
use App\Services\CMS\BlankContentQuestionService;
use App\Services\CMS\BlankImageQuestionService;
use App\Services\CMS\PartService;
use App\Services\CMS\QuestionService;
use App\Services\CMS\WritingQuestionService;

class PartController extends CMSController
{
    public function __construct(
        public PartService                 $partService,
        public QuestionService             $questionService,
        public BlankContentQuestionService $fillInBlankQuestionService,
        public BlankImageQuestionService $blankImageQuestionService,
        public WritingQuestionService $writingQuestionService,
    ) {
    }

    public function detail($id)
    {
        $part = $this->partService->getPart($id);
        $skillType = $part->skill->type;

        $this->breadcrumbs = [
            'Exam' => null,
            'Skill' => route('admin.skills.detail', $part->skill_id),
            'Part' => null,
            'Detail' => null,
        ];

        $allQuestions = $this->getAllOrderedQuestionsOfPart($id, $skillType);

        return view('parts.detail', [
            'part' => $part,
            'allQuestions' => $allQuestions,
            'paragraph' => $part->skill->type === SkillType::READING ? $part->paragraph : null,
        ]);
    }

    public function getAllOrderedQuestionsOfPart($partId, $skillType): array
    {
        $questionOrders = $this->partService->getAllQuestionOrdersOfPart($partId, $skillType);

        if ($skillType == SkillType::WRITING) {
            return $this->getAllOrderedWritingQuestionsOfPart($questionOrders, $partId);
        } else {
            return $this->getAllOrderedOtherQuestionsOfPart($questionOrders, $partId);
        }
    }

    public function getAllOrderedWritingQuestionsOfPart($questionOrders, $partId): array
    {
        $writingQuestions = $this->writingQuestionService->getWritingQuestionByPart($partId);
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
        $choiceQuestions = $this->questionService->getChoiceQuestionByPart($partId);
        $sortMapChoiceQuestions = $choiceQuestions->mapWithKeys(function ($item) {
            return [$item->getTable() . '_' . $item->id => $item];
        })->all();

        $fillInBlankQuestions = $this->fillInBlankQuestionService->getFillInBlankContentQuestionByPart($partId);
        $sortFillInBlankQuestions = $fillInBlankQuestions->mapWithKeys(function ($item) {
            return [$item->getTable() . '_' . $item->id => $item];
        })->all();

        $fillInImageQuestions = $this->blankImageQuestionService->getFillInImageQuestionByPart($partId);
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

    public function update($id, UpdatePartRequest $request)
    {
        $this->partService->updatePartDesc($id, $request->desc);

        return redirect()->back()->with([
            'success' => 'Update part desc success',
        ]);
    }
}