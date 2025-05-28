<?php

namespace App\Services\CMS;

use App\Enum\Models\AnswerResult;
use App\Repositories\SkillAnswer\SkillAnswerInterface;
use App\Services\BaseService;

class SkillAnswerService extends BaseService
{
    public function __construct(
        public SkillAnswerInterface $skillAnswerRepository,
    ) {

    }

    public function getSkillAnswerById($skillAnswerId)
    {
        return $this->skillAnswerRepository->find($skillAnswerId);
    }

    public function updateSkillAnswerScore($skillAnswerId, $score, $isPendingAnswer = false)
    {
        $updateAttributes = ['score' => $score];

        if ($isPendingAnswer) {
            $updateAttributes['answer_result'] = AnswerResult::GRADED;
        }

        return $this->skillAnswerRepository->update($updateAttributes, $skillAnswerId);
    }
}