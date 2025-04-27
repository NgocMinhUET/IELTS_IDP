<?php

namespace App\Http\Controllers\CMS;

use App\Http\Requests\Skill\UpdateSkillRequest;
use App\Services\CMS\PartService;
use App\Services\CMS\SkillService;
use Illuminate\Support\Facades\DB;

class SkillController extends CMSController
{
    private array $rootBreadcrumbs = [];

    public function __construct(
        public SkillService $skillService,
        public PartService $partService,
    ) {
    }

    public function detail($id)
    {
        $skill = $this->skillService->getSkill($id);

        $this->breadcrumbs = [
            'Exam' => route('admin.exams.detail', ['id' => $skill->exam_id]),
            'Skill' => null,
            'Detail' => null,
        ];

        return view('skills.detail', [
            'skill' => $skill,
        ]);
    }

    public function update($id, UpdateSkillRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->skillService->updateSkill($id, $request->only(['desc', 'duration', 'bonus_time']));
            $this->partService->upsertPartFromSkill($id, $request->input('parts'));

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Update skill success');
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}