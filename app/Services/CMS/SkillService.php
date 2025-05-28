<?php

namespace App\Services\CMS;

use App\Http\Controllers\CMS\Traits\QuestionUtil;
use App\Models\Skill;
use App\Repositories\Skill\SkillInterface;
use App\Services\BaseService;
use Illuminate\Http\UploadedFile;

/**
 * Class SkillService.
 *
 * @package namespace App\Services\CMS;
 */
class SkillService extends BaseService
{
    use QuestionUtil;

    public string $storageDisk = 'local';
    public function __construct(
        public SkillInterface $skillRepository,
    ) {
        //TODO: open there if s3 config is set
//        $this->storageDisk = config('filesystems.default');
    }

    public function storeSkillFromExam($examId, $skillTypes)
    {
        $current = now();
        $insertData = array_map(function ($skillType) use ($examId, $current) {
            return [
                'exam_id' => $examId,
                'type' => $skillType,
                'created_at' => $current,
                'updated_at' => $current,
            ];
        }, $skillTypes);

        return $this->skillRepository->insert($insertData);
    }

    public function updateSkillFromExam($examId, $skillTypes)
    {
        $selectedSkills = $this->getSkillsByExam($examId)->map(function ($skill) {
            return $skill->type->value;
        })->toArray();

        $removeSkills = array_diff($selectedSkills, $skillTypes);
        $addSkills = array_diff($skillTypes, $selectedSkills);

        if (count($addSkills)) {
            $this->storeSkillFromExam($examId, array_values($addSkills));
        }

        if (count($removeSkills)) {
            $this->skillRepository->deleteWhere([
                'exam_id' => $examId,
                'type' => ['type', 'IN', array_values($removeSkills)],
            ]);
        }
    }

    public function getSkill($id)
    {
        return $this->skillRepository->findOrFail($id);
    }

    public function updateSkill($id, $payload)
    {
        return $this->skillRepository->update($payload, $id);
    }

    public function getSkillsByExam($examId)
    {
        return $this->skillRepository->findWhere(['exam_id' => $examId]);
    }

    public function storeListeningSkillAudioFile(Skill $skill, UploadedFile $audioFile): \Illuminate\Database\Eloquent\Model
    {
        return $skill->addMedia($audioFile, $this->storageDisk);
    }

    public function updateListeningSkillAudioFile(Skill $skill, UploadedFile $audioFile): \Illuminate\Database\Eloquent\Model
    {
        return $skill->updateMedia($audioFile, $this->storageDisk, 'public');
    }

    public function getAllQuestionsBySkillId($skillId)
    {
        $skill = $this->getSkill($skillId);

        $questions = [];

        $parts = $skill->parts;

        foreach ($parts as $part) {
            $questions[$part->id]['part'] = $part;
            $questions[$part->id]['questions'] = $this->getAllOrderedQuestionsOfPart($part->id, $skill->type);
        }

        return $questions;
    }
}