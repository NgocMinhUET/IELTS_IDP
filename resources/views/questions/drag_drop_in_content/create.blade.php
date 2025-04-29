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
                                        Create Drag Drop In Blank Question For {{ $part->title }} ( {{ $part->skill->type->label() }})
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="p-4 code-to-copy">
                                <form action="{{ route('admin.parts.fic-questions.store', $partId) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="answer_type" value="{{ \App\Enum\AnswerType::DRAG_DROP->value }}">
                                    <div class="mb-4">
                                        <label class="form-label">Question <span class="text-danger">*</span></label>
                                        <textarea name="title" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                                  rows="3" required>{{ old('title', '') }}</textarea>
                                        @if($errors->has('title'))
                                            <div class="invalid-feedback mt-0">{{ $errors->first('title') }}</div>
                                        @endif
                                    </div>

                                    <hr>

                                    <div class="mb-4">
                                        <label class="form-label">Question Content <span class="text-danger">*</span></label>
                                        <textarea id="editor" class="form-control" name="content"></textarea>
                                        @if($errors->has('content'))
                                            <div class="invalid-feedback mt-0 d-block">{{ $errors->first('content') }}</div>
                                        @endif

                                        @if($part->skill->type == \App\Enum\Models\SkillType::READING)
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" id="contentInherit" type="checkbox" name="content_inherit" value="1" />
                                                <label class="form-check-label" for="contentInherit">
                                                    Use this content as paragraphs of part
                                                    <span class="text-warning"> * Replace current paragraphs of part if exist * </span>
                                                </label>
                                            </div>
                                        @endif
                                    </div>

                                    <hr>

                                    <div id="answers-wrapper" class="mb-4">
                                        <label class="form-label">Correct Answers <span class="text-danger">*</span></label>
                                        @if($errors->has('answers'))
                                            <div class="invalid-feedback mt-0 d-block">{{ $errors->first('answers') }}</div>
                                        @endif
                                        <div id="answer-list"></div>
                                    </div>

                                    <hr>

                                    <div class="mb-4">
                                        <label class="form-label">Other Distractor Answers</label>
                                        @if($errors->has('distractor_answers'))
                                            <div class="invalid-feedback mt-0 d-block">{{ $errors->first('distractor_answers') }}</div>
                                        @endif
                                        <div class="row g-2 distractor-answer-list">
                                            <!-- JavaScript add answer -->
                                        </div>
                                        <button type="button" class="btn btn-outline-secondary mt-2 add-answer" data-sub-index="${index}">+ Add Answer</button>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">All Answers Label</label>
                                        <input name="answer_label" type="text"
                                               class="form-control {{ $errors->has('answer_label') ? 'is-invalid' : '' }}"
                                               placeholder=""
                                               value="{{ old('answer_label', '') }}"
                                        >
                                        @if($errors->has('answer_label'))
                                            <div class="invalid-feedback mt-0">{{ $errors->first('answer_label') }}</div>
                                        @endif
                                    </div>

                                    <button type="submit" class="btn btn-success">Save</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for adding blank -->
        <div class="modal fade" id="blankModal" tabindex="-1" aria-labelledby="blankModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="blankModalLabel">Add Blank</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Placeholder</label>
                            <input type="text" id="blank-placeholder" class="form-control" value="" placeholder="e.g. ____">
                        </div>
                        <div class="mb-3">
                            <label>Correct Answer</label>
                            <input type="text" id="blank-answer" class="form-control" placeholder="Enter correct answer">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="insert-blank">Insert Blank</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('build/vendors/tinymce/tinymce.min.js') }}"></script>
    <script>
        let blankIndex = 1;

        function renderAnswerInput(index, placeholder, answer) {
            return `
                <div class="input-group mb-2 answer-item" data-index="${index}">
                    <span class="input-group-text">Answer of ${placeholder}</span>
                    <input type="hidden" name="placeholders[${index}]" class="form-control" value="${placeholder}" required>
                    <input type="text" name="answers[${index}]" class="form-control" value="${answer}" required>
                    <button type="button" class="btn btn-outline-danger remove-blank" data-blank-id="${index}">×</button>
                </div>
            `;
        }

        tinymce.init({
            selector: '#editor',
            plugins: 'code',
            toolbar: 'bold italic underline | addBlankBtn',
            setup: function (editor) {
                editor.ui.registry.addButton('addBlankBtn', {
                    text: '+ Add Blank',
                    onAction: function () {
                        const modal = new bootstrap.Modal(document.getElementById('blankModal'));
                        modal.show();
                    }
                });

                // Remove blank input in editor and answer list
                document.addEventListener('click', function (e) {
                    if (e.target.classList.contains('remove-blank')) {
                        const id = e.target.dataset.blankId;
                        const inputEl = editor.getBody().querySelector(`input[data-blank-id="${id}"]`);
                        if (inputEl) inputEl.remove();
                        e.target.closest('.answer-item').remove();
                    }
                });

                document.getElementById('insert-blank').addEventListener('click', function () {
                    const placeholder = document.getElementById('blank-placeholder').value || '____';
                    const answer = document.getElementById('blank-answer').value;
                    const modalEl = bootstrap.Modal.getInstance(document.getElementById('blankModal'));

                    if (answer.trim() !== '') {
                        const inputHtml = `<input type="text" class="blank-fill" placeholder="${placeholder}" data-blank-id="${blankIndex}" readonly>`;
                        editor.insertContent(inputHtml);

                        document.getElementById('answer-list').insertAdjacentHTML('beforeend', renderAnswerInput(blankIndex, placeholder, answer));
                        blankIndex++;
                        modalEl.hide();
                        document.getElementById('blank-placeholder').value = '';
                        document.getElementById('blank-answer').value = '';
                    }
                });
            },
            height: 300
        });

        function generateAnswerHtml() {
            return `
            <div class="col-md-6 answer-item">
                <div class="input-group">
                    <input type="text" name="distractor_answers[]" class="form-control" placeholder="Answer text" required>
                    <button type="button" class="btn btn-outline-danger remove-answer">×</button>
                </div>
            </div>
        `;
        }

        // event add distractor answers
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('add-answer')) {
                const answerList = document.querySelector(`.distractor-answer-list`);
                answerList.insertAdjacentHTML('beforeend', generateAnswerHtml());
            }

            if (e.target.classList.contains('remove-answer')) {
                e.target.closest('.answer-item').remove();
            }
        });
    </script>
@endsection
