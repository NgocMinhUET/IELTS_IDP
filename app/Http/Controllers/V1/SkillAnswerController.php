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
use Symfony\Component\HttpKernel\Exception\HttpException;

class SkillAnswerController extends Controller
{
    public function __construct(
        public SkillAnswerService $skillAnswerService,
        public SkillSessionService $skillSessionService,
        public ExamSessionService $examSessionService,
        public SkillService $skillService,
    ) {}

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

        //TODO: refactor speaking skill
        if (in_array($skill->type, [SkillType::LISTENING, SkillType::READING])) {
            $skillQuestions = $this->skillService->getAllListenOrReadingSkillQuestionsAndAnswers($skill);

            $this->skillAnswerService->storeAnswerAndGetResult($request, $skillQuestions);
        } else {
            $skillQuestions = $this->skillService->getAllWritingSkillQuestionsAndAnswers($skill);

            $this->skillAnswerService->storeAnswer($request, $skillQuestions);
        }
    }
}
