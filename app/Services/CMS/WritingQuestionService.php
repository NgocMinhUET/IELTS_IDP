<?php

namespace App\Services\CMS;

use App\Repositories\WritingQuestion\WritingQuestionInterface;
use App\Services\BaseService;

class WritingQuestionService extends BaseService
{
    public function __construct(
        public WritingQuestionInterface $writingQuestionInterface,
    ) {}

    public function store($partId, $content)
    {
        return $this->writingQuestionInterface->create([
            'part_id' => $partId,
            'content' => $content,
        ]);
    }

    public function getWritingQuestionByPart($partId)
    {
        return $this->writingQuestionInterface
            ->findWhere(['part_id' => $partId]);
    }
}
