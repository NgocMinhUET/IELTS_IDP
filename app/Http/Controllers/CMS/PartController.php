<?php

namespace App\Http\Controllers\CMS;

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

        $sort = [
            [
                'child_table' => 'l_blank_content_questions',
                'child_id' => 1,
            ],
            [
                'child_table' => 'l_blank_content_questions',
                'child_id' => 2,
            ],
            [
                'child_table' => 'choice_questions',
                'child_id' => 10,
            ],
            [
                'child_table' => 'choice_questions',
                'child_id' => 12,
            ],
        ];

        $choiceQuestions = $this->questionService->getChoiceQuestionByPart($id);
        $sortMapChoiceQuestions = $choiceQuestions->mapWithKeys(function ($item) {
            return [$item->getTable() . '_' . $item->id => $item];
        })->all();

        $fillInBlankQuestions = $this->fillInBlankQuestionService->getFillInBlankContentQuestionByPart($id);
        $sortFillInBlankQuestions = $fillInBlankQuestions->mapWithKeys(function ($item) {
            return [$item->getTable() . '_' . $item->id => $item];
        })->all();

        $fillInImageQuestions = $this->blankImageQuestionService->getFillInImageQuestionByPart($id);
        $sortFillInImageQuestions = $fillInBlankQuestions->mapWithKeys(function ($item) {
            return [$item->getTable() . '_' . $item->id => $item];
        })->all();

        $allMapQuestions = array_merge($sortMapChoiceQuestions, $sortFillInBlankQuestions, $sortFillInImageQuestions);
        $allQuestions = [];
        foreach ($sort as $item) {
            if (isset($allMapQuestions[$item['child_table'] . '_' . $item['child_id']])) {
                $allQuestions[] = $allMapQuestions[$item['child_table'] . '_' . $item['child_id']];
            }
        }
        dd($allQuestions);

        return view('parts.detail', [
            'part' => $part,
            'choiceQuestions' => $choiceQuestions,
            'fillInBlankQuestions' => $fillInBlankQuestions,
            'fillInImageQuestions' => $fillInImageQuestions,
        ]);
    }

    public function update($id, Request $request)
    {
        dd($request->all(), $id);
    }
}