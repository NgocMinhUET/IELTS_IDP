<?php

namespace App\Http\Controllers\CMS;

use App\Enum\Models\SkillType;
use App\Http\Requests\Part\StoreParagraphRequest;
use App\Services\CMS\BlankContentQuestionService;
use App\Services\CMS\ParagraphService;
use App\Services\CMS\PartService;
use Illuminate\Support\Facades\DB;

class ParagraphController extends CMSController
{
    private array $rootBreadcrumbs = ['Exam' => null, 'Skill' => null];

    public function __construct(
        public PartService $partService,
        public ParagraphService $paragraphService,
        public BlankContentQuestionService $blankContentQuestionService,
    ) {
    }

    public function create($partId)
    {
        $part = $this->partService->getPart($partId);

        $this->breadcrumbs = array_merge($this->rootBreadcrumbs, [
            'Part' => route('admin.parts.detail', $partId),
            'Paragraph' => null,
            'Create' => null,
        ]);

        return view('paragraphs.create', compact('part'));
    }

    /**
     * @throws \Exception
     */
    public function edit($partId, $paragraphId)
    {
        $paragraph = $this->paragraphService->getParagraphById($paragraphId);

        if ($paragraph->part_id != $partId) abort(404);

        $part = $this->validatePart($partId);

        // Is having question inheriting this content
        $isParagraphInherit = $this->blankContentQuestionService->isHavingQuestionInherit($partId);

        $this->breadcrumbs = array_merge($this->rootBreadcrumbs, [
            'Part' => route('admin.parts.detail', $partId),
            'Paragraph' => null,
            'Edit' => null,
        ]);

        return view('paragraphs.create', compact('paragraph', 'part', 'isParagraphInherit'));
    }

    public function store(StoreParagraphRequest $request, $partId): \Illuminate\Http\RedirectResponse
    {
        DB::beginTransaction();
        try {
            $this->validatePart($partId);

            $this->paragraphService->storeParagraph($partId, $request->input('content'));

            DB::commit();

            return redirect()
                ->route('admin.parts.detail', $partId)
                ->with('success', 'Create paragraph success');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('danger', $th->getMessage() ?? 'Create paragraph failed');
        }
    }

    public function update(StoreParagraphRequest $request, $partId, $paragraphId)
    {
        DB::beginTransaction();
        try {
            $paragraph = $this->paragraphService->getParagraphById($paragraphId);

            if ($paragraph->part_id != $partId) abort(404);

            $this->validatePart($partId);

            $this->paragraphService->updateParagraph($paragraphId, $request->input('content'));
            $this->blankContentQuestionService->unsetExistedContentInheritQuestion($partId);

            DB::commit();

            return redirect()
                ->route('admin.parts.detail', $partId)
                ->with('success', 'Update paragraph success');
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
        }
    }

    private function validatePart($partId)
    {
        $part = $this->partService->getPart($partId);
        if ($part->skill->type !== SkillType::READING) {
            throw new \Exception('Paragraph can only be created for reading skill');
        }

        return $part;
    }
}