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

        $choiceQuestions = $this->questionService->getChoiceQuestionByPart($id);

        $fillInBlankQuestions = $this->fillInBlankQuestionService->getFillInBlankContentQuestionByPart($id);

        $fillInImageQuestions = $this->blankImageQuestionService->getFillInImageQuestionByPart($id);

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