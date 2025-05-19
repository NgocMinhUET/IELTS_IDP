<?php

namespace App\Http\Controllers\V1;

use App\Common\ResponseApi;
use App\Enum\Models\SkillType;
use App\Http\Controllers\Controller;
use App\Http\Requests\SkillAnswer\SubmitAnswerAPIRequest;
use App\Services\API\ExamSessionService;
use App\Services\API\SkillAnswerService;
use App\Services\API\SkillService;
use App\Services\API\SkillSessionService;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SkillAnswerController extends Controller
{
    public function __construct(
        public SkillAnswerService $skillAnswerService,
        public SkillSessionService $skillSessionService,
        public ExamSessionService $examSessionService,
        public SkillService $skillService,
    ) {}

    /**
     * @throws \Throwable
     */
    public function submitAnswer(SubmitAnswerAPIRequest $request)
    {
        $userId = auth()->id();
        $skillSession = $this->skillSessionService->validateSkillSessionToken($request->skill_session_token);

        $examSession = $this->examSessionService->getExamSessionFromId($skillSession->exam_session_id);

        if ($examSession->user_id != $userId) {
            throw new HttpException(403, 'The session not allowed to submit answers.');
        }

        $skill = $this->skillService->getSkill($skillSession->skill_id);
        if ($skill->exam_id != $examSession->exam_id) {
            throw new HttpException(403, 'The session not allowed to submit answers.');
        }

        $answerPayload = $this->skillAnswerService->validateAnswerPayload($request);

        //TODO: refactor speaking skill
        if (in_array($skill->type, [SkillType::LISTENING, SkillType::READING])) {
            $skillQuestions = $this->skillService->getAllListenOrReadingSkillQuestionsAndAnswers($skill);

            $compareAnswers = $this->skillAnswerService->compareAnswer($answerPayload, $skillQuestions);

            $result = $this->skillAnswerService->buildResultScoreResponse($compareAnswers);
        } else {
            $writingQuestions = $this->skillService->getAllWritingSkillQuestionsAndAnswers($skill);

            $compareAnswers = $this->skillAnswerService->compareWritingAnswer($answerPayload, $writingQuestions);

            $result = [];
        }

        try {
            DB::beginTransaction();
            $this->skillAnswerService->storeAnswerAfterCompare($compareAnswers, $skillSession->id);

            // remove skill session
            $this->skillSessionService->revokeSkillSessionToken($skillSession);

            //update exam session
            //TODO: refactor if this last skill
            $this->examSessionService->updateExamSessionStatusAfterSkillSubmit($examSession);
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }

        return ResponseApi::success('', $result);
    }
}
