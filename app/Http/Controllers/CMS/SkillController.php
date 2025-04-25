<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\Skill\UpdateSkillRequest;
use App\Services\CMS\PartService;
use App\Services\CMS\SkillService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SkillController extends Controller
{
    public function __construct(
        public SkillService $skillService,
        public PartService $partService,
    ) {
    }

    public function detail($id)
    {
        $skill = $this->skillService->getSkill($id);

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
            dd($th);
        }
    }
}