<?php

namespace App\Services\API;

use App\Repositories\SkillAnswer\SkillAnswerInterface;
use Illuminate\Http\Request;

class SkillAnswerService
{
    public function __construct(
        public SkillAnswerInterface $skillAnswerRepository,
    ) {}

    public function storeAnswerAndGetResult(Request $request)
    {

    }

    public function storeAnswer(Request $request)
    {

    }
}
