<?php

namespace App\Services\CMS;

use App\Repositories\WritingQuestion\WritingQuestionInterface;
use App\Services\BaseService;

class WritingQuestionService extends BaseService
{
    public function __construct(
        public WritingQuestionInterface $writingQuestionRepository,
    ) {}

    public function store($partId, $content, $score)
    {
        return $this->writingQuestionRepository->create([
            'part_id' => $partId,
            'content' => $content,
            'score' => $score,
        ]);
    }

    public function getWritingQuestionByPart($partId)
    {
        return $this->writingQuestionRepository
            ->findWhere(['part_id' => $partId]);
    }
}
