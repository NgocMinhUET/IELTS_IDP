<?php

namespace App\Http\Controllers\CMS;

use App\Enum\AnswerType;
use App\Http\Requests\Question\StoreFICQuestionRequest;
use App\Services\CMS\BlankContentQuestionService;
use Illuminate\Support\Facades\DB;

class FillInContentQuestionController extends CMSController
{
    public function __construct(
        public BlankContentQuestionService $blankContentQuestionService,
    ) {
    }

    public function store($partId, StoreFICQuestionRequest $request)
    {
        DB::beginTransaction();
        try {
            $content = $request->input('content');
            $answers = $request->input('answers');
            $placeholders = $request->input('placeholders');

            $dom = $this->blankContentQuestionService->validateContentAndAnswer($content, $answers, $placeholders);
            [$newContent, $newAnswers, $newPlaceholders] = $this->blankContentQuestionService
                ->replaceBlankInputId($dom, $answers, $placeholders);
            $request->merge(['content' => $newContent]);

            $question = $this->blankContentQuestionService->storeFillInBlankContentQuestion(
                $partId,
                $request->only('title', 'content', 'answer_type', 'answer_label')
            );
            $questionId = $question->id;

            $this->blankContentQuestionService->storeFillInBlankContentAnswers($questionId, $newAnswers, $newPlaceholders);

            if ($request->input('answer_type') == AnswerType::DRAG_DROP->value) {
                $distractorAnswers = $request->input('distractor_answers');
                if (!empty($distractorAnswers)) {
                    $this->blankContentQuestionService->storeFillInBlankContentDistractorAnswers($questionId, $distractorAnswers);
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