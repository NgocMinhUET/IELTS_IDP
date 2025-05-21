<?php

namespace App\Http\Controllers\CMS;

use App\Enum\AnswerType;
use App\Enum\Models\SkillType;
use App\Http\Requests\Question\StoreFICQuestionRequest;
use App\Services\CMS\BlankContentQuestionService;
use App\Services\CMS\PartService;
use Illuminate\Support\Facades\DB;

class FillInContentQuestionController extends CMSController
{
    public function __construct(
        public BlankContentQuestionService $blankContentQuestionService,
        public PartService $partService,
    ) {
    }

    public function store($partId, StoreFICQuestionRequest $request)
    {
        DB::beginTransaction();
        try {
            $part = $this->partService->getPart($partId);

            $content = $request->input('content');
            $answers = $request->input('answers');
            $score = $request->input('score');
            $placeholders = $request->input('placeholders');

            $dom = $this->blankContentQuestionService->validateContentAndAnswer($content, $answers, $placeholders);
            [$newContent, $newAnswers, $newPlaceholders, $newScore] = $this->blankContentQuestionService
                ->replaceBlankInputId($dom, $answers, $placeholders, $score);
            $request->merge(['content' => $newContent]);

            $isContentInherit = $part->skill->type === SkillType::READING && $request->has('content_inherit');
            $request->merge(['content_inherit' => $isContentInherit]);

            if ($isContentInherit) {
                // store paragraphs
                $this->partService->updateOrCreateReadingParagraph($partId, $newContent);
            }

            $totalQuestionScore = array_sum($newScore);
            $questionAttributes = $request->only('title', 'content', 'content_inherit', 'answer_type', 'answer_label');
            $questionAttributes['score'] = $totalQuestionScore;

            $question = $this->blankContentQuestionService->storeFillInBlankContentQuestion($partId, $questionAttributes);
            $questionId = $question->id;

            $this->blankContentQuestionService->storeFillInBlankContentAnswers($questionId, $newAnswers, $newPlaceholders, $newScore);

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