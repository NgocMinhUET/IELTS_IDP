<?php

namespace App\Repositories\SpeakingQuestion;

use App\Models\SpeakingQuestion;
use App\Repositories\BaseRepository;

class SpeakingQuestionRepository extends BaseRepository implements SpeakingQuestionInterface
{
    public function model(): string
    {
        return SpeakingQuestion::class;
    }
}
