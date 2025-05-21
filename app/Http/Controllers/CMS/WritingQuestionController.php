<?php

namespace App\Http\Controllers\CMS;

use App\Http\Requests\Question\StoreWritingQuestionRequest;
use App\Services\CMS\WritingQuestionService;
use Illuminate\Support\Facades\DB;

class WritingQuestionController extends CMSController
{
    public function __construct(
        public WritingQuestionService $writingQuestionService,
    ) {
    }

    public function store($partId, StoreWritingQuestionRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->writingQuestionService->store($partId, $request->input('content'),  $request->input('score'));

            DB::commit();

            return redirect()->route('admin.parts.detail', $partId)
                ->with('success', 'Create writing question success');
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    public function detail($id, $questionId)
    {
    }
}