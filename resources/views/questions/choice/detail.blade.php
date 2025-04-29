@extends('layouts.master')

@section('contents')
    <div class="mt-4">
        <div class="row g-4">
            <form action="" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Main Question Content</label>
                    <textarea name="question[content]" class="form-control" rows="3" required>{{ old('question.content', $question->title) }}</textarea>
                </div>

                <hr>

                <div id="sub-questions-wrapper">
                    @foreach ($question->choiceSubQuestions as $index => $sub)
                        <div class="card mb-4 sub-question" data-index="{{ $index }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6>Sub Question {{ $index + 1 }}</h6>
                                    <button type="button" class="btn btn-sm btn-danger remove-sub-question">&times;</button>
                                </div>

                                <div class="mb-2">
                                    <label>Question Content</label>
                                    <textarea name="question[sub_questions][{{ $index }}][content]" class="form-control" rows="2" required>{{ $sub->question }}</textarea>
                                </div>

                                <div class="mb-2 d-flex gap-3">
                                    <div>
                                        <label>Min Select</label>
                                        <input type="number" class="form-control min-select" name="question[sub_questions][{{ $index }}][min_select]" value="{{ $sub->min_option }}" min="1">
                                    </div>
                                    <div>
                                        <label>Max Select</label>
                                        <input type="number" class="form-control max-select" name="question[sub_questions][{{ $index }}][max_select]" value="{{ $sub->max_option }}" min="1">
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label>Answers</label>
                                    <div class="row g-2 answer-list" data-sub-index="{{ $index }}">
                                        @foreach ($sub->choiceOptions as $ansIndex => $answer)
                                            <div class="col-md-6 answer-item">
                                                <div class="input-group">
                                                    <input type="text" name="question[sub_questions][{{ $index }}][answers][{{ $ansIndex }}][text]" class="form-control" value="{{ $answer->answer }}" required>
                                                    <span class="input-group-text">
                                            <input type="checkbox" class="correct-checkbox" name="question[sub_questions][{{ $index }}][answers][{{ $ansIndex }}][is_correct]" value="1" {{ $answer->is_correct ? 'checked' : '' }}>
                                        </span>
                                                    <button type="button" class="btn btn-outline-danger remove-answer">&times;</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-outline-secondary mt-2 add-answer" data-sub-index="{{ $index }}">+ Add Answer</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-outline-primary" id="add-sub-question">+ Add Sub Question</button>

                <hr>

                <button type="submit" class="btn btn-success mt-3">Update Question Group</button>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        let subQuestionIndex = {{ count($question->choiceSubQuestions) }};

        function generateSubQuestionHtml(index) {
            return `
            <div class="card mb-4 sub-question" data-index="${index}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6>Sub Question ${index + 1}</h6>
                        <button type="button" class="btn btn-sm btn-danger remove-sub-question">&times;</button>
                    </div>

                    <div class="mb-2">
                        <label>Question Content</label>
                        <textarea name="question[sub_questions][${index}][content]" class="form-control" rows="2" required></textarea>
                    </div>

                    <div class="mb-2 d-flex gap-3">
                        <div>
                            <label>Min Select</label>
                            <input type="number" class="form-control min-select" name="question[sub_questions][${index}][min_select]" value="1" min="1">
                        </div>
                        <div>
                            <label>Max Select</label>
                            <input type="number" class="form-control max-select" name="question[sub_questions][${index}][max_select]" value="1" min="1">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label>Answers</label>
                        <div class="row g-2 answer-list" data-sub-index="${index}">
                            <!-- Answers here -->
                        </div>
                        <button type="button" class="btn btn-outline-secondary mt-2 add-answer" data-sub-index="${index}">+ Add Answer</button>
                    </div>
                </div>
            </div>
        `;
        }

        function generateAnswerHtml(subIndex, ansIndex) {
            return `
            <div class="col-md-6 answer-item">
                <div class="input-group">
                    <input type="text" name="question[sub_questions][${subIndex}][answers][${ansIndex}][text]" class="form-control" placeholder="Answer text" required>
                    <span class="input-group-text">
                        <input type="checkbox" class="correct-checkbox" name="question[sub_questions][${subIndex}][answers][${ansIndex}][is_correct]" value="1">
                    </span>
                    <button type="button" class="btn btn-outline-danger remove-answer">&times;</button>
                </div>
            </div>
        `;
        }

        document.getElementById('add-sub-question').addEventListener('click', function () {
            const wrapper = document.getElementById('sub-questions-wrapper');
            const html = generateSubQuestionHtml(subQuestionIndex);
            wrapper.insertAdjacentHTML('beforeend', html);

            const answerList = wrapper.querySelector(`[data-sub-index="${subQuestionIndex}"]`);
            answerList.insertAdjacentHTML('beforeend', generateAnswerHtml(subQuestionIndex, 0));
            answerList.insertAdjacentHTML('beforeend', generateAnswerHtml(subQuestionIndex, 1));
            subQuestionIndex++;
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-sub-question')) {
                e.target.closest('.sub-question').remove();
            }

            if (e.target.classList.contains('add-answer')) {
                const subIndex = e.target.dataset.subIndex;
                const answerList = document.querySelector(`.answer-list[data-sub-index="${subIndex}"]`);
                const ansIndex = answerList.querySelectorAll('.answer-item').length;
                answerList.insertAdjacentHTML('beforeend', generateAnswerHtml(subIndex, ansIndex));
            }

            if (e.target.classList.contains('remove-answer')) {
                e.target.closest('.answer-item').remove();
            }
        });

        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('correct-checkbox')) {
                const subQuestion = e.target.closest('.sub-question');
                const max = parseInt(subQuestion.querySelector('.max-select').value);
                const checkboxes = subQuestion.querySelectorAll('.correct-checkbox');
                const checkedCount = [...checkboxes].filter(cb => cb.checked).length;

                if (checkedCount > max) {
                    e.target.checked = false;
                    alert(`You can select up to ${max} correct answer(s).`);
                }
            }
        });
    </script>
@endsection