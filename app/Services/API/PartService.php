<?php

namespace App\Services\API;

use App\Enum\Models\SkillType;
use App\Repositories\BlankImageQuestion\BlankImageQuestionInterface;
use App\Repositories\ChoiceQuestion\ChoiceQuestionInterface;
use App\Repositories\LBlankContentQuestion\LBlankContentQuestionInterface;
use App\Repositories\Part\PartInterface;
use App\Repositories\QuestionOrder\QuestionOrderInterface;
use App\Repositories\WritingQuestion\WritingQuestionInterface;

class PartService
{
    public function __construct(
        public PartInterface $partRepository,
        public QuestionOrderInterface $questionOrderRepository,
        public WritingQuestionInterface $writingQuestionRepository,
        public ChoiceQuestionInterface $choiceQuestionRepository,
        public LBlankContentQuestionInterface $lBlankContentQuestionRepository,
        public BlankImageQuestionInterface $blankImageQuestionRepository,
    ) {}

    public function getQuestionsOfSkill($skill)
    {
        $parts = $this->getPartsOfSkill($skill->id);

        $partResponse = [];
        foreach ($parts as $part) {
            $temp['title'] = $part->title;
            $temp['desc'] = $part->desc;
            $temp['questions'] = $this->getQuestionsOfPart($part->id, $skill->type);

            $partResponse[] = $temp;
        }

        return $partResponse;
    }

    public function getPartsOfSkill($skillId)
    {
        return $this->partRepository->findByField('skill_id', $skillId);
    }

    public function getQuestionsOfPart($partId, $skillType): array
    {
        $questionOrders = $this->questionOrderRepository->getAllQuestionOrdersOfPart($partId,  $skillType)
            ->select('table', 'question_id')->toArray();

        if ($skillType == SkillType::WRITING) {
            return $this->getAllOrderedWritingQuestionsOfPart($questionOrders, $partId);
        } else {
            return $this->getAllOrderedOtherQuestionsOfPart($questionOrders, $partId);
        }
    }

    public function getAllOrderedWritingQuestionsOfPart($questionOrders, $partId): array
    {
        $writingQuestions = $this->writingQuestionRepository
            ->findWhere(['part_id' => $partId]);

        $sortMapWritingQuestions = $writingQuestions->mapWithKeys(function ($item) {
            return [$item->getTable() . '_' . $item->id => $item];
        })->all();

        $allQuestions = [];
        foreach ($questionOrders as $order) {
            if (isset($sortMapWritingQuestions[$order['table'] . '_' . $order['question_id']])) {
                $allQuestions[] = $sortMapWritingQuestions[$order['table'] . '_' . $order['question_id']];
            }
        }

        return $allQuestions;
    }

    public function getAllOrderedOtherQuestionsOfPart($questionOrders, $partId): array
    {
        $choiceQuestions = $this->choiceQuestionRepository->with('choiceSubQuestions.choiceOptions')
            ->findWhere(['part_id' => $partId]);
        $sortMapChoiceQuestions = $choiceQuestions->mapWithKeys(function ($item) {
            return [$item->getTable() . '_' . $item->id => $item];
        })->all();

        $fillInBlankQuestions = $this->lBlankContentQuestionRepository->with('answers')
            ->findWhere(['part_id' => $partId]);
        $sortFillInBlankQuestions = $fillInBlankQuestions->mapWithKeys(function ($item) {
            return [$item->getTable() . '_' . $item->id => $item];
        })->all();

        $fillInImageQuestions = $this->blankImageQuestionRepository->with('answers')
            ->findWhere(['part_id' => $partId]);
        $sortFillInImageQuestions = $fillInImageQuestions->mapWithKeys(function ($item) {
            return [$item->getTable() . '_' . $item->id => $item];
        })->all();

        $allMapQuestions = array_merge($sortMapChoiceQuestions, $sortFillInBlankQuestions, $sortFillInImageQuestions);

        $allQuestions = [];
        foreach ($questionOrders as $order) {
            if (isset($allMapQuestions[$order['table'] . '_' . $order['question_id']])) {
                $allQuestions[] = ($allMapQuestions[$order['table'] . '_' . $order['question_id']])->toArray();
            }
        }

        return $allQuestions;
    }
}
