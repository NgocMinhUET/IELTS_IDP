<?php

namespace App\Services\CMS;

use App\Repositories\Part\PartInterface;
use App\Repositories\QuestionOrder\QuestionOrderInterface;
use App\Services\BaseService;

/**
 * Class PartService.
 *
 * @package namespace App\Services\CMS;
 */
class PartService extends BaseService
{
    public function __construct(
        public PartInterface $partRepository,
        public QuestionOrderInterface $questionOrderRepository,
    ) {}

    public function getPart($id)
    {
        return $this->partRepository->find($id);
    }

    public function upsertPartFromSkill($skillId, $partPayload): void
    {
        $insertData = [];
        $exceptDeleteIds = [];
        $current = now();
        foreach ($partPayload as $part) {
            if (empty($part['id'])) {
                $insertData[] = [
                    'skill_id' => $skillId,
                    'title' => $part['title'],
                    'created_at' => $current,
                    'updated_at' => $current,
                ];
            } else {
                $exceptDeleteIds[] = $part['id'];
            }
        }

        if (count($exceptDeleteIds)) {
            $this->partRepository->deleteWhere([
                'id' => ['id', 'NOTIN', $exceptDeleteIds],
                'skill_id' => ['skill_id', '=', $skillId],
            ]);
        }

        if (count($insertData)) {
            $this->partRepository->insert($insertData);
        }
    }

    public function getAllQuestionOrdersOfPart($id)
    {
        return $this->questionOrderRepository->findWhere(['part_id' => $id])
            ->select('table', 'question_id')->toArray();
    }
}
