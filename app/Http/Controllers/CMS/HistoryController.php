<?php

namespace App\Http\Controllers\CMS;

use App\Services\CMS\HistoryService;
use App\Services\CMS\StudentService;
use App\Services\CMS\TestService;

class HistoryController extends CMSController
{
    private array $rootBreadcrumbs = ['History' => null];

    public function __construct(
        public HistoryService $historyService,
        public TestService $testService,
        public StudentService $studentService,
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
            'Exam Session' => null,
            'List' => null,
        ]);

        $test = $this->testService->getTest($testId);

        $student = $this->studentService->getExamSessionsOfStudentWithTestId($testId, $studentId);

        return view('histories.exam_list', compact('test', 'student'));
    }
}