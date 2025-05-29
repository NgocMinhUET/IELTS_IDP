<?php

namespace App\Http\Controllers\V1;

use App\Common\ResponseApi;
use App\Enum\Models\SkillType;
use App\Http\Controllers\Controller;
use App\Http\Requests\SkillAnswer\GetSpeakingPresignedUrlAPIRequest;
use App\Http\Requests\SkillAnswer\SubmitAnswerAPIRequest;
use App\Services\API\ExamSessionService;
use App\Services\API\SkillAnswerService;
use App\Services\API\SkillService;
use App\Services\API\SkillSessionService;
use Illuminate\Http\Request;
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
        if ($skill->exam_id != $examSession->exam_id || $skill->type == SkillType::SPEAKING) {
            throw new HttpException(403, 'The session not allowed to submit answers.');
        }

        $answerPayload = $this->skillAnswerService->validateAnswerPayload($request);

        $analyticData = [
            'total_correct_answer' => 0,
            'total_pending_answer' => 0,
            'total_correct_score' => 0,
        ];

        if (in_array($skill->type, [SkillType::LISTENING, SkillType::READING])) {
            $skillQuestions = $this->skillService->getAllListenOrReadingSkillQuestionsAndAnswers($skill);

            [$compareAnswers, $numberOfCorrectAnswer, $totalCorrectScore] = $this->skillAnswerService->compareAnswer($answerPayload, $skillQuestions);

            $analyticData['total_question'] = count($skillQuestions);
            $analyticData['total_submitted_answer'] = count($answerPayload);
            $analyticData['total_correct_answer'] = $numberOfCorrectAnswer;
            $analyticData['total_correct_score'] = $totalCorrectScore;
            $analyticData['total_score'] = array_sum(array_column($skillQuestions, 'score'));

            $result = $this->skillAnswerService->buildResultScoreResponse($compareAnswers);
        } else {
            $writingQuestions = $this->skillService->getAllWritingSkillQuestionsAndAnswers($skill);

            $compareAnswers = $this->skillAnswerService->compareWritingAnswer($answerPayload, $writingQuestions);

            $analyticData['total_question'] = count($writingQuestions);
            $analyticData['total_submitted_answer'] = count($answerPayload);
            $analyticData['total_pending_answer'] = $analyticData['total_submitted_answer'];
            $analyticData['total_score'] = array_sum(array_column($writingQuestions, 'score'));

            $result = [];
        }

        try {
            DB::beginTransaction();
            $this->skillAnswerService->storeAnswerAfterCompare($compareAnswers, $skillSession->id);

            // remove skill session
            $this->skillSessionService->revokeSkillSessionToken($skillSession);
            //update question and answer analytic to skill session
            $this->skillSessionService->updateSkillSessionToken($skillSession, $analyticData);

            //update exam session
            $this->examSessionService->updateExamSessionStatusAfterSkillSubmit($examSession);
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }

        return ResponseApi::success('', $result);
    }

    public function getSpeakingRecordPresignedUrl(GetSpeakingPresignedUrlAPIRequest $request)
    {
        $userId = auth()->id();
        [$skillSession, $examSession, $skill] = $this->validateSpeakingQuestionRequest($request);

        [$payloadQuestionId, $speakingQuestion] = $this->skillAnswerService->validateSpeakingAnswerPayload($request->question_id);

        $skillQuestions = $this->skillService->getAllSpeakingSkillQuestionsAndAnswers($skill);

        if (!in_array($request->question_id, array_column($skillQuestions, 'question_id'))) {
            throw new HttpException(403, 'The question id not found in skill questions.');
        }

        $this->skillAnswerService->isFirstRequestGetSpeakingRecordPresignedUrl($skillSession->id, $speakingQuestion);

        [$presignedUrl, $path, $storageDisk] = $this->skillAnswerService
            ->getSpeakingRecordPresignedUrl($speakingQuestion, $examSession, $skillSession, $userId);

        $this->skillAnswerService->storeSpeakingQuestionAnswer($skillSession, $speakingQuestion,
            ['path' => $path, 'storage' => $storageDisk]
        );

        return ResponseApi::success('', [
            'presigned_url' => $presignedUrl,
//            'path' => $path,
            'question_id' => $payloadQuestionId,
        ]);
    }

    /**
     * @throws \Throwable
     */
    public function markSpeakingRecordAsSent(GetSpeakingPresignedUrlAPIRequest $request)
    {
        try {
            DB::beginTransaction();

            [$skillSession, $examSession, $skill] = $this->validateSpeakingQuestionRequest($request);

            [$payloadQuestionId, $speakingQuestion] = $this->skillAnswerService->validateSpeakingAnswerPayload($request->question_id);

            $this->skillAnswerService->markSpeakingRecordAsSent($skillSession->id, $speakingQuestion);

            $this->skillAnswerService->updateSpeakingSkillSessionAfterSent($skillSession);

            $this->examSessionService->updateExamSessionStatusAfterSkillSubmit($examSession);

            DB::commit();

            return ResponseApi::success();
        } catch (\Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }

    }

    public function validateSpeakingQuestionRequest(Request $request): array
    {
        $userId = auth()->id();
        $skillSession = $this->skillSessionService->validateSkillSessionToken($request->skill_session_token);

        $examSession = $this->examSessionService->getExamSessionFromId($skillSession->exam_session_id);

        if ($examSession->user_id != $userId) {
            throw new HttpException(403, 'The session not allowed to request url.');
        }

        $skill = $this->skillService->getSkill($skillSession->skill_id);
        if ($skill->exam_id != $examSession->exam_id || $skill->type != SkillType::SPEAKING) {
            throw new HttpException(403, 'The session not allowed to request url.');
        }

        return [$skillSession, $examSession, $skill];
    }
}