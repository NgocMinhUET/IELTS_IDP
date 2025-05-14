<?php

namespace App\Services\CMS;

use App\Repositories\LBlankContentQuestion\LBlankContentQuestionInterface;
use App\Repositories\Paragraph\ParagraphInterface;
use App\Services\BaseService;

class ParagraphService extends BaseService
{
    public function __construct(
        public ParagraphInterface $paragraphRepository,
        public LBlankContentQuestionInterface $lBlankContentQuestionRepository,
    ) {}

    /**
     * @throws \Exception
     */
    public function storeParagraph($partId, $paragraph)
    {
        $existedParagraph = $this->getParagraphByPartId($partId);

        if ($existedParagraph) {
            throw new \Exception('Existed paragraph');
        }

        return $this->paragraphRepository->create([
            'part_id' => $partId,
            'content' => $paragraph,
        ]);
    }

    public function getParagraphByPartId($partId)
    {
        return $this->paragraphRepository
            ->findByField('part_id', $partId)
            ->first();
    }

    public function getParagraphById($paragraphId)
    {
        return $this->paragraphRepository->find($paragraphId);
    }

    public function updateParagraph($paragraphId, $paragraph)
    {
        return $this->paragraphRepository->update(['content' => $paragraph], $paragraphId);
    }
}
