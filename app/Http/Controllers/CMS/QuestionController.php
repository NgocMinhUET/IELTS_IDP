<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\Question\StoreQuestionRequest;
use App\Services\CMS\QuestionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function __construct(
        public QuestionService $questionService,
    ) {
    }

    public function create($partId)
    {
        return view('questions.choices.create', [
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