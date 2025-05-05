<?php

namespace App\Http\Controllers\V1;

use App\Common\ResponseApi;
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

    public function getQuestions(Request $request)
    {
        // skill id
        if (!$request->has('target_id')) {
            return ResponseApi::bad();
        }

        $skillId = $request->input('target_id');
        $skill = $this->skillService->getSkill($skillId);

        // get audio if skill is listening

        $response = [];
        $response['duration'] = $skill->duration;
        $parts = $this->partService->getQuestionsOfSkill($skill);
        $response['parts'] = $parts;

        return ResponseApi::success('', $response);
    }
}
