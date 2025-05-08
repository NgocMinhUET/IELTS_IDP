<?php

namespace App\Http\Controllers\CMS;

use App\Enum\Models\SkillType;
use App\Http\Requests\Skill\UpdateSkillRequest;
use App\Services\CMS\PartService;
use App\Services\CMS\SkillService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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

    /**
     * @throws ValidationException
     */
    public function update($id, UpdateSkillRequest $request)
    {
        $skill = $this->otherRequestValidate($id, $request);

        DB::beginTransaction();
        try {
            $this->skillService->updateSkill($id, $request->only(['desc', 'duration', 'bonus_time']));

            $parts = $request->input('parts');
            if (!empty($parts)) {
                $this->partService->upsertPartFromSkill($id, $request->input('parts'));
            }

            if ($skill->type == SkillType::LISTENING) {
                $this->skillService->storeListeningSkillAudioFile($skill, $request->audio);
            }

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Update skill success');
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
        }
    }

    /**
     * @throws ValidationException
     */
    public function otherRequestValidate($id, Request $request)
    {
        $skill = $this->skillService->getSkill($id);

        if ($skill->type == SkillType::LISTENING) {
            if (!$request->audio instanceof UploadedFile) {
                $validator = Validator::make([], []);
                $validator->errors()->add('audio', 'Audio file is required for this skill');

                throw new ValidationException($validator);
            }
        }

        return $skill;
    }
}