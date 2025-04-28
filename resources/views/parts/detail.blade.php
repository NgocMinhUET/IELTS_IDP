@extends('layouts.master')

@section('css')
    .hover-shadow:hover {
        box-shadow: 0 0 10px rgba(0,0,0,0.12) !important;
        transform: translateY(-2px);
    }
    .transition {
        transition: all 0.2s ease-in-out;
    }
@endsection

@section('contents')
    <div class="mt-4">
        <div class="row g-4">
            <div class="col-12 col-xl-10 order-1 order-xl-0">
                <div class="accordion" id="accordionExample">
                    @foreach($allQuestions as $key => $question)
                        @if ($question instanceof \App\Models\ChoiceQuestion)
                            <div class="accordion-item" id="Q_{{$key}}">
                                <h2 class="accordion-header" id="heading_{{$key}}">

                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{$key}}" aria-expanded="false" aria-controls="collapse_{{$key}}">
                                        {{ $question->title }}
                                    </button>
                                </h2>
                                <div class="accordion-collapse collapse" id="collapse_{{$key}}" aria-labelledby="heading_{{$key}}" data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body pt-0">
                                        @foreach ($question->choiceSubQuestions as $index => $sub)
                                            <div class="container py-4">
                                                <div class="card mb-4">
                                                    <div class="card-body">
                                                        <div class="mb-2 d-flex justify-content-between align-items-center">
                                                            <h6 class="mb-0">{{ $sub->question }}</h6>
                                                            <small class="text-muted"> MIN: {{ $sub->min_option }} MAX: {{ $sub->max_option }}</small>
                                                        </div>
                                                        <div class="row g-2 mt-2">
                                                            @foreach ($sub->choiceOptions as $i => $answer)
                                                                <div class="col-md-6">
                                                                    <div class="form-check border rounded p-3 d-flex align-items-start gap-2">
                                                                        <input
                                                                                class="form-check-input"
                                                                                type="{{ $sub->max_option > 1 ? 'checkbox' : 'radio' }}"
                                                                                name="sub_question_{{ $index }}[]"
                                                                                value="{{ $answer->id }}"
                                                                                id="answer_{{ $sub->id }}_{{ $i }}"
                                                                                disabled
                                                                                {{ $answer->is_correct ? 'checked' : '' }}
                                                                        >
                                                                        <label class="form-check-label w-100" for="answer_{{ $sub->id }}_{{ $i }}">
                                                                            {{ $answer->answer }}
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
                            @endphp
                            <div class="accordion-item" id="Q_{{$key}}">
                                <h2 class="accordion-header" id="heading_2_{{ $key }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse_2_{{ $key }}" aria-expanded="false" aria-controls="collapse_2_{{ $key }}">
                                        {{ $question->title ?? 'Fill in the Blank Question ' . ($key + 1) }}
                                    </button>
                                </h2>
                                <div id="collapse_2_{{ $key }}" class="accordion-collapse collapse"
                                     aria-labelledby="heading_{{ $key }}" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="container py-4">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <h6>Question:</h6>
                                                        <div class="card pt-4 px-2">
                                                            {!! $question->content !!}
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <h6>Correct Answers:</h6>
                                                        @foreach ($correctAnswers as $answer)
                                                            <div class="input-group mb-2">
                                                                <span class="input-group-text">Answer of {{ $answer->placeholder }}</span>
                                                                <input type="text" class="form-control" value="{{ $answer->answer }}" disabled>
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

                                                        <div class="mb-3">
                                                            <h6>All Answers Label</h6>
                                                            <input name="answer_label" type="text" class="form-control" value="{{ $question->answer_label }}" disabled>
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
                            @endphp
                            <div class="accordion-item" id="Q_{{$key}}">
                                <h2 class="accordion-header" id="heading_3_{{ $key }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse_3_{{ $key }}" aria-expanded="false" aria-controls="collapse_3_{{ $key }}">
                                        {{ $question->title ?? 'Fill in the Blank Question ' . ($key + 1) }}
                                    </button>
                                </h2>
                                <div id="collapse_3_{{ $key }}" class="accordion-collapse collapse"
                                     aria-labelledby="heading_{{ $key }}" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="container py-4">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <h6>Question:</h6>
                                                        <div class="card pt-4 px-2">
                                                            {!! $question->content !!}
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <h6>Correct Answers:</h6>
                                                        @foreach ($correctAnswers as $answer)
                                                            <div class="input-group mb-2">
                                                                <span class="input-group-text">Answer of {{ $answer->placeholder }}</span>
                                                                <input type="text" class="form-control" value="{{ $answer->answer }}" disabled>
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

                                                        <div class="mb-3">
                                                            <h6>All Answers Label</h6>
                                                            <input name="answer_label" type="text" class="form-control" value="{{ $question->answer_label }}" disabled>
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

                    <div class="accordion-item">
                        @component('components.question_types', ['part' => $part])
                        @endcomponent
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-2">
                <div class="position-sticky mt-xl-4" style="top: 80px;">
                    <h5 class="lh-1">List of questions </h5>
                    <hr />
                    <ul class="nav nav-vertical flex-column doc-nav" data-doc-nav="data-doc-nav">
                        @foreach($allQuestions as $key => $question)
                        <li class="nav-item">
                            <a class="nav-link" href="#Q_{{$key}}">
                                {{ $question->title }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

@endsection
                    