<?php

namespace App\Repositories\QuestionOrder;

use App\Enum\Models\SkillType;
use App\Models\QuestionOrder;
use App\Models\WritingQuestion;
use App\Repositories\BaseRepository;

class QuestionOrderRepository extends BaseRepository implements QuestionOrderInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return QuestionOrder::class;
    }

    public function getAllQuestionOrdersOfPart($partId, SkillType $skillType)
    {
        $query = $this->model->where(['part_id' => $partId]);

        switch ($skillType) {
            case SkillType::WRITING:
                $query->where('table', (new WritingQuestion())->getTable());
                break;
            default:
                $query->where('table', '!=', (new WritingQuestion())->getTable());
                break;
        }

        return $query->get();
    }
}
