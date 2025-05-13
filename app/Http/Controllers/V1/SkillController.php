<?php

namespace App\Http\Controllers\V1;

use App\Common\ResponseApi;
use App\Enum\Models\SkillType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Question\GetQuestionAPIRequest;
use App\Services\API\ExamSessionService;
use App\Services\API\PartService;
use App\Services\API\SkillService;
use App\Services\API\SkillSessionService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SkillController extends Controller
{
    public function __construct(
        public SkillService $skillService,
        public PartService $partService,
        public ExamSessionService $examSessionService,
        public SkillSessionService $skillSessionService,
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

    public function getQuestions(GetQuestionAPIRequest $request)
    {
        $examSession = $this->examSessionService->validateExamSessionFromToken($request->input('exam_session_token'));
        $skillId = $request->input('target_id');
        $skill = $this->skillService->getSkill($skillId);
        if ($examSession->exam_id != $skill->exam_id) {
            throw new HttpException(403, 'You are not allowed to access this skill');
        }

        //TODO: check exam_session_token has skill_session_token

        $skillSession = $this->skillSessionService->makeSkillSessionService($examSession->id, $skill);

        $response = [];
        $response['skill_session_token'] = $skillSession->generateEncryptedToken();
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
