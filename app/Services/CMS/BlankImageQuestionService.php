<?php

namespace App\Services\CMS;

use App\Repositories\BlankImageAnswer\BlankImageAnswerInterface;
use App\Repositories\BlankImageQuestion\BlankImageQuestionInterface;
use App\Services\BaseService;

/**
 * Dạng câu hỏi điền/drag drop vào blank input trên image
 */
class BlankImageQuestionService extends BaseService
{
    public function __construct(
        public BlankImageQuestionInterface $blankImageQuestionRepository,
        public BlankImageAnswerInterface $blankImageAnswerRepository,
    ) {}

    public function uploadQuestionImage($image): string
    {
        $imagePath = $image->store('uploads', 'public');

        return asset('storage/' . $imagePath);
    }

    public function storeFillInBlankImageQuestion($partId, $questionPayload)
    {
        $questionPayload['part_id'] = $partId;

        return $this->blankImageQuestionRepository->create($questionPayload);
    }

    public function storeFillInBlankImageAnswers($questionId, $answerPayload)
    {
        $insertData = [];

        foreach ($answerPayload as $key => $answer) {
            $insertData[] = [
                'question_id' => $questionId,
                'input_identify' => $questionId . $key . uniqid(),
                'answer' => $answer['answer'],
                'x' => $answer['x'],
                'y' => $answer['y'],
                'placeholder' => $answer['placeholder'],
            ];
        }

        return $this->blankImageAnswerRepository->insert($insertData);
    }

    public function storeFillInBlankImageDistractorAnswers($questionId, $distractorAnswers)
    {
        $insertData = [];
        $current = now();
        foreach ($distractorAnswers as $answer) {
            $insertData[] = [
                'question_id' => $questionId,
                'answer' => $answer,
                'created_at' => $current,
                'updated_at' => $current,
            ];
        }

        return $this->blankImageAnswerRepository->insert($insertData);
    }

    public function getFillInImageQuestionByPart($partId)
    {
        return $this->blankImageQuestionRepository->with('answers')
            ->findWhere(['part_id' => $partId]);
    }
}
