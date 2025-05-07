<?php

namespace App\Http\Controllers\CMS;

use App\Http\Requests\Test\StoreTestRequest;
use App\Services\CMS\ExamService;
use App\Services\CMS\SkillService;
use App\Services\CMS\TestService;

class TestController extends CMSController
{
    private array $rootBreadcrumbs = ['Test' => null];

    public function __construct(
        public TestService $testService,
        public ExamService $examService,
    ) {
        $this->rootBreadcrumbs['Test'] = route('admin.tests.index');
    }

    public function index()
    {
        $this->breadcrumbs = array_merge($this->rootBreadcrumbs, [
            'List' => null
        ]);

        $tests = $this->testService->getPaginateTests();

        return view('tests.index', compact('tests'));
    }

    public function create()
    {
        $this->breadcrumbs = array_merge($this->rootBreadcrumbs, [
            'Create' => null
        ]);

        $allExams = $this->examService->getPickupExams();

        return view('tests.create', compact('allExams'));
    }

    public function store(StoreTestRequest $request)
    {
        $request->merge(['exam_id' => ($request->exams)[0]]);

        $test = $this->testService->storeTest($request->only(['desc', 'start_time', 'end_time', 'exam_id']));

        return redirect()
            ->route('admin.tests.detail', $test->id)
            ->with('success', 'Test created.');
    }

    public function detail($id)
    {
        $this->breadcrumbs = array_merge($this->rootBreadcrumbs, [
            'Detail' => null
        ]);

        $test = $this->testService->getTest($id);

        $allExams = $this->examService->getPickupExams();

        return view('tests.create', compact('test', 'allExams'));
    }

    public function update(StoreTestRequest $request, $id)
    {
        $request->merge(['exam_id' => ($request->exams)[0]]);

        $test = $this->testService->updateTest($id, $request->only(['desc', 'start_time', 'end_time', 'exam_id']));

        return redirect()
            ->route('admin.tests.detail', $test->id)
            ->with('success', 'Test updated.');
    }
}