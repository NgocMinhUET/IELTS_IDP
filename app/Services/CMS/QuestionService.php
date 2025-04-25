<?php

namespace App\Services\CMS;

use App\Repositories\ChoiceOption\ChoiceOptionInterface;
use App\Repositories\ChoiceQuestion\ChoiceQuestionInterface;
use App\Repositories\ChoiceSubQuestion\ChoiceSubQuestionInterface;
use App\Services\BaseService;

/**
 * Class QuestionService.
 *
 * @package namespace App\Services\CMS;
 */
class QuestionService extends BaseService
{
    public function __construct(
        public ChoiceQuestionInterface $choiceQuestionRepository,
        public ChoiceSubQuestionInterface $choiceSubQuestionRepository,
        public ChoiceOptionInterface $choiceOptionRepository,
    ) {}

    public function storeChoiceQuestionFromPart($partId, $payload)
    {
        $payload['part_id'] = $partId;

        return $this->choiceQuestionRepository->create($payload);
    }

    /**
     * @throws \Exception
     */
    public function storeSubQuestionFromPart($choiceQuestionId, $choiceSubQuestionPayload): void
    {
        foreach ($choiceSubQuestionPayload as $choiceSubQuestion) {
            $this->storeChoiceSubQuestionAndAnswer($choiceQuestionId, $choiceSubQuestion);
        }
    }

    /**
     * @throws \Exception
     */
    public function storeChoiceSubQuestionAndAnswer($choiceQuestionId, $choiceOptionPayload): void
    {
        $current = now();
        $storeSubQuestionData = [
            'choice_question_id' => $choiceQuestionId,
            'question' => $choiceOptionPayload['question'],
            'min_option' => $choiceOptionPayload['min_option'] ?? null,
            'max_option' => $choiceOptionPayload['max_option'] ?? null,
        ];

        $choiceOptions = $choiceOptionPayload['choice_options'] ?? [];

        if (empty($choiceOptions)) throw new \Exception('Choice options is required');

        // create subquestion
        $choiceSubQuestion = $this->storeChoiceSubQuestion($storeSubQuestionData);

        // insert choice options of subquestion
        $insertOptionData = [];
        foreach ($choiceOptions as $choiceOption) {
            $insertOptionData[] = [
                'choice_sub_question_id' => $choiceSubQuestion->id,
                'answer' => $choiceOption['answer'],
                'is_correct' => !empty($choiceOption['is_correct']),
                'created_at' => $current,
                'updated_at' => $current,
            ];
        }

        $this->insertChoiceOptions($insertOptionData);
    }

    public function storeChoiceSubQuestion($choiceSubQuestionData)
    {
        return $this->choiceSubQuestionRepository->create($choiceSubQuestionData);
    }

    public function insertChoiceOptions($insertOptionData)
    {
        return $this->choiceOptionRepository->insert($insertOptionData);
    }

    public function getChoiceQuestionByPart($partId)
    {
        return $this->choiceQuestionRepository->with('choiceSubQuestions.choiceOptions')->findWhere(['part_id' => $partId]);
    }

    public function getChoiceQuestionById($id)
    {
        return $this->choiceQuestionRepository->with('choiceSubQuestions.choiceOptions')->find($id);
    }
}
