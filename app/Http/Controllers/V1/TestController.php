<?php

namespace App\Http\Controllers\V1;

use App\Common\ResponseApi;
use App\Http\Controllers\Controller;
use App\Services\API\TestService;

class TestController extends Controller
{
    public function __construct(
        public TestService $testService,
    ) {}

    public function getDetailTest($id): \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $testDetail = $this->testService->getDetailTest($id);

        return ResponseApi::success('', $testDetail);
    }

    public function enrollTest($id)
    {

    }
}
