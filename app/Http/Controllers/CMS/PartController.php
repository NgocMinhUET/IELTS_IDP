<?php

namespace App\Http\Controllers\CMS;

use App\Enum\Models\SkillType;
use App\Http\Controllers\CMS\Traits\QuestionUtil;
use App\Http\Requests\Part\UpdatePartRequest;
use App\Services\CMS\BlankContentQuestionService;
use App\Services\CMS\BlankImageQuestionService;
use App\Services\CMS\PartService;
use App\Services\CMS\QuestionService;
use App\Services\CMS\WritingQuestionService;

class PartController extends CMSController
{
    use QuestionUtil;
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

    public function update($id, UpdatePartRequest $request)
    {
        $this->partService->updatePartDesc($id, $request->desc);

        return redirect()->back()->with([
            'success' => 'Update part desc success',
        ]);
    }
}