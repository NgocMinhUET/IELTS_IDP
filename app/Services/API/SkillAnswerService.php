<?php

namespace App\Services\API;

use App\Enum\Models\AnswerResult;
use App\Enum\QuestionType;
use App\Enum\QuestionTypeAPI;
use App\Models\BlankImageAnswer;
use App\Models\ChoiceOptions;
use App\Models\ChoiceSubQuestion;
use App\Models\LBlankContentAnswer;
use App\Models\Skill;
use App\Models\WritingQuestion;
use App\Repositories\SkillAnswer\SkillAnswerInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SkillAnswerService
{
    public function __construct(
        public SkillAnswerInterface $skillAnswerRepository,
    ) {}

    public function storeAnswerAndGetResult($answerPayload, $skillQuestions): array
    {
        return $this->compareAnswer($answerPayload, $skillQuestions);
    }

    public function storeAnswerAfterCompare($compareAnswers, $skillSessionId): void
    {
        $insertData = [];
        $now = now();

        foreach ($compareAnswers as $compareAnswer) {
            $insertData[] = [
                'skill_session_id' => $skillSessionId,
                'question_model' => $compareAnswer['question_model'],
                'question_id' => $compareAnswer['question_id'],
                'question_type' => $compareAnswer['question_type'],
                'answer' => $compareAnswer['answer'],
                'answer_result' => $compareAnswer['is_correct'] ? AnswerResult::CORRECT->value : AnswerResult::INCORRECT->value,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $this->skillAnswerRepository->insert($insertData);
    }

    public function buildResultScoreResponse($compareAnswers): array
    {
        return array_map(function ($compareAnswer) {
            return [
                'question_id' => $compareAnswer['question_id'],
                'question_type' => $compareAnswer['question_type'],
                'answer' => $compareAnswer['answer'],
                'is_correct' => $compareAnswer['is_correct'],
            ];
        }, $compareAnswers);
    }

    public function compareAnswer($answerPayload, $skillQuestions): array
    {
        $answerResult = [];
        foreach ($answerPayload as $answer) {
            $found = [];
            foreach ($skillQuestions as $skillQuestion) {
                if ($skillQuestion['question_id'] == $answer['question_id']
                    && $skillQuestion['question_model'] == $answer['question_model']
                    && $skillQuestion['question_type'] == $answer['question_type']) {
                    $found = $skillQuestion;
                    break;
                }
            }
            if (empty($found)) {
                throw new HttpException(400, 'Bad Request');
            }

            $answer['is_correct'] = false;

            if (in_array($answer['question_type'], [QuestionTypeAPI::FILL_CONTENT->value, QuestionTypeAPI::FILL_IMAGE->value])) {
                if (strtolower($answer['answer']) == strtolower($found['answer'])) {
                    $answer['is_correct'] = true;
                }
            } else {
                if (is_array($answer['answer_id'])) {
                    if ($answer['answer_id'] === $found['answer_id']) {
                        $answer['is_correct'] = true;
                    }
                } else {
                    if (($answer['answer_id'] == $found['answer_id']) && !is_null($answer['answer_id'])) {
                        $answer['is_correct'] = true;
                    }
                }
            }

            $answerResult[] = $answer;
        }

        return $answerResult;
    }

    public function validateAnswerPayload(Request $request): array
    {
        $answers = $request->input('answers');
        $questionTypes = QuestionTypeAPI::values();
        $hasEncryptAnswerIdType = [
            QuestionTypeAPI::CHOICE->value,
            QuestionTypeAPI::DRAG_DROP_IMAGE->value,
            QuestionTypeAPI::DRAG_DROP_CONTENT->value,
        ];

        $convertAnswers = [];
        foreach ($answers as $answer) {
            $questionType = $answer['question_type'];
            if (!in_array($questionType, $questionTypes)) {
                throw new HttpException(400, 'Invalid question type');
            }
            $questionModel = QuestionTypeAPI::fromValueToQuestionModel($questionType);
            if ($questionModel instanceof ChoiceSubQuestion || $questionModel instanceof WritingQuestion) {
                $originalQuestionId = $questionModel::toOriginId($answer['question_id']);
                if (!$originalQuestionId) {
                    throw new HttpException(400, 'Invalid question id');
                }
                $answer['question_id'] = $originalQuestionId;
            }

            $originalAnswerId = null;
            $answerModel = null;
            if (in_array($questionType, $hasEncryptAnswerIdType)) {
                $answerModel = QuestionTypeAPI::fromValueToAnswerModel($questionType);
                if ($answerModel instanceof ChoiceOptions) {
                    $arrAnswers = explode(',', $answer['answer']);
                    foreach ($arrAnswers as $value) {
                        $answerId = $answerModel::toOriginId($value);
                        if (!$answerId) {
                            throw new HttpException(400, 'Invalid answer id');
                        }
                        $originalAnswerId[] = $answerId;
                    }
                } else {
                    $originalAnswerId = $answerModel::toOriginId($answer['answer']);
                    if (!$originalAnswerId) {
                        throw new HttpException(400, 'Invalid answer id');
                    }
                }
            }


            $answer['question_model'] = $questionModel->getTable();
            $answer['answer_id'] = $originalAnswerId;
            $answer['answer_model'] = $answerModel ? $answerModel->getTable() : null;
            $convertAnswers[] = $answer;
        }

        return $convertAnswers;
    }
}
