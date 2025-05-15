<?php

namespace App\Http\Controllers\CMS;

use App\Http\Requests\Test\StoreTestRequest;
use App\Http\Requests\Test\UpdateApproveStatusRequest;
use App\Services\CMS\ExamService;
use App\Services\CMS\StudentService;
use App\Services\CMS\TestService;
use Illuminate\Support\Facades\DB;

class TestController extends CMSController
{
    private array $rootBreadcrumbs = ['Test' => null];

    public function __construct(
        public TestService $testService,
        public ExamService $examService,
        public StudentService $studentService,
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
        $allStudents = $this->studentService->getPickupStudents();

        return view('tests.create', compact('allExams', 'allStudents'));
    }

    public function store(StoreTestRequest $request)
    {
        DB::beginTransaction();
        try {
            $request->merge(['exam_id' => ($request->exams)[0]]);

            $test = $this->testService->storeTest($request->only(['desc', 'start_time', 'end_time', 'exam_id', 'exams', 'students']));

            DB::commit();

            return redirect()
                ->route('admin.tests.detail', $test->id)
                ->with('success', 'Test created.');
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(500);
        }
    }

    public function detail($id)
    {
        $this->breadcrumbs = array_merge($this->rootBreadcrumbs, [
            'Detail' => null
        ]);

        $test = $this->testService->getTest($id);

        $allExams = $this->examService->getPickupExams();
        $allStudents = $this->studentService->getPickupStudents();

        return view('tests.create', compact('test', 'allExams', 'allStudents'));
    }

    public function update(StoreTestRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $request->merge(['exam_id' => ($request->exams)[0]]);

            $test = $this->testService->updateTest($id, $request->only(['desc', 'start_time', 'end_time', 'exam_id', 'exams', 'students']));

            DB::commit();

            return redirect()
                ->route('admin.tests.detail', $test->id)
                ->with('success', 'Test updated.');
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(500);
        }
    }

    public function updateApproveStatus($id, UpdateApproveStatusRequest $request): void
    {
        $this->testService->updateApproveStatus($id, $request->status);
    }
}