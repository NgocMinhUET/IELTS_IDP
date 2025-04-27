<?php

namespace App\Http\Controllers\CMS;

use App\Http\Requests\Exam\StoreExamRequest;
use App\Services\CMS\ExamService;
use App\Services\CMS\SkillService;
use Illuminate\Support\Facades\DB;

class ExamController extends CMSController
{
    private array $rootBreadcrumbs = ['Exam' => null];

    public function __construct(
        public ExamService $examService,
        public SkillService $skillService,
    ) {
        $this->rootBreadcrumbs['Exam'] = route('admin.exams.index');
    }

    public function index()
    {
        $this->breadcrumbs = array_merge($this->rootBreadcrumbs, [
            'List' => null
        ]);

        $exams = $this->examService->getPaginateExams();

        return view('exams.index', compact('exams'));
    }

    public function create()
    {
        $this->breadcrumbs = array_merge($this->rootBreadcrumbs, [
            'Create' => null
        ]);

        return view('exams.create');
    }

    public function store(StoreExamRequest $request)
    {
        DB::beginTransaction();
        try {
            $exam = $this->examService->storeExam($request->only(['title', 'desc']));

            $this->skillService->storeSkillFromExam($exam->id, $request->input('skills'));

            DB::commit();

            return redirect()
                ->route('admin.exams.detail', $exam->id)
                ->with('success', 'Create exam success');
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    public function detail($id)
    {
        $this->breadcrumbs = array_merge($this->rootBreadcrumbs, [
            'Detail' => null
        ]);

        $exam = $this->examService->getExam($id);

        return view('exams.create', [
            'exam' => $exam,
        ]);
    }

    public function update($id, StoreExamRequest $request)
    {
        dd($request->all());
    }
}