<?php

namespace App\Http\Controllers\V1;

use App\Common\ResponseApi;
use App\Enum\Models\SkillType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Question\GetQuestionAPIRequest;
use App\Services\API\ExamSessionService;
use App\Services\API\PartService;
use App\Services\API\SkillService;
use App\Services\API\SkillSessionService;
use App\Services\API\TestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SkillController extends Controller
{
    public function __construct(
        public SkillService $skillService,
        public PartService $partService,
        public ExamSessionService $examSessionService,
        public SkillSessionService $skillSessionService,
        public TestService $testService,
    ) {}

    public function getSkillForExam(Request $request)
    {
        // skill id
        if (!$request->has('exam_id')) {
            return ResponseApi::bad();
        }

        $examId = $request->input('exam_id');
        return ResponseApi::success('', $this->skillService->getSkillByExam($examId));
    }

    /**
     * @throws \Throwable
     */
    public function getQuestions(GetQuestionAPIRequest $request)
    {
        $userId = auth()->id();

        // refresh API get questions
        $hasSkillSessionToken = $request->has('skill_session_token');

        $examSession = $this->examSessionService->validateExamSessionFromToken($request->input('exam_session_token'), !$hasSkillSessionToken);
        $skillId = $request->input('target_id');
        $skill = $this->skillService->getSkill($skillId);
        if ($examSession->exam_id != $skill->exam_id) {
            throw new HttpException(403, 'You are not allowed to access this skill');
        }

        // first request for get questions
        if (is_null($examSession->expired_at)) {
            // validate test is public
            $this->testService->validateTest($examSession->test_id, $userId);
        } else {
            if ($examSession->expired_at <= now()) {
                throw new HttpException(403, 'This test has expired');
            }
        }

        if ($hasSkillSessionToken) {
            $skillSessionToken = $request->input('skill_session_token');
            $skillSession = $this->skillSessionService->getSkillSessionFromToken($skillSessionToken);

            if ($skillSession->exam_session_id != $examSession->id || $skillSession->skill_id != $skill->id) {
                throw new HttpException(403, 'You are not allowed to access this skill with skill session token');
            }
        }


        DB::beginTransaction();
        try {
            $partQuestions = $this->partService->getQuestionsOfSkill($skill);

            $response = $this->skillService->buildPartQuestionsResponse($skill, $partQuestions);

            // store skill session
            $skillSession = $hasSkillSessionToken ? $skillSession : $this->skillSessionService->makeSkillSessionService($examSession->id, $skill);
            $response['skill_session_token'] = $skillSession->generateEncryptedToken();
            $response['seconds_remaining'] = $skillSession->seconds_remaining;

            // change expired exam session
            $examSession = $this->examSessionService->setExamSessionStatusAndExpiredAfterGetSkillQuestion($examSession->id, $skill);
            $response['exam_session_token_expired_at'] = $examSession->expired_at->format('Y-m-d H:i:s');

            DB::commit();

            return ResponseApi::success('', $response);
        } catch (\Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }
    }
}
