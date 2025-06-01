<?php

namespace App\Services\CMS;

use App\Repositories\SpeakingQuestion\SpeakingQuestionInterface;
use App\Services\BaseService;

class SpeakingQuestionService extends BaseService
{
    public function __construct(
        public SpeakingQuestionInterface $speakingQuestionRepository,
    ) {}

    public function store($partId, $content, $score, $duration)
    {
        return $this->speakingQuestionRepository->create([
            'part_id' => $partId,
            'content' => $content,
            'score' => $score,
            'duration' => $duration,
        ]);
    }

    public function getSpeakingQuestionByPart($partId)
    {
        return $this->speakingQuestionRepository
            ->findWhere(['part_id' => $partId]);
    }
}
