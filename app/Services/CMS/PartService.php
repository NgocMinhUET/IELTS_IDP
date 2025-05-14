<?php

namespace App\Services\CMS;

use App\Enum\Models\SkillType;
use App\Repositories\LBlankContentQuestion\LBlankContentQuestionInterface;
use App\Repositories\Paragraph\ParagraphInterface;
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
        public ParagraphInterface $paragraphRepository,
        public LBlankContentQuestionInterface $lBlankContentQuestionRepository,
    ) {}

    public function getPart($id)
    {
        return $this->partRepository->with('skill')->findOrFail($id);
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

    public function getAllQuestionOrdersOfPart($id, SkillType $skillType)
    {
        return $this->questionOrderRepository->getAllQuestionOrdersOfPart($id,  $skillType)
            ->select('table', 'question_id')->toArray();
    }

    public function updateOrCreateReadingParagraph($partId, $paragraph)
    {
        $this->lBlankContentQuestionRepository->unsetExistedContentInheritQuestion($partId);

        return $this->paragraphRepository->updateOrCreate(['part_id' => $partId], ['content' => $paragraph]);
    }

    public function updatePartDesc($partId, $desc)
    {
        return $this->partRepository->update(['desc' => $desc], $partId);
    }
}
