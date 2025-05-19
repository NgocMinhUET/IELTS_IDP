<?php

namespace App\Http\Controllers\V1;

use App\Common\ResponseApi;
use App\Http\Controllers\Controller;
use App\Http\Requests\Test\EnrollTestAPIRequest;
use App\Services\API\ExamSessionService;
use App\Services\API\TestService;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TestController extends Controller
{
    public function __construct(
        public TestService $testService,
        public ExamSessionService $examSessionService,
    ) {}

    public function getTests(): \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $tests = $this->testService->getAssignedToUserTests();

        return ResponseApi::success('', $tests);
    }

    /**
     * @throws \Throwable
     */
    public function enrollTest(EnrollTestAPIRequest $request): \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
    {
        DB::beginTransaction();
        try {
            $testID = $request->input('test_id');

            $testDetail = $this->testService->enrollTest($testID);

            DB::commit();

            return ResponseApi::success('', $testDetail);
        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }
    }

    public function getTestHistories()
    {
        $tests = $this->testService->getTestHistories();

        return ResponseApi::success('', $tests);
    }

    public function getDetailTestHistory($id)
    {
        $examSessions = $this->examSessionService->getExamSessionOfHistoryTest($id);

        if ($examSessions->count() == 0) {
            throw new HttpException(403, 'No exam session found for this test history');
        }

        return ResponseApi::success('', $this->testService->buildDetailTestHistoryResponse($examSessions));
    }
}
