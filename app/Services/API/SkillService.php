<?php

namespace App\Services\API;

use App\Enum\AnswerType;
use App\Enum\Models\SkillType;
use App\Enum\QuestionTypeAPI;
use App\Models\Skill;
use App\Repositories\BlankImageQuestion\BlankImageQuestionInterface;
use App\Repositories\ChoiceQuestion\ChoiceQuestionInterface;
use App\Repositories\LBlankContentQuestion\LBlankContentQuestionInterface;
use App\Repositories\Part\PartInterface;
use App\Repositories\Skill\SkillInterface;
use App\Repositories\WritingQuestion\WritingQuestionInterface;

class SkillService
{
    public function __construct(
        public SkillInterface $skillRepository,
        public PartInterface $partRepository,
        public WritingQuestionInterface $writingQuestionRepository,
        public ChoiceQuestionInterface $choiceQuestionRepository,
        public LBlankContentQuestionInterface $lBlankContentQuestionRepository,
        public BlankImageQuestionInterface $blankImageQuestionRepository,
    ) {}

    public function getSkill($id)
    {
        return $this->skillRepository->find($id);
    }

    public function getSkillByExam($examId)
    {
        return $this->skillRepository->findWhere([
            'exam_id' => $examId,
        ]);
    }

    public function getAllListenOrReadingSkillQuestionsAndAnswers(Skill $skill): array
    {
        $parts = $this->getPartsOfSkill($skill->id);

        $questionAndAnswers = [];
        foreach ($parts as $part) {
            $questionAndAnswers = array_merge($questionAndAnswers, $this->getAllListeningOrReadingQuestionsOfPart($part->id));
        }

        return $questionAndAnswers;
    }

    public function getAllWritingSkillQuestionsAndAnswers(Skill $skill): array
    {
        $parts = $this->getPartsOfSkill($skill->id);

        $writingQuestions = [];
        foreach ($parts as $part) {
            $writingQuestions = array_merge($writingQuestions, $this->getAllWritingQuestionsOfPart($part->id));
        }

        return $writingQuestions;
    }

    public function getPartsOfSkill($skillId)
    {
        return $this->partRepository->findByField('skill_id', $skillId);
    }

    public function getAllWritingQuestionsOfPart($partId): array
    {
        $questions = $this->writingQuestionRepository
            ->findWhere(['part_id' => $partId]);

        $writingQuestions = [];
        foreach ($questions as $question) {
            $tmp = [
                'question_id' => $question->input_identify,
                'question_model' => $question->getTable(),
                'question_type' => $question->type,
                'score' => $question->score,
            ];
            $writingQuestions[] = $tmp;
        }

        return $writingQuestions;
    }

    public function getAllListeningOrReadingQuestionsOfPart($partId): array
    {
        $choiceQuestions = $this->choiceQuestionRepository->with('choiceSubQuestions.choiceOptions')
            ->findWhere(['part_id' => $partId]);

        $fillInBlankQuestions = $this->lBlankContentQuestionRepository->with('answers')
            ->findWhere(['part_id' => $partId]);

        $fillInImageQuestions = $this->blankImageQuestionRepository->with('answers')
            ->findWhere(['part_id' => $partId]);

        $questionAndAnswers = [];

        foreach ($choiceQuestions as $choiceQuestion) {
            foreach ($choiceQuestion->choiceSubQuestions as $choiceSubQuestion) {
                $questionAndAnswer = [
                    'question_id' => $choiceSubQuestion->input_identify,
                    'question_model' => $choiceSubQuestion->getTable(),
                    'answer_id' => $choiceSubQuestion->choiceOptions->where('is_correct', true)
                        ->pluck('id')->toArray(),
                    'answer' => null,
                    'question_type' => $choiceQuestion->type,
                    'score' => $choiceSubQuestion->score,
                ];
                $questionAndAnswers[] = $questionAndAnswer;
            }
        }


        $questionAndAnswers = array_merge($questionAndAnswers, $this->makeBlankQuestionAndAnswerArray($fillInBlankQuestions));

        return array_merge($questionAndAnswers, $this->makeBlankQuestionAndAnswerArray($fillInImageQuestions));
    }

    public function makeBlankQuestionAndAnswerArray($blankQuestions): array
    {
        $questionAndAnswers = [];
        foreach ($blankQuestions as $question) {
            $tableModel = $question->getTable();
            $questionType = $question->type;
            $answerType = $question->answer_type;

            foreach ($question->answers as $answer) {
                if (!is_null($answer->input_identify)) {
                    $questionAndAnswers[] = [
                        'question_id' => $answer->input_identify,
                        'question_type' => $questionType,
                        'question_model' => $tableModel,
                        'answer_id' => $answerType == AnswerType::FILL->value ? null : $answer->id,
                        'answer' => $answerType == AnswerType::FILL->value ? $answer->answer : null,
                        'score' => $answer->score,
                    ];
                }
            }
        }

        return $questionAndAnswers;
    }

    public function buildPartQuestionsResponse(Skill $skill, $partQuestions): array
    {
        $response = [];

        $response['skill_type'] = $skill->type->value;
        $response['skill_label'] = $skill->type->name ?? '';
        $response['skill_desc'] = $skill->desc ?? '';
        $response['duration'] = $skill->duration;
        $response['bonus_time'] = $skill->bonus_time;
        $response['audio'] = '';
        $response['parts'] = $partQuestions;

        // get audio if skill is listening
        if ($skill->type == SkillType::LISTENING) {
            $response['audio'] = $skill->getFirstMediaUrl() ?? '';
        }

        return $response;
    }
}
