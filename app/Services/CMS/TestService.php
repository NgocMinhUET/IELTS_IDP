<?php

namespace App\Services\CMS;

use App\Repositories\Test\TestInterface;
use App\Services\BaseService;

class TestService extends BaseService
{
    public function __construct(
        public TestInterface $testRepository,
    ) {}

    public function getPaginateTests()
    {
        return $this->testRepository->with('exam')->paginate(10);
    }

    public function getTest($id)
    {
        return $this->testRepository->with('exam')->find($id);
    }

    public function storeTest($testPayload)
    {
        return $this->testRepository->create($testPayload);
    }

    public function updateTest($id, $payload)
    {
        return $this->testRepository->update($payload, $id);
    }
}
