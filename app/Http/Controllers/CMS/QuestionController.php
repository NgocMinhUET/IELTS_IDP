<?php

namespace App\Http\Controllers\CMS;

use App\Enum\QuestionType;
use App\Http\Requests\Question\CreateQuestionRequest;
use App\Http\Requests\Question\StoreQuestionRequest;
use App\Services\CMS\QuestionService;
use Illuminate\Support\Facades\DB;

class QuestionController extends CMSController
{
    public function __construct(
        public QuestionService $questionService,
    ) {
    }

    public function create($partId, CreateQuestionRequest $request)
    {
        $this->breadcrumbs = [
            'Exam' => null,
            'Skill' => null,
            'Part' => route('admin.parts.detail', $partId),
            'Question' => null,
            'Create' => null,
        ];

        $type = QuestionType::fromValue($request->input('type'));

        if (!$type) abort(404);


        return view($type->view(), [
            'partId' => $partId,
        ]);
    }

    public function store($partId, StoreQuestionRequest $request)
    {
        DB::beginTransaction();
        try {
            $choiceQ = $this->questionService->storeChoiceQuestionFromPart($partId, $request->only(['title']));

            $this->questionService->storeSubQuestionFromPart($choiceQ->id, $request->choice_sub_questions);

            DB::commit();

            return redirect()->route('admin.parts.detail', $partId)
                ->with('success', 'Create question success');
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    public function detail($id, $questionId)
    {
        $choiceQuestions = $this->questionService->getChoiceQuestionById($questionId);

        return view('questions.choices.detail', [
            'question' => $choiceQuestions,
        ]);
    }
}