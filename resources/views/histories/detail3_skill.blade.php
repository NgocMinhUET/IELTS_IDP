@extends('layouts.master')

@section('css')
    .incorrect-border {
        border: 1px solid red!important;
    }
    .correct-border {
        border: 1px solid green!important;
    }

    .editor-parent {
        display: flex;
        width: 100%;
        height: calc(100vh - 200px);
        position: relative;
        overflow: hidden;
    }

    .box {
        height: 100%;
        overflow: auto;
        padding: 10px;
    }

    .box-paragraph {
        /*background-color: #f9f9f9;*/
    }

    .editor-resizer {
        width: 5px;
        background-color: #ccc;
        cursor: col-resize;
        z-index: 10;
    }

    .nav.nav-underline .nav-link.active {
        font-weight: bold;
    }
@endsection

@php
    $firstPartId = array_keys($skillQuestionsByPart)[0] ?? 0;
@endphp
@section('contents')
    <div class="mt-4" id="tests">
        <x-spinner></x-spinner>

        READING

        <div class="p-4">
            <div class="sticky-top bg-white z-index-fixed" style="top: 64px;">
                <ul class="nav nav-underline fs-9 d-flex w-100 justify-content-between border-bottom" id="partTab" role="tablist">
                    @foreach($skillQuestionsByPart as $key => $part)
                        <li class="nav-item flex-fill text-center">
                            <a class="nav-link {{ $firstPartId == $key ? 'active' : '' }}" id="part-{{ $key }}-tab" data-bs-toggle="tab" href="#tab-part-{{ $key }}"
                               role="tab" aria-controls="tab-part-{{ $key }}" aria-selected="true">Part {{ $part['part']->title }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="tab-content mt-3" id="partTabContent">
                @foreach($skillQuestionsByPart as $key => $part)
                    <div class="tab-pane fade  {{ $firstPartId == $key ? 'show active' : '' }}" id="tab-part-{{ $key }}"
                         role="tabpanel" aria-labelledby="part-{{ $key }}-tab"
                    >
                        <div class="editor-parent" id="editor-parent-{{ $key }}">
                            @php
                                $paragraph = $part['part']->paragraph;
                                $paragraphContent = $paragraph ? $paragraph->content : '';
                                $questions = $part['questions'];

                                $inheritQuestion = null;
                                foreach($questions as $question) {
                                    if ($question instanceof \App\Models\LBlankContentQuestion) {
                                        if ($question->content_inherit == 1) {
                                            $inheritQuestion = $question;
                                            break;
                                        }
                                    }
                                }
                                if ($inheritQuestion) {
                                    $questionType = $inheritQuestion->answer_type == \App\Enum\AnswerType::FILL->value ?
                                                \App\Enum\QuestionTypeAPI::FILL_CONTENT->value : \App\Enum\QuestionTypeAPI::DRAG_DROP_CONTENT->value;

                                    $relatedAnswers = $skillAnswers->where('question_model', (new \App\Models\LBlankContentAnswer())->getTable())
                                        ->where('question_type', $questionType);

                                    $paragraphContent = $inheritQuestion->getContentWithSubmittedAnswer($inheritQuestion->answers, $relatedAnswers);
                                }
                            @endphp
                            <div class="box box-paragraph" id="left-box-{{ $key }}" style="width: 45%">
                                {!! $paragraphContent !!}
                            </div>

                            <div class="editor-resizer" id="editor-resizer-{{ $key }}"></div>

                            <div class="box box-answer" id="right-box-{{ $key }}" style="width: 55%">
                                @foreach($questions as $question)
                                    @if ($question instanceof \App\Models\ChoiceQuestion)
                                        <div class="accordion-item" id="Q_{{$key}}">
                                            <h2 class="accordion-header" id="heading_{{$key}}">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{$key}}" aria-expanded="false" aria-controls="collapse_{{$key}}">
                                                    {{ $question->title }}
                                                </button>
                                            </h2>
                                            <div class="accordion-collapse collapse show" id="collapse_{{$key}}" aria-labelledby="heading_{{$key}}" data-bs-parent="#accordionExample" style="">
                                                <div class="accordion-body pt-0">
                                                    @foreach ($question->choiceSubQuestions as $index => $sub)
                                                        @php
                                                            $questionModel = $sub->getTable();
                                                            $answer = $skillAnswers->where('question_model', $questionModel)
                                                                ->where('question_id', $sub->id)
                                                                ->first();
                                                            $isAnswerCorrect = !is_null($answer) && $answer['answer_result'] == \App\Enum\Models\AnswerResult::CORRECT->value;
                                                        @endphp
                                                        <div class="container py-4">
                                                            <div class="card mb-4">
                                                                <div class="card-body">
                                                                    <div class="mb-2 d-flex justify-content-between align-items-center">
                                                                        <h6 class="mb-0">{{ $sub->question }}</h6>
                                                                        <small class="text-gray">
                                                                            <span style="display: inline-block; border-bottom: 1px solid green; width: 40px;"></span>
                                                                            <span style="display: inline-block; border-bottom: 1px solid red; width: 40px;"></span>
                                                                            &nbsp;Sumitted Answer&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
                                                                            <b class="text-{{ ($isAnswerCorrect) ? 'success' : 'danger' }}">
                                                                                {!! $isAnswerCorrect ? '<i class="fa-solid fa-check"></i>' :
                                                                                '<i class="fa-solid fa-xmark"></i>' !!}
                                                                                SCORE: {{ $sub->score ?? 'NOT SET' }}
                                                                            </b>
                                                                        </small>
                                                                    </div>
                                                                    <div class="row g-2 mt-2">
                                                                        @foreach ($sub->choiceOptions as $i => $choiceOption)
                                                                            @php
                                                                                $isStudentSelected = !is_null($answer) && in_array($choiceOption->id, $answer['answer']);
                                                                            @endphp
                                                                            <div class="col-md-6">
                                                                                <div class="form-check border rounded
                                                                                    {{ (!$isAnswerCorrect && $isStudentSelected) ? 'incorrect-border' : '' }}
                                                                                    {{ ($isAnswerCorrect && $isStudentSelected) ? 'correct-border' : '' }}
                                                                                    p-3 d-flex align-items-start gap-2"
                                                                                >
                                                                                    <input
                                                                                            class="form-check-input"
                                                                                            type="{{ $sub->max_option > 1 ? 'checkbox' : 'radio' }}"
                                                                                            name="sub_question_{{ $sub->id }}[]"
                                                                                            value="{{ $choiceOption->id }}"
                                                                                            id="answer_{{ $sub->id }}_{{ $i }}"
                                                                                            disabled
                                                                                            {{ $choiceOption->is_correct ? 'checked' : '' }}
                                                                                    >
                                                                                    <label class="form-check-label w-100" for="answer_{{ $sub->id }}_{{ $i }}">
                                                                                        {{ $choiceOption->answer }}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($question instanceof \App\Models\LBlankContentQuestion)
                                        @php
                                            $isDragDropQuestion = $question->answer_type == \App\Enum\AnswerType::DRAG_DROP->value;
                                            $allAnswers = $question->answers;
                                            $correctAnswers = $allAnswers->whereNotNull('input_identify');
                                            $distractorAnswers = $allAnswers->whereNull('input_identify');

                                            $contentWithSubmittedAnswer = '';
                                            $isContentInherit = true;
                                            if (!$question->content_inherit) {
                                                $questionType = $question->answer_type == \App\Enum\AnswerType::FILL->value ?
                                                    \App\Enum\QuestionTypeAPI::FILL_CONTENT->value : \App\Enum\QuestionTypeAPI::DRAG_DROP_CONTENT->value;

                                                $relatedAnswers = $skillAnswers->where('question_model', (new \App\Models\LBlankContentAnswer())->getTable())
                                                    ->where('question_type', $questionType);

                                                $contentWithSubmittedAnswer = $question->getContentWithSubmittedAnswer($question->answers, $relatedAnswers);
                                                $isContentInherit = false;
                                            }
                                        @endphp
                                        <div class="accordion-item" id="Q_{{$key}}">
                                            <h2 class="accordion-header" id="heading_2_{{ $key }}">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapse_2_{{ $key }}" aria-expanded="false" aria-controls="collapse_2_{{ $key }}">
                                                    {{ $question->title ?? 'Fill in the Blank Question ' . ($key + 1) }}
                                                </button>
                                            </h2>
                                            <div id="collapse_2_{{ $key }}" class="accordion-collapse collapse show"
                                                 aria-labelledby="heading_{{ $key }}" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="container py-4">
                                                        <div class="card mb-4">
                                                            <div class="card-body">
                                                                <div class="mb-3">
                                                                    <h6>Question: <b class="text-info">{{ $inheritQuestion ? 'Is part paragraph' : ''}}</b></h6>
                                                                    @if(!$isContentInherit)
                                                                        <div class="card pt-4 px-2">
                                                                            {!! $contentWithSubmittedAnswer !!}
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                                <div class="mb-3">
                                                                    <h6>Correct Answers:</h6>
                                                                    @foreach ($correctAnswers as $answer)
                                                                        <div class="input-group mb-2">
                                                                            <span class="input-group-text">Answer of {{ $answer->placeholder }}</span>
                                                                            <input type="text" class="form-control" value="{{ $answer->answer }}" disabled>
                                                                            <span class="input-group-text">Score</span>
                                                                            <input type="number" class="form-control" value="{{ $answer->score ?: 'NOT SET' }}" disabled>
                                                                        </div>
                                                                    @endforeach
                                                                </div>

                                                                @if($isDragDropQuestion)
                                                                    <div class="mb-3">
                                                                        <h6>Other Distractor Answers</h6>
                                                                        <div class="row g-2">
                                                                            @foreach($distractorAnswers as $answer)
                                                                                <div class="col-md-6">
                                                                                    <input type="text" class="form-control" value="{{ $answer->answer }}" disabled>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($question instanceof \App\Models\BlankImageQuestion)
                                        @php
                                            $isDragDropQuestion = $question->answer_type == \App\Enum\AnswerType::DRAG_DROP->value;
                                            $allAnswers = $question->answers;
                                            $correctAnswers = $allAnswers->whereNotNull('input_identify');
                                            $distractorAnswers = $allAnswers->whereNull('input_identify');

                                            $questionType = $question->answer_type == \App\Enum\AnswerType::FILL ?
                                                \App\Enum\QuestionTypeAPI::FILL_IMAGE->value : \App\Enum\QuestionTypeAPI::DRAG_DROP_IMAGE->value;

                                            $relatedAnswers = $skillAnswers->where('question_model', (new \App\Models\BlankImageAnswer())->getTable())
                                                ->where('question_type', $questionType);

                                            $contentWithSubmittedAnswer = $question->getContentWithSubmittedAnswer($question->answers, $relatedAnswers);
                                        @endphp
                                        <div class="accordion-item" id="Q_{{$key}}">
                                            <h2 class="accordion-header" id="heading_3_{{ $key }}">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapse_3_{{ $key }}" aria-expanded="false" aria-controls="collapse_3_{{ $key }}">
                                                    {{ $question->title ?? 'Fill in the Blank Question ' . ($key + 1) }}
                                                </button>
                                            </h2>
                                            <div id="collapse_3_{{ $key }}" class="accordion-collapse collapse show"
                                                 aria-labelledby="heading_{{ $key }}" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="container py-4">
                                                        <div class="card mb-4">
                                                            <div class="card-body">
                                                                <div class="mb-3">
                                                                    <h6>Question:</h6>
                                                                    <div class="card pt-4 px-2">
                                                                        {!! $contentWithSubmittedAnswer !!}
                                                                    </div>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <h6>Correct Answers:</h6>
                                                                    @foreach ($correctAnswers as $answer)
                                                                        <div class="input-group mb-2">
                                                                            <span class="input-group-text">Answer of {{ $answer->placeholder }}</span>
                                                                            <input type="text" class="form-control" value="{{ $answer->answer }}" disabled>
                                                                            <span class="input-group-text">Score</span>
                                                                            <input type="number" class="form-control" value="{{ $answer->score ?: 'NOT SET' }}" disabled>
                                                                        </div>
                                                                    @endforeach
                                                                </div>

                                                                @if($isDragDropQuestion)
                                                                    <div class="mb-3">
                                                                        <h6>Other Distractor Answers</h6>
                                                                        <div class="row g-2">
                                                                            @foreach($distractorAnswers as $answer)
                                                                                <div class="col-md-6">
                                                                                    <input type="text" class="form-control" value="{{ $answer->answer }}" disabled>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.querySelectorAll('#partTab a[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function () {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });

        @foreach($skillQuestionsByPart as $key => $part)
            const resizer{{ $key }} = document.getElementById('editor-resizer-{{ $key }}');
            const leftBox{{ $key }} = document.getElementById('left-box-{{ $key }}');
            const rightBox{{ $key }} = document.getElementById('right-box-{{ $key }}');

            let isResizing{{ $key }} = false;

            resizer{{ $key }}.addEventListener('mousedown', function (e) {
                isResizing{{ $key }} = true;
            });

            document.addEventListener('mousemove', function (e) {
                if (!isResizing{{ $key }}) return;

                const containerWidth = resizer{{ $key }}.parentElement.getBoundingClientRect().width;
                let leftWidth = e.clientX - resizer{{ $key }}.parentElement.getBoundingClientRect().left;
                leftWidth = Math.max(100, Math.min(leftWidth, containerWidth - 100)); // min 100px
                leftBox{{ $key }}.style.width = leftWidth + 'px';
                rightBox{{ $key }}.style.width = (containerWidth - leftWidth - 5) + 'px'; // 5px for resizer
            });

            document.addEventListener('mouseup', function () {
                isResizing{{ $key }} = false;
            });
        @endforeach
    </script>
@endsection