@extends('layouts.master')

@section('contents')
    <div class="mt-4">
        <div class="row g-4">
            <div class="col-12">
                <div class="card shadow-none border my-4">
                    <div class="card-header p-4 border-bottom bg-body">
                        <h4 class="text-body mb-0">Create Image Map Fill Question</h4>
                    </div>
                    <div class="card-body p-4">

                        <form action="{{ route('admin.parts.fii-questions.store', $partId) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="answer_type" value="{{ \App\Enum\AnswerType::FILL }}">
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
                                <h6>Answer for Blanks <span class="text-danger">*</span></h6>
                                @if($errors->has('answers'))
                                    <div class="invalid-feedback mt-0 d-block">{{ $errors->first('answers') }}</div>
                                @endif
                                <div id="answer-list"></div>
                            </div>

                            <button type="submit" class="btn btn-success">Save</button>
                        </form>

                        {{-- Modal nhập Placeholder và Đáp án --}}
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

        // Upload ảnh
        document.getElementById('image-upload').addEventListener('change', function (e) {
            const reader = new FileReader();
            reader.onload = function (event) {
                document.getElementById('uploaded-image').src = event.target.result;
                document.getElementById('image-container').style.display = 'block';
                document.getElementById('answers-wrapper').style.display = 'block';

                const uploadImage =  document.getElementById('uploaded-image');
                uploadImage.onload = function () {
                    const rect = uploadImage.getBoundingClientRect();
                    document.getElementById('original-width').value = rect.width;
                    document.getElementById('original-height').value = rect.height;
                };

            };
            reader.readAsDataURL(e.target.files[0]);


        });

        // Click vào ảnh
        document.getElementById('uploaded-image').addEventListener('click', function (e) {
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
            const x = document.getElementById('pos-x').value;
            const y = document.getElementById('pos-y').value;
            const w = document.getElementById('pos-w').value;
            const h = document.getElementById('pos-h').value;

            const imageContainer = document.getElementById('image-container');

            // Thêm input vào ảnh
            const input = document.createElement('input');
            input.type = 'text';
            input.placeholder = placeholder;
            input.dataset.id = blankIndex;
            input.className = 'position-absolute blank-input';
            input.style.left = `${x}px`;
            input.style.top = `${y}px`;
            input.style.transform = 'translate(-50%, -50%)';
            input.style.width = '120px';
            input.setAttribute('readonly', true);
            imageContainer.appendChild(input);

            // Thêm dòng answer có nút xóa
            const answerHtml = `
            <div class="input-group mb-2" data-index="${blankIndex}">
                <span class="input-group-text">Answer for ${placeholder}</span>
                <input type="text" name="answers[${blankIndex}][answer]" value="${answer}" class="form-control" required>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeBlank(${blankIndex})">x</button>
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

        // Hàm xóa blank
        function removeBlank(index) {
            // Xóa input trên ảnh
            const input = document.querySelector(`.blank-input[data-id="${index}"]`);
            if (input) {
                input.remove();
            }

            // Xóa answer dưới list
            const answerItem = document.querySelector(`#answer-list div[data-index="${index}"]`);
            if (answerItem) {
                answerItem.nextElementSibling.remove(); // xóa hidden input x
                answerItem.nextElementSibling.remove(); // xóa hidden input y
                answerItem.nextElementSibling.remove(); // xóa hidden input w
                answerItem.nextElementSibling.remove(); // xóa hidden input h
                answerItem.nextElementSibling.remove(); // xóa hidden input placeholder
                answerItem.remove(); // xóa dòng input-group
            }
        }
    </script>
@endsection