<?php

namespace App\Http\Controllers\V1;

use App\Common\ResponseApi;
use App\Enum\Models\SkillType;
use App\Http\Controllers\Controller;
use App\Services\API\PartService;
use App\Services\API\SkillService;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function __construct(
        public SkillService $skillService,
        public PartService $partService
    ) {}

    public function getSkillForExam(Request $request)
    {
        // skill id
        if (!$request->has('exam_id')) {
            return ResponseApi::bad();
        }

        $examId = $request->input('exam_id');
        return ResponseApi::success('', $this->skillService->getSkillByExam($examId));
    }

    public function getQuestions(Request $request)
    {
        // skill id
        if (!$request->has('target_id')) {
            return ResponseApi::bad();
        }

        $skillId = $request->input('target_id');
        $skill = $this->skillService->getSkill($skillId);

        $response = [];
        $response['skill_type'] = $skill->type->value;
        $response['skill_label'] = $skill->type->name ?? '';
        $response['skill_desc'] = $skill->desc ?? '';
        $response['duration'] = $skill->duration;
        $response['audio'] = '';
        $parts = $this->partService->getQuestionsOfSkill($skill);
        $response['parts'] = $parts;

        // get audio if skill is listening
        if ($skill->type == SkillType::LISTENING) {
            $response['audio'] = $skill->getFirstMediaUrl() ?? '';
        }


        return ResponseApi::success('', $response);
    }
}
