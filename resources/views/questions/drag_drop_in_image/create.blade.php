@extends('layouts.master')

@section('contents')
    <div class="mt-4">
        <div class="row g-4">
            <div class="col-12">
                <div class="card shadow-none border my-4">
                    <div class="card-header p-4 border-bottom bg-body">
                        <h4 class="text-body mb-0">
                            Create Drag Drop In Image Question For Part {{ $part->title }} ( {{ $part->skill->type->label() }})
                        </h4>
                    </div>
                    <div class="card-body p-4">

                        <form action="{{ route('admin.parts.fii-questions.store', $partId) }}" id="di-question-form" method="POST" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="answer_type" value="{{ \App\Enum\AnswerType::DRAG_DROP->value }}">
                            <div class="mb-3">
                                <label class="form-label">Question <span class="text-danger">*</span></label>
                                <textarea name="title"
                                          class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                          rows="3" required></textarea>
                                @if($errors->has('title'))
                                    <div class="invalid-feedback mt-0">{{ $errors->first('title') }}</div>
                                @endif
                            </div>

                            {{-- Image Upload --}}
                            <div class="mb-3">
                                <label for="image-upload" class="form-label">Upload Image <span class="text-danger">*</span></label>
                                <input type="file" class="form-control {{ $errors->has('image') ? 'is-invalid' : '' }}"
                                       id="image-upload" name="image" accept="image/*" required>
                                @if($errors->has('image'))
                                    <div class="invalid-feedback mt-0">{{ $errors->first('image') }}</div>
                                @endif
                                <input type="hidden" id="original-width" name="width">
                                <input type="hidden" id="original-height" name="height">
                            </div>

                            {{-- Image Preview --}}
                            <div id="image-container" class="position-relative mb-4" style="display:none;">
                                <img id="uploaded-image" src="" alt="Uploaded" class="img-fluid" style="max-width: 100%;">
                            </div>

                            {{-- List Answers --}}
                            <div id="answers-wrapper" class="mb-4" style="display: {{ $errors->has('answers') ? 'block' : 'none' }};">
{{--                                <h6>Correct Answers <span class="text-danger">*</span></h6>--}}
                                <label class="form-label">Correct Answers <span class="text-danger">*</span></label>
                                @if($errors->has('answers'))
                                    <div class="invalid-feedback mt-0 d-block">{{ $errors->first('answers') }}</div>
                                @endif
                                <div id="answer-list"></div>
                                <hr>
                            </div>


                            <div id="distractor_answers-wrapper" class="mb-4" style="display: {{ $errors->has('distractor_answers') ? 'block' : 'none' }};">
                                <label class="form-label">Other Distractor Answers</label>
                                @if($errors->has('distractor_answers'))
                                    <div class="invalid-feedback mt-0 d-block">{{ $errors->first('distractor_answers') }}</div>
                                @endif
                                <div class="row g-2 distractor-answer-list">
                                    <!-- JavaScript add answer -->
                                </div>
                                <button type="button" class="btn btn-outline-secondary mt-2 add-distractor-answer" data-sub-index="${index}">+ Add Answer</button>
                                <hr>
                            </div>


                            <div id="answer_label-wrapper" class="mb-4" style="display: {{ $errors->has('answer_label') ? 'block' : 'none' }};">
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

                        <div class="modal fade" id="blankModal" tabindex="-1" aria-labelledby="blankModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form id="modal-form">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="blankModalLabel">Add Blank</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Placeholder</label>
                                                <input type="text" class="form-control" id="placeholder" placeholder="e.g. 1" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Correct Answer</label>
                                                <input type="text" class="form-control" id="answer" placeholder="Enter correct answer" required>
                                                <div class="invalid-feedback" id="answer-error" style="display: none;"></div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Score</label>
                                                <input type="number" value="1" min="1" id="score" class="form-control">
                                            </div>
                                            <input type="hidden" id="pos-x">
                                            <input type="hidden" id="pos-y">
                                            <input type="hidden" id="pos-w">
                                            <input type="hidden" id="pos-h">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary" id="insert-blank">Insert Blank</button>
                                        </div>
                                    </form>
                                </div>
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
        let blankIndex = 0;

        function normalizeAnswer(text) {
            return text.trim().toLowerCase();
        }

        function isDuplicateAnswer(newAnswer) {
            const allAnswers = [];

            // Get current correct answers
            document.querySelectorAll('#answer-list input[name^="answers["][name$="[answer]"]').forEach(input => {
                allAnswers.push(normalizeAnswer(input.value));
            });

            // Get current distractor answers
            document.querySelectorAll('input[name="distractor_answers[]"]').forEach(input => {
                allAnswers.push(normalizeAnswer(input.value));
            });

            console.log(allAnswers);

            return allAnswers.includes(normalizeAnswer(newAnswer));
        }

        // Upload image
        document.getElementById('image-upload').addEventListener('change', function (e) {
            const reader = new FileReader();
            reader.onload = function (event) {
                document.getElementById('uploaded-image').src = event.target.result;
                document.getElementById('image-container').style.display = 'block';
                document.getElementById('answers-wrapper').style.display = 'block';
                document.getElementById('distractor_answers-wrapper').style.display = 'block';
                document.getElementById('answer_label-wrapper').style.display = 'block';

                const uploadImage =  document.getElementById('uploaded-image');
                uploadImage.onload = function () {
                    const rect = uploadImage.getBoundingClientRect();
                    document.getElementById('original-width').value = rect.width;
                    document.getElementById('original-height').value = rect.height;
                };

            };
            reader.readAsDataURL(e.target.files[0]);
        });

        // Click Image Event
        document.getElementById('uploaded-image').addEventListener('click', function (e) {
            if (isDragging) {
                // Nếu vừa kéo xong, bỏ qua click
                e.stopPropagation();
                e.preventDefault();
                return;
            }

            const rect = this.getBoundingClientRect();
            console.log(rect, e)
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const w = rect.width;
            const h = rect.height;

            document.getElementById('pos-x').value = x;
            document.getElementById('pos-y').value = y;
            document.getElementById('pos-w').value = w;
            document.getElementById('pos-h').value = h;

            const modal = new bootstrap.Modal(document.getElementById('blankModal'));
            modal.show();
        });

        // Submit Modal
        document.getElementById('modal-form').addEventListener('submit', function (e) {
            e.preventDefault();

            const placeholder = document.getElementById('placeholder').value;
            const answer = document.getElementById('answer').value;
            const score = document.getElementById('score').value;
            const x = document.getElementById('pos-x').value;
            const y = document.getElementById('pos-y').value;
            const w = document.getElementById('pos-w').value;
            const h = document.getElementById('pos-h').value;

            let hasError = false;
            document.getElementById('answer-error').style.display = 'none';
            if (isDuplicateAnswer(answer)) {
                document.getElementById('answer-error').textContent = `Answer already exists.`;
                document.getElementById('answer-error').style.display = 'block';
                hasError = true;
            }

            if (!answer) {
                document.getElementById('answer-error').textContent = `Answer cannot be empty.`;
                document.getElementById('answer-error').style.display = 'block';
                hasError = true;
            }

            if (hasError) return;

            const imageContainer = document.getElementById('image-container');

            // Add input to image
            const input = document.createElement('input');
            input.type = 'text';
            input.placeholder = placeholder;
            input.dataset.id = blankIndex;
            input.className = 'position-absolute blank-input';
            input.style.left = `${x}px`;
            input.style.top = `${y}px`;
            // input.style.transform = 'translate(-50%, -50%)';
            input.style.width = '120px';
            input.style.border = '2px dashed #c5c5c5';
            input.style.borderRadius = '5px';
            input.style.textAlign = 'center';
            input.setAttribute('readonly', true);
            imageContainer.appendChild(input);
            makeInputDraggable(input);

            // Add answer preview with remove button
            const answerHtml = `
            <div class="input-group mb-2 answer-item" data-index="${blankIndex}">
                <span class="input-group-text">Answer for ${placeholder}</span>
                <input type="text" name="answers[${blankIndex}][answer]" value="${answer}" class="form-control answer-input" required>
                <span class="input-group-text">Score</span>
                <input type="number" name="answers[${blankIndex}][score]" class="form-control" value="${score}" min="1" required>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeBlank(${blankIndex})">x</button>
                <div class="invalid-feedback text-danger answer-error" style="display:none"></div>
            </div>
            <input type="hidden" name="answers[${blankIndex}][x]" value="${x}">
            <input type="hidden" name="answers[${blankIndex}][y]" value="${y}">
            <input type="hidden" name="answers[${blankIndex}][w]" value="${w}">
            <input type="hidden" name="answers[${blankIndex}][h]" value="${h}">
            <input type="hidden" name="answers[${blankIndex}][placeholder]" value="${placeholder}">
        `;
            document.getElementById('answer-list').insertAdjacentHTML('beforeend', answerHtml);

            blankIndex++;

            const modal = bootstrap.Modal.getInstance(document.getElementById('blankModal'));
            modal.hide();
            this.reset();
        });

        function removeBlank(index) {
            // Remove input in the image
            const input = document.querySelector(`.blank-input[data-id="${index}"]`);
            if (input) {
                input.remove();
            }

            // Remove answer preview
            const answerItem = document.querySelector(`#answer-list div[data-index="${index}"]`);
            if (answerItem) {
                answerItem.nextElementSibling.remove(); // remove hidden input x
                answerItem.nextElementSibling.remove(); // remove hidden input y
                answerItem.nextElementSibling.remove(); // remove hidden input w
                answerItem.nextElementSibling.remove(); // remove hidden input h
                answerItem.nextElementSibling.remove(); // remove hidden input placeholder
                answerItem.remove(); // remove input-group
            }
        }

        function generateAnswerHtml() {
            return `
            <div class="col-md-6 answer-item">
                <div class="input-group">
                    <input type="text" name="distractor_answers[]" class="form-control answer-input" placeholder="Answer text" required>
                    <button type="button" class="btn btn-outline-danger remove-answer">×</button>
                    <div class="invalid-feedback text-danger answer-error" style="display:none"></div>
                </div>
            </div>
        `;
        }

        // add distractor answers event
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('add-distractor-answer')) {
                const answerList = document.querySelector(`.distractor-answer-list`);
                answerList.insertAdjacentHTML('beforeend', generateAnswerHtml());
            }

            if (e.target.classList.contains('remove-answer')) {
                e.target.closest('.answer-item').remove();
            }
        });

        let isDragging = false; // global flag

        function makeInputDraggable(input) {
            let offsetX, offsetY;
            const image = document.getElementById('uploaded-image');

            input.addEventListener('mousedown', function (e) {
                isDragging = false; // reset flag
                offsetX = e.offsetX;
                offsetY = e.offsetY;

                function onMouseMove(ev) {
                    isDragging = true;
                    const rect = image.getBoundingClientRect();

                    let x = ev.clientX - rect.left - offsetX;
                    let y = ev.clientY - rect.top - offsetY;

                    // Drag/drop in image zone
                    const maxX = image.offsetWidth - input.offsetWidth;
                    const maxY = image.offsetHeight - input.offsetHeight;

                    x = Math.max(0, Math.min(x, maxX));
                    y = Math.max(0, Math.min(y, maxY));

                    input.style.left = `${x}px`;
                    input.style.top = `${y}px`;

                    // New position applies
                    const index = input.dataset.id;
                    document.querySelector(`input[name="answers[${index}][x]"]`).value = x;
                    document.querySelector(`input[name="answers[${index}][y]"]`).value = y;
                }

                function onMouseUp() {
                    document.removeEventListener('mousemove', onMouseMove);
                    document.removeEventListener('mouseup', onMouseUp);

                    setTimeout(() => { isDragging = false; }, 100);
                }

                document.addEventListener('mousemove', onMouseMove);
                document.addEventListener('mouseup', onMouseUp);
            });

            input.style.cursor = 'grab';
        }

        document.addEventListener('input', function (e) {
            const target = e.target;
            if (target.classList.contains('answer-input')) {
                const item = target.closest('.answer-item');
                const currentIndex = item.dataset.index;

                const currentAnswer = item.querySelector('.answer-input').value.trim();

                const allAnswers = [...document.querySelectorAll('.answer-input')]
                    .filter(input => input.closest('.answer-item').dataset.index !== currentIndex)
                    .map(input => input.value.trim().toLowerCase());

                const answerError = item.querySelector('.answer-error');

                // Reset errors
                answerError.style.display = 'none';

                if (allAnswers.includes(currentAnswer.toLowerCase())) {
                    answerError.textContent = `Answer already exists.`;
                    answerError.style.display = 'block';
                }
            }
        });

        document.getElementById('di-question-form').addEventListener('submit', function (e) {
            let hasError = false;
            const answerInputs = [...document.querySelectorAll('.answer-input')];

            const answerValues = {};

            answerInputs.forEach(input => {
                const value = input.value.trim().toLowerCase();
                const container = input.closest('.answer-item');
                const errorEl = container.querySelector('.answer-error');

                errorEl.style.display = 'none';
                if (answerValues[value]) {
                    // errorEl.textContent = `Duplicate answer "${input.value}"`;
                    errorEl.textContent = `Answer already exists.`;
                    errorEl.style.display = 'block';
                    hasError = true;
                } else {
                    answerValues[value] = true;
                }
            });

            if (hasError) {
                e.preventDefault();
            }
        });
    </script>
@endsection