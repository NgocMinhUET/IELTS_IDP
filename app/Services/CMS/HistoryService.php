<?php

namespace App\Services\CMS;

use App\Repositories\Test\TestInterface;
use App\Services\BaseService;

class HistoryService extends BaseService
{
    public function __construct(
        public TestInterface $testRepository,
    ) {}

    public function getPaginateHistoryTests()
    {
        return $this->testRepository->getPaginateHistoryTests();
    }

    public function getDetailHistoryTest($testId)
    {
        return $this->testRepository->getDetailHistoryTest($testId);
    }
}
