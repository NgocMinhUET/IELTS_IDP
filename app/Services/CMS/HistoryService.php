<?php

namespace App\Services\CMS;

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
            }

            $arrSkillAnswer = $skillAnswer->toArray();
            $arrSkillAnswer['question_id'] = $originalQuestionId;

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
