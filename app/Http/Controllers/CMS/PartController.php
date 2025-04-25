<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Services\CMS\PartService;
use App\Services\CMS\QuestionService;
use Illuminate\Http\Request;

class PartController extends Controller
{
    public function __construct(
        public PartService $partService,
        public QuestionService $questionService,
    ) {
    }

    public function detail($id)
    {
        $part = $this->partService->getPart($id);

        $choiceQuestions = $this->questionService->getChoiceQuestionByPart($id);

        return view('parts.detail', [
            'part' => $part,
            'choiceQuestions' => $choiceQuestions,
        ]);
    }

    public function update($id, Request $request)
    {
        dd($request->all(), $id);
    }
}