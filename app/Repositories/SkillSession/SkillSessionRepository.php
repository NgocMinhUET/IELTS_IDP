<?php

namespace App\Repositories\SkillSession;

use App\Models\SkillSession;
use App\Repositories\BaseRepository;

class SkillSessionRepository extends BaseRepository implements SkillSessionInterface
{
    public function model(): string
    {
        return SkillSession::class;
    }

    public function firstOrCreateSkillSession($uniquePair, $attributes)
    {
        return $this->model->firstOrCreate($uniquePair, $attributes);
    }

    public function updateSkillSessionAfterChangeScore($id, $scoreDiff, $isPendingAnswer)
    {
        $skillSession = $this->find($id);

        $skillSession->increment('total_correct_score', $scoreDiff);

        if ($isPendingAnswer) {
            $skillSession->decrement('total_pending_answer');
        }

        $skillSession->save();
    }
}