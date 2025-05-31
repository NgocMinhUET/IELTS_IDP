<?php

namespace App\Services\CMS;

use App\Enum\QuestionTypeAPI;
use App\Models\BlankImageAnswer;
use App\Models\BlankImageQuestion;
use App\Models\ChoiceOptions;
use App\Models\ChoiceSubQuestion;
use App\Models\LBlankContentAnswer;
use App\Models\SpeakingQuestion;
use App\Models\WritingQuestion;
use App\Repositories\Test\TestInterface;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HistoryService extends BaseService
{
    public function __construct(
        public TestInterface $testRepository,
    ) {}

    public function getPaginateHistoryTests()
    {
        return $this->testRepository->getPaginateHistoryTests();
    }

    public function getDetailHistoryTest($testId)
    {
        return $this->testRepository->getDetailHistoryTest($testId);
    }

    public function prepareSkillAnswersForHistory($skillAnswers)
    {
        return $skillAnswers->map(function ($skillAnswer) {
            $originalQuestionId = $skillAnswer->question_id;
            if ($skillAnswer->question_model == (new WritingQuestion)->getTable()) {
                $originalQuestionId = WritingQuestion::toOriginId($originalQuestionId);
            } else if ($skillAnswer->question_model == (new SpeakingQuestion())->getTable()) {
                $originalQuestionId = SpeakingQuestion::toOriginId($originalQuestionId);
            } else if ($skillAnswer->question_model == (new ChoiceSubQuestion())->getTable()) {
                $originalQuestionId = ChoiceSubQuestion::toOriginId($originalQuestionId);
            }

            $answer = $skillAnswer->answer;
            if (in_array($skillAnswer->question_type, [
                QuestionTypeAPI::CHOICE->value, QuestionTypeAPI::DRAG_DROP_CONTENT->value, QuestionTypeAPI::DRAG_DROP_IMAGE->value
            ])) {
                if ($skillAnswer->question_model == (new ChoiceSubQuestion())->getTable()) {
                    $answerArr = explode(',', $skillAnswer->answer);
                    $answer = array_map(function ($a) {
                        return ChoiceOptions::toOriginId($a);
                    }, $answerArr);
                } else if ($skillAnswer->question_model == (new BlankImageAnswer())->getTable()) {
                    $answer = BlankImageAnswer::toOriginId($skillAnswer->answer);
                } else if ($skillAnswer->question_model == (new LBlankContentAnswer())->getTable()) {
                    $answer = LBlankContentAnswer::toOriginId($skillAnswer->answer);
                }
            }


            $arrSkillAnswer = $skillAnswer->toArray();
            $arrSkillAnswer['question_id'] = $originalQuestionId;
            $arrSkillAnswer['answer'] = $answer;

            return $arrSkillAnswer;
        });
    }

    public function validateUpdateSkillAnswerScorePayload(Request $request)
    {
        if (!$request->has('score') || !is_int($request->input('score'))) {
            throw new HttpException(400, 'Invalid score');
        }
    }
}
