<?php

namespace App\Http\Controllers\CMS;

use App\Enum\Models\SkillType;
use App\Services\CMS\BlankContentQuestionService;
use App\Services\CMS\BlankImageQuestionService;
use App\Services\CMS\PartService;
use App\Services\CMS\QuestionService;
use Illuminate\Http\Request;

class PartController extends CMSController
{
    public function __construct(
        public PartService                 $partService,
        public QuestionService             $questionService,
        public BlankContentQuestionService $fillInBlankQuestionService,
        public BlankImageQuestionService $blankImageQuestionService,
    ) {
    }

    public function detail($id)
    {
        $part = $this->partService->getPart($id);

        $this->breadcrumbs = [
            'Exam' => null,
            'Skill' => route('admin.skills.detail', $part->skill_id),
            'Part' => null,
            'Detail' => null,
        ];

        $questionOrders = $this->partService->getAllQuestionOrdersOfPart($id);

        $choiceQuestions = $this->questionService->getChoiceQuestionByPart($id);
        $sortMapChoiceQuestions = $choiceQuestions->mapWithKeys(function ($item) {
            return [$item->getTable() . '_' . $item->id => $item];
        })->all();

        $fillInBlankQuestions = $this->fillInBlankQuestionService->getFillInBlankContentQuestionByPart($id);
        $sortFillInBlankQuestions = $fillInBlankQuestions->mapWithKeys(function ($item) {
            return [$item->getTable() . '_' . $item->id => $item];
        })->all();

        $fillInImageQuestions = $this->blankImageQuestionService->getFillInImageQuestionByPart($id);
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

        return view('parts.detail', [
            'part' => $part,
            'allQuestions' => $allQuestions,
            'paragraph' => $part->skill->type === SkillType::READING ? $part->paragraph : null,
        ]);
    }

    public function update($id, Request $request)
    {
        dd($request->all(), $id);
    }
}