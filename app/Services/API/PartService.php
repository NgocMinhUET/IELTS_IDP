<?php

namespace App\Services\API;

use App\Enum\AnswerType;
use App\Enum\Models\SkillType;
use App\Models\BlankImageQuestion;
use App\Models\ChoiceQuestion;
use App\Models\LBlankContentQuestion;
use App\Models\WritingQuestion;
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
            $temp['title'] = $part->title ?? '';
            $temp['desc'] = $part->desc ?? '';
            $temp['paragraphs'] = $part->paragraph ? $part->paragraph->content : '';
            $temp['questions'] = $this->getQuestionsOfPart($part->id, $skill->type);

            $partResponse[] = $temp;
        }

        return $partResponse;
    }

    public function getPartsOfSkill($skillId)
    {
        return $this->partRepository->with('paragraph')->findByField('skill_id', $skillId);
    }

    public function getQuestionsOfPart($partId, $skillType): array
    {
        $questionOrders = $this->questionOrderRepository->getAllQuestionOrdersOfPart($partId, $skillType)
            ->select('table', 'question_id')->toArray();

        if ($skillType == SkillType::WRITING) {
            $questions = $this->getAllOrderedWritingQuestionsOfPart($questionOrders, $partId);
        } else {
            $questions = $this->getAllOrderedOtherQuestionsOfPart($questionOrders, $partId);
        }

        return $this->mapQuestionAPI($questions);
    }

    public function getAllOrderedWritingQuestionsOfPart($questionOrders, $partId): array
    {
        $writingQuestions = $this->writingQuestionRepository
            ->findWhere(['part_id' => $partId]);

        $sortMapWritingQuestions = $this->mapKeyForQuestionModels($writingQuestions);

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
        $sortMapChoiceQuestions = $this->mapKeyForQuestionModels($choiceQuestions);

        $fillInBlankQuestions = $this->lBlankContentQuestionRepository->with('answers')
            ->findWhere(['part_id' => $partId]);
        $sortFillInBlankQuestions = $this->mapKeyForQuestionModels($fillInBlankQuestions);

        $fillInImageQuestions = $this->blankImageQuestionRepository->with('answers')
            ->findWhere(['part_id' => $partId]);
        $sortFillInImageQuestions = $this->mapKeyForQuestionModels($fillInImageQuestions);

        $allMapQuestions = array_merge($sortMapChoiceQuestions, $sortFillInBlankQuestions, $sortFillInImageQuestions);

        $allQuestions = [];
        foreach ($questionOrders as $order) {
            if (isset($allMapQuestions[$order['table'] . '_' . $order['question_id']])) {
                $allQuestions[] = $allMapQuestions[$order['table'] . '_' . $order['question_id']];
            }
        }

        return $allQuestions;
    }

    public function mapKeyForQuestionModels($questions)
    {
        return $questions->mapWithKeys(function ($item) {
            return [$item->getTable() . '_' . $item->id => $item];
        })->all();
    }

    public function mapQuestionAPI($questions): array
    {
        return array_map(function ($question) {
            $tmp = [];
            $tmp['type'] = $question->type;
            $tmp['title'] = $question->title ?? '';
            $tmp['component'] = $this->buildQuestionComponent($question);

            return $tmp;
        }, $questions);
    }

    public function buildQuestionComponent($question): array
    {
        $component = [];
        $component['question_content'] = $question->content ?? '';
        $component['question_content_inherit'] = false;
        $component['answer_label'] = $question->answer_label ?? '';
        $component['sub_question_ids'] = [];
        $component['answers'] = [];
        $component['choice_question_detail'] = (object) [];

        if ($question instanceof LBlankContentQuestion || $question instanceof BlankImageQuestion) {
            if (isset($question->content_inherit) && $question->content_inherit == LBlankContentQuestion::IS_CONTENT_INHERIT) {
                $component['question_content_inherit'] = true;
                $component['question_content'] = '';
            }
            $answers = $question->answers;
            if ($answers->count()) {
                $component['sub_question_ids'] = $answers->whereNotNull('input_identify')
                    ->pluck('input_identify')->toArray();
                if ($question->answer_type == AnswerType::DRAG_DROP->value) {
                    $component['answers'] = $answers->select('answer_identify', 'answer')->shuffle()->toArray();
                }
            }
        } else if ($question instanceof ChoiceQuestion) {
            $subQuestions = $question->choiceSubQuestions;
            if ($subQuestions->count()) {
                $component['sub_question_ids'] = $subQuestions->pluck('input_identify')->toArray();
                $choiceQuestionDetail['min'] = $question->min_option ?? 1;
                $choiceQuestionDetail['max'] = $question->max_option ?? 1;
                $subQuestionDetails = $subQuestions->map(function ($subQuestion) {
                    $tmp['title'] = $subQuestion->question;
                    $tmp['min'] = $subQuestion->min_option ?? 1;
                    $tmp['max'] = $subQuestion->max_option ?? 1;
                    $tmp['answers'] = $subQuestion->choiceOptions->select('answer_identify', 'answer')->toArray();

                    return $tmp;
                });
                $choiceQuestionDetail['sub_questions']= $subQuestionDetails->toArray();
                $component['choice_question_detail'] = $choiceQuestionDetail;
            }
        } else if ($question instanceof WritingQuestion) {
            $component['sub_question_ids'] = [$question->input_identify];
        }

        return $component;
    }
}
