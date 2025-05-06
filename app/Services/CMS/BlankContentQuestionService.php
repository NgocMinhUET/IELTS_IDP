<?php

namespace App\Services\CMS;

use App\Repositories\LBlankContentAnswer\LBlankContentAnswerInterface;
use App\Repositories\LBlankContentQuestion\LBlankContentQuestionInterface;
use App\Services\BaseService;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Dạng câu hỏi điền/drag drop vào blank input trong content html
*/
class BlankContentQuestionService extends BaseService
{
    public function __construct(
        public LBlankContentQuestionInterface $lBlankContentQuestionRepository,
        public LBlankContentAnswerInterface $lBlankContentAnswerRepository
    ) {}

    public function validateContentAndAnswer($content, $answers, $placeholders): array
    {
        // Parse input tags from HTML
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $content);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);
        $inputTags = $xpath->query('//input[@data-blank-id]');

        if ($inputTags->length !== count($answers) || array_keys($answers) !== array_keys($placeholders)) {
            throw new BadRequestHttpException('Incorrect answers found');
        }

        return compact('dom', 'inputTags');
    }

    public function replaceBlankInputId($domTag, $answers, $placeholders): array
    {
        $dom = $domTag['dom'];
        $inputTags = $domTag['inputTags'];

        foreach ($inputTags as $input) {
            $oldAnswerId = $input->getAttribute('data-blank-id');
            $newAnswerId = $oldAnswerId . uniqid();
            $input->setAttribute('data-blank-id', $newAnswerId);

            $answers[$newAnswerId] = $answers[$oldAnswerId];
            $placeholders[$newAnswerId] = $placeholders[$oldAnswerId];
            unset($answers[$oldAnswerId]);
            unset($placeholders[$oldAnswerId]);
        }

        // Export HTML back to string
        $body = $dom->getElementsByTagName('body')->item(0);
        $newContent = '';
        foreach ($body->childNodes as $child) {
            $newContent .= $dom->saveHTML($child);
        }
        $newContent = mb_convert_encoding($newContent, 'HTML-ENTITIES', 'UTF-8');

        return [$newContent, $answers, $placeholders];
    }

    public function storeFillInBlankContentQuestion($partId, $questionPayload)
    {
        $questionPayload['part_id'] = $partId;

        return $this->lBlankContentQuestionRepository->create($questionPayload);
    }

    public function storeFillInBlankContentAnswers($questionId, $answers, $placeholders)
    {
        $insertData = [];
        $current = now();
        foreach ($answers as $key => $answer) {
            $insertData[] = [
                'question_id' => $questionId,
                'input_identify' => $key,
                'answer' => $answer,
                'placeholder' => $placeholders[$key],
                'created_at' => $current,
                'updated_at' => $current,
            ];
        }

        return $this->lBlankContentAnswerRepository->insert($insertData);
    }

    public function storeFillInBlankContentDistractorAnswers($questionId, $distractorAnswers)
    {
        $insertData = [];
        $current = now();
        foreach ($distractorAnswers as $answer) {
            $insertData[] = [
                'question_id' => $questionId,
                'answer' => $answer,
                'created_at' => $current,
                'updated_at' => $current,
            ];
        }

        return $this->lBlankContentAnswerRepository->insert($insertData);
    }

    public function getFillInBlankContentQuestionByPart($partId)
    {
        return $this->lBlankContentQuestionRepository->with('answers')
            ->findWhere(['part_id' => $partId]);
    }

    public function isHavingQuestionInherit($partId)
    {
        return $this->lBlankContentQuestionRepository->isHavingQuestionInherit($partId);
    }

    public function unsetExistedContentInheritQuestion($partId)
    {
        return $this->lBlankContentQuestionRepository->unsetExistedContentInheritQuestion($partId);
    }
}
