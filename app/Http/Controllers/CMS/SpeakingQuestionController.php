<?php

namespace App\Http\Controllers\CMS;

use App\Http\Requests\Question\StoreSpeakingQuestionRequest;
use App\Services\CMS\SpeakingQuestionService;
use Illuminate\Support\Facades\DB;

class SpeakingQuestionController extends CMSController
{
    public function __construct(
        public SpeakingQuestionService $speakingQuestionService,
    ) {
    }

    public function store($partId, StoreSpeakingQuestionRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->speakingQuestionService->store($partId,
                $request->input('content'),
                $request->input('score'),
                $request->input('duration'),
            );

            DB::commit();

            return redirect()->route('admin.parts.detail', $partId)
                ->with('success', 'Create speaking question success');
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    public function detail($id, $questionId)
    {
    }
}