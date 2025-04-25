<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\Exam\StoreExamRequest;
use App\Services\CMS\ExamService;
use App\Services\CMS\SkillService;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    public function __construct(
        public ExamService $examService,
        public SkillService $skillService,
    ) {
    }

    public function index()
    {
        return view('exams.index');
    }

    public function create()
    {
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