@extends('layouts.master')

@section('contents')
    <div class="mt-4">
        <div class="row g-4">
            <div class="col-12 col-xl-12 order-1 order-xl-0">
                <div class="mb-9">
                    <div class="card shadow-none border my-4" data-component-card="data-component-card">
                        <div class="card-header p-4 border-bottom bg-body">
                            <div class="row g-3 justify-content-between align-items-center">
                                <div class="col-12 col-md">
                                    <h4 class="text-body mb-0">
                                        Create Choice Question
                                    </h4>
                                </div>
                                <div class="col col-md-auto">

                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="p-4 code-to-copy">
                                <form action="{{ route('admin.parts.questions.store', $partId) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Main Question Content</label>
                                        <textarea name="title"
                                                  class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                                  rows="3" required>{{ old('title', '') }}</textarea>
                                        @if($errors->has('title'))
                                            <div class="invalid-feedback mt-0">{{ $errors->first('title') }}</div>
                                        @endif
                                    </div>

                                    <hr>

                                    <div id="sub-questions-wrapper"></div>
                                    <button type="button" class="btn btn-outline-primary" id="add-sub-question">+ Add Sub Question</button>
                                    @if($errors->has('choice_sub_questions'))
                                        <div class="invalid-feedback mt-0 d-block">{{ $errors->first('choice_sub_questions') }}</div>
                                    @endif
                                    <hr>

                                    <button type="submit" class="btn btn-success mt-3">Save Question Group</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        let subQuestionIndex = 0;

        function generateSubQuestionHtml(index) {
            return `
            <div class="card mb-4 sub-question" data-index="${index}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6>Sub Question ${index + 1}</h6>
                        <button type="button" class="btn btn-sm btn-danger remove-sub-question">×</button>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Question Content</label>
                        <textarea name="choice_sub_questions[${index}][question]" class="form-control" rows="2" required></textarea>
                    </div>

                    <div class="mb-2 d-flex gap-3">
                        <div>
                            <label class="form-label">Min Select</label>
                            <input type="number" class="form-control min-select" name="choice_sub_questions[${index}][min_option]" value="1" min="1">
                        </div>
                        <div>
                            <label class="form-label">Max Select</label>
                            <input type="number" class="form-control max-select" name="choice_sub_questions[${index}][max_option]" value="1" min="1">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Answers</label>
                        <div class="row g-2 answer-list" data-sub-index="${index}">
                            <!-- JavaScript add answer -->
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
                    <input type="text" name="choice_sub_questions[${subIndex}][choice_options][${ansIndex}][answer]" class="form-control" placeholder="Answer text" required>
                    <span class="input-group-text">
                        <input type="checkbox" class="correct-checkbox" name="choice_sub_questions[${subIndex}][choice_options][${ansIndex}][is_correct]" value="1">
                    </span>
                    <button type="button" class="btn btn-outline-danger remove-answer">×</button>
                </div>
            </div>
        `;
        }

        document.getElementById('add-sub-question').addEventListener('click', function () {
            const wrapper = document.getElementById('sub-questions-wrapper');
            const html = generateSubQuestionHtml(subQuestionIndex);
            wrapper.insertAdjacentHTML('beforeend', html);

            // Khởi tạo 2 đáp án mặc định
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

        // Hạn chế chọn vượt quá max_select
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
                    