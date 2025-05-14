<?php

namespace App\Http\Controllers\CMS;

use App\Enum\AnswerType;
use App\Http\Requests\Question\StoreFIIQuestionRequest;
use App\Services\CMS\BlankImageQuestionService;
use Illuminate\Support\Facades\DB;

class FillInImageQuestionController extends CMSController
{
    public function __construct(
        public BlankImageQuestionService $blankImageQuestionService,
    ) {
    }

    public function store($partId, StoreFIIQuestionRequest $request)
    {
        DB::beginTransaction();
        try {
            // Upload image
            $imageLink = $this->blankImageQuestionService->uploadQuestionImage($request->file('image'));

            $request->merge([
                'link' => $imageLink,
            ]);

            $question = $this->blankImageQuestionService->storeFillInBlankImageQuestion(
                $partId,
                $request->only('title', 'link', 'answer_type', 'answer_label', 'width', 'height')
            );
            $questionId = $question->id;

            $this->blankImageQuestionService->storeFillInBlankImageAnswers($questionId, $request->input('answers'));

            if ($request->input('answer_type') == AnswerType::DRAG_DROP->value) {
                $distractorAnswers = $request->input('distractor_answers');
                if (!empty($distractorAnswers)) {
                    $this->blankImageQuestionService->storeFillInBlankImageDistractorAnswers($questionId, $distractorAnswers);
                }
            }

            DB::commit();

            return redirect()->route('admin.parts.detail', $partId)
                ->with('success', 'Create question success');
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
        }
    }
}