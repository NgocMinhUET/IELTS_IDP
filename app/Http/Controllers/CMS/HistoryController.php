<?php

namespace App\Http\Controllers\CMS;

use App\Enum\Models\AnswerResult;
use App\Services\CMS\HistoryService;
use App\Services\CMS\SkillAnswerService;
use App\Services\CMS\SkillService;
use App\Services\CMS\SkillSessionService;
use App\Services\CMS\StudentService;
use App\Services\CMS\TestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoryController extends CMSController
{
    private array $rootBreadcrumbs = ['History' => null];

    public function __construct(
        public HistoryService $historyService,
        public TestService $testService,
        public StudentService $studentService,
        public SkillSessionService $skillSessionService,
        public SkillAnswerService $skillAnswerService,
        public SkillService $skillService,
    ) {
        $this->rootBreadcrumbs['History'] = route('admin.histories.index');
    }

    public function index()
    {
        $this->breadcrumbs = array_merge($this->rootBreadcrumbs, [
            'List' => null
        ]);

        $tests = $this->historyService->getPaginateHistoryTests();

        return view('histories.index', compact('tests'));
    }

    public function getListHistoryStudentsOfTest($testId)
    {
        $this->breadcrumbs = array_merge($this->rootBreadcrumbs, [
            'Students' => null,
            'List' => null,
        ]);

        $test = $this->testService->getTest($testId);

        $students = $this->studentService->getHistoryStudentsOfTest($testId);

        return view('histories.student_list', compact('test', 'students'));
    }

    public function getListHistorySessionsOfStudentS($testId, $studentId)
    {
        $this->breadcrumbs = array_merge($this->rootBreadcrumbs, [
            'Students' => route('admin.histories.list-student', $testId),
            'Exam Sessions' => null,
            'List' => null,
        ]);

        $test = $this->testService->getTest($testId);

        // student & list history exams
        $student = $this->studentService->getExamSessionsOfStudentWithTestId($testId, $studentId);

        return view('histories.exam_list', compact('test', 'student'));
    }

    public function getDetailSkillSession($testId, $studentId, $skillSessionId)
    {
        $this->breadcrumbs = array_merge($this->rootBreadcrumbs, [
            'Students' => route('admin.histories.list-student', $testId),
            'Exam Sessions' => route('admin.histories.list-exam-session', [$testId, $studentId]),
            'Skill' => null,
            'Detail' => null,
        ]);

        $test = $this->testService->getTest($testId);
        $skillSession = $this->skillSessionService->getSkillSession($skillSessionId);
        $examSession = $skillSession->examSession;
        if (!$examSession) {
            abort(400);
        }
        if ($examSession->test_id != $testId || $examSession->user_id != $studentId) {
            abort(400);
        }

        $skillQuestionsByPart = $this->skillService->getAllQuestionsBySkillId($skillSession->skill_id);

//        dd($skillQuestionsByPart);

        $skillAnswers = $this->historyService->prepareSkillAnswersForHistory($skillSession->skillAnswers);

//        dd($skillAnswers, $skillQuestionsByPart);


        return view('histories.detail_skill', compact('skillQuestionsByPart', 'skillAnswers'));
    }

    /**
     * @throws \Throwable
     */
    public function updateSkillAnswerScore($skillAnswerId, Request $request): \Illuminate\Http\JsonResponse
    {
        $this->historyService->validateUpdateSkillAnswerScorePayload($request);
        $newScore = $request->input('score');

        $skillAnswer = $this->skillAnswerService->getSkillAnswerById($skillAnswerId);
        $oldScore = $skillAnswer->score;
        $isPendingAnswer = $skillAnswer->answer_result == AnswerResult::PENDING->value;

        $scoreDiff = (int)$newScore - (int)$oldScore;

        if ($oldScore != $newScore) {
            try {
                DB::beginTransaction();

                $newSkillAnswer = $this->skillAnswerService->updateSkillAnswerScore($skillAnswerId, $newScore, $isPendingAnswer);
                $this->skillSessionService->updateSkillSessionAfterChangeScore($newSkillAnswer->skill_session_id, $scoreDiff, $isPendingAnswer);

                DB::commit();
            } catch (\Throwable $throwable) {
                DB::rollBack();
                throw $throwable;
            }
        }

        return response()->json(['score' => $newScore]);
    }
}