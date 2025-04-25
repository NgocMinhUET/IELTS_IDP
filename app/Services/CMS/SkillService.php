<?php

namespace App\Services\CMS;

use App\Repositories\Skill\SkillInterface;
use App\Services\BaseService;

/**
 * Class SkillService.
 *
 * @package namespace App\Services\CMS;
 */
class SkillService extends BaseService
{
    public function __construct(
        public SkillInterface $skillRepository,
    ) {}

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

    public function getSkill($id)
    {
        return $this->skillRepository->with('parts')->find($id);
    }

    public function updateSkill($id, $payload)
    {
        return $this->skillRepository->update($payload, $id);
    }
}
