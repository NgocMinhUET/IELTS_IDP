<?php

namespace App\Models;

use App\Enum\AnswerType;
use App\Enum\Models\AnswerResult;
use App\Enum\QuestionTypeAPI;
use App\Models\Traits\HasQuestionOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LBlankContentQuestion extends Model
{
    use HasFactory;
    use HasQuestionOrder;

    protected $fillable = [
        'content_inherit',
        'content',
        'part_id',
        'title',
        'type',
        'answer_type',
        'answer_label',
        'score',
        'order'
    ];

    protected $appends = [
        'type', // detail question type for API
    ];

    const IS_CONTENT_INHERIT = true;
    const IS_CONTENT_NOT_INHERIT = false;

    public function answers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LBlankContentAnswer::class, 'question_id');
    }

    public function getTypeAttribute(): int
    {
        return $this->answer_type == AnswerType::FILL->value ? QuestionTypeAPI::FILL_CONTENT->value :
            QuestionTypeAPI::DRAG_DROP_CONTENT->value;
    }

    public function getContentWithSubmittedAnswer($questionAnswers, $submittedAnswers)
    {
        $questionContent = $this->content;

        // Parse input tags from HTML
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $questionContent);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);
        $inputTags = $xpath->query('//input[@data-blank-id]');

        foreach ($inputTags as $input) {
            $questionId = $input->getAttribute('data-blank-id');

            // answer submitted of this question
            $submittedAnswer = $submittedAnswers->where('question_id', $questionId)->first();
            $answerValue = '';
            $isCorrect = false;
            if ($submittedAnswer) {
                if ($submittedAnswer['question_type'] == QuestionTypeAPI::FILL_CONTENT->value) {
                    $answerValue = $submittedAnswer['answer'];
                } else if ($submittedAnswer['question_type'] == QuestionTypeAPI::DRAG_DROP_CONTENT->value) {
                    $mapQuestionAnswer = $questionAnswers->where('id', $submittedAnswer['answer'])->first();
                    if ($mapQuestionAnswer) {
                        $answerValue = $mapQuestionAnswer->answer;
                    }
                }

                if ($submittedAnswer['answer_result'] == AnswerResult::CORRECT->value) {
                    $isCorrect = true;
                }
            }

            $input->removeAttribute('placeholder');

            // Set value of the input
            $input->setAttribute('value', htmlspecialchars($answerValue));

            $style = $input->getAttribute('style');
            // Remove any existing border style
            $style = preg_replace('/border\s*:\s*[^;]+;?/i', '', $style);
            // Add border based on correctness
            $borderColor = $isCorrect ? 'green' : 'red';
            $style = trim($style . '; border: 3px solid ' . $borderColor . ';');
            // Normalize and clean style string
            $style = trim(preg_replace('/;;+/', ';', $style), "; \t\n\r");
            $input->setAttribute('style', $style . ';');
        }

        // Export HTML back to string
        $body = $dom->getElementsByTagName('body')->item(0);
        $newContent = '';
        foreach ($body->childNodes as $child) {
            $newContent .= $dom->saveHTML($child);
        }

        return mb_convert_encoding($newContent, 'HTML-ENTITIES', 'UTF-8');
    }
}
