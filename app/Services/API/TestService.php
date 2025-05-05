<?php

namespace App\Services\API;

use App\Repositories\Exam\ExamInterface;
use App\Repositories\Skill\SkillInterface;
use App\Repositories\Test\TestInterface;

class TestService
{
    public function __construct(
        public TestInterface $testRepository,
        public ExamInterface $examRepository,
        public SkillInterface $skillRepository,
    ) {}

    public function getDetailTest($id): array
    {
        $test = $this->testRepository->find($id);
        $exam = $this->examRepository->find($test->exam_id);
        $skills = $this->skillRepository->findByField('exam_id', $test->exam_id);

        return [
            'desc' => $test->desc,
            'start_time' => $test->start_time,
            'end_time' => $test->end_time,
            'exam' => [
                'title' => $exam->title,
                'desc' => $exam->desc,
                'skills' => $skills->map(function ($skill) {
                    return [
                        'code' => $skill->code,
                        'type' => $skill->type->value,
                        'desc' => $skill->desc,
                        'duration' => $skill->duration,
                        'bonus_time' => $skill->bonus_time,
                    ];
                })
            ]
        ];
    }
}
