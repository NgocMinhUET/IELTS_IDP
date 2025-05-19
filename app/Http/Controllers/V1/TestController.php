<?php

namespace App\Http\Controllers\V1;

use App\Common\ResponseApi;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiRequest;
use App\Http\Requests\Test\EnrollTestAPIRequest;
use App\Services\API\ExamSessionService;
use App\Services\API\PartService;
use App\Services\API\SkillAnswerService;
use App\Services\API\SkillService;
use App\Services\API\SkillSessionService;
use App\Services\API\TestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TestController extends Controller
{
    public function __construct(
        public TestService $testService,
        public ExamSessionService $examSessionService,
        public SkillService $skillService,
        public PartService $partService,
        public SkillSessionService $skillSessionService,
        public SkillAnswerService $skillAnswerService,
    ) {}

    public function getTests(): \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $tests = $this->testService->getAssignedToUserTests();

        return ResponseApi::success('', $tests);
    }

    /**
     * @throws \Throwable
     */
    public function enrollTest(EnrollTestAPIRequest $request): \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
    {
        DB::beginTransaction();
        try {
            $testID = $request->input('test_id');

            $testDetail = $this->testService->enrollTest($testID);

            DB::commit();

            return ResponseApi::success('', $testDetail);
        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }
    }

    public function getTestHistories()
    {
        $tests = $this->testService->getTestHistories();

        return ResponseApi::success('', $tests);
    }

    public function getDetailTestHistory($id)
    {
        $examSessions = $this->examSessionService->getListExamSessionOfHistoryTest($id);

        if ($examSessions->count() == 0) {
            throw new HttpException(403, 'No exam session found for this test history');
        }

        //TODO: append score

        return ResponseApi::success('', $this->testService->buildDetailTestHistoryResponse($examSessions));
    }

    public function getSkillOfExamHistory($id, $sessionId)
    {
        $examSession = $this->examSessionService->getExamSessionOfHistoryTest($sessionId, $id);

        if (is_null($examSession)) {
            throw new HttpException(403, 'No exam session found for this test history');
        }

        $exam = $examSession->exam;

        return ResponseApi::success('', $this->testService->buildExamResponse($exam, true));
    }

    public function getAnswerHistory($id, $sessionId, $skillId)
    {
        $examSession = $this->examSessionService->getExamSessionOfHistoryTest($sessionId, $id);

        if (is_null($examSession)) {
            throw new HttpException(403, 'No exam session found for this test history');
        }

        $skills = $this->skillService->getSkillByExam($examSession->exam_id);

        $skill = $skills->where('id', $skillId)->first();

        if (is_null($skill)) {
            throw new HttpException(403, 'No skill found for this test history');
        }

        $partQuestions = $this->partService->getQuestionsOfSkill($skill);
        $baseQuestions = $this->skillService->buildPartQuestionsResponse($skill, $partQuestions);

        $skillAnswers = [];
        $skillSession = $this->skillSessionService->getSkillSessionFromExamSessionAndSkillId($examSession->id, $skill->id);
        if ($skillSession) {
            $skillAnswers = $this->skillAnswerService->getAllAnswerBySkillSession($skillSession->id);
            $skillAnswers = $this->testService->buildAnswersResponse($skillAnswers);
        }

        $baseQuestions['answers'] = $skillAnswers;

        return ResponseApi::success('', $baseQuestions);
    }
}
