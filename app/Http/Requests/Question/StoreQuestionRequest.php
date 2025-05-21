<?php

namespace App\Http\Requests\Question;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreQuestionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:2048',
            'choice_sub_questions' => 'required|array',
            'choice_sub_questions.*.question' => 'required|string|max:2048',
            'choice_sub_questions.*.min_option' => 'required|numeric',
            'choice_sub_questions.*.max_option' => 'required|numeric',
            'choice_sub_questions.*.score' => 'required|numeric',
            'choice_sub_questions.*.choice_options' => 'required|array',
            'choice_sub_questions.*.choice_options.*.answer' => 'required|string|max:2048',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $subQuestions = $this->input('choice_sub_questions', []);

            foreach ($subQuestions as $index => $sub) {
                $min = intval($sub['min_option'] ?? 1);
                $max = intval($sub['max_option'] ?? 1);
                $choices = $sub['choice_options'] ?? [];
                $totalChoices = count($choices);

                if ($min > $max) {
                    $validator->errors()->add("choice_sub_questions.$index.min_option", "Min cannot be greater than Max.");
                    $validator->errors()->add("choice_sub_questions.$index.max_option", "Max must be greater than or equal to Min.");
                }

                if ($max > $totalChoices) {
                    $validator->errors()->add("choice_sub_questions.$index.max_option", "Max cannot exceed number of choices ($totalChoices).");
                }

                if ($min > $totalChoices) {
                    $validator->errors()->add("choice_sub_questions.$index.min_option", "Min cannot exceed number of choices ($totalChoices).");
                }

                // Optional: Ensure at least `min` answers are marked correct
                $correctCount = collect($choices)->where('is_correct', '1')->count();
                if ($correctCount < $min) {
                    $validator->errors()->add("choice_sub_questions.$index", "At least $min correct answer(s) required.");
                }
                if ($correctCount > $max) {
                    $validator->errors()->add("choice_sub_questions.$index", "Max $max correct answer(s) required.");
                }
            }
        });
    }
}