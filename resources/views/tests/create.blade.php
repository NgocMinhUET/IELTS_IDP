@extends('layouts.master')

@section('css')
    .exam-item {
        transition: all 0.4s ease;
    }

    .exam-removed {
        transform: scale(0.95);
        text-decoration: line-through;
        pointer-events: none;
    }

    .exam-removed .exam-label {
        opacity: 0.5;
        color: red;
    }
@endsection

@section('contents')
    @php
        $isUpdate = !!isset($test);
    @endphp

    <div class="mt-4">
        <div class="row g-4">
            <div class="col-12 col-xl-12 order-1 order-xl-0">
                <div class="mb-9">
                    <div class="card shadow-none border my-4" data-component-card="data-component-card">
                        <div class="card-header p-4 border-bottom bg-body">
                            <div class="row g-3 justify-content-between align-items-center">
                                <div class="col-12 col-md">
                                    <h4 class="text-body mb-0">
                                        {{ $isUpdate ? 'Detail Test' : 'Create Test' }}
                                    </h4>
                                </div>
                                <div class="col col-md-auto">

                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="p-4 code-to-copy">
                                @if($isUpdate)
                                <form class="row g-3" action="{{ route('admin.tests.update', $exam->id) }}" method="POST">
                                    @method('put')
                                @else
                                <form class="row g-3" novalidate="" action="{{ route('admin.tests.store') }}" method="POST">
                                @endif
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label" for="descTextarea">Description</label>
                                        <textarea class="form-control  {{ $errors->has('desc') ? 'is-invalid' : '' }}"
                                                  id="descTextarea" name="desc" rows="3">{{ old('desc', $exam->desc ?? '') }}</textarea>
                                        @if($errors->has('desc'))
                                            <div class="invalid-feedback mt-0">{{ $errors->first('desc') }}</div>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label mb-1" for="exam">Select Exam <span class="text-danger">*</span></label>
                                        @if($errors->has('exams'))
                                            <div class="invalid-feedback mt-0 d-block">{{ $errors->first('exams') }}</div>
                                        @endif
                                        <div class="row g-4">
                                            <div class="col-sm-3 exam-item {{ false ? '' : 'exam-removed' }}">
                                                <div class="card-body p-0">
                                                    <div class="code-to-copy">
                                                        <div class="toast show exam-toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                                            <div class="toast-header border-bottom-0">
                                                                <strong class="me-auto exam-label">
                                                                    @if($isUpdate && $isSelectedOption)
                                                                        <a href="{{ route('admin.exams.detail', 1) }}">
                                                                            {{ $a = 'TEMP' }}
                                                                        </a>
                                                                    @else
                                                                        {{ $b = 'TEMP2' }}
                                                                    @endif
                                                                </strong>
                                                                <input type="hidden"
                                                                       name="exams[]"
                                                                       value=""
                                                                       class="exam-input"
                                                                >
                                                                <button class="btn ms-2 p-0 remove-exam {{ true ? '' : 'd-none' }}"
                                                                        type="button" aria-label="Remove"
                                                                >
                                                                    <span class="uil uil-times fs-7"></span>
                                                                </button>
                                                                <button class="btn ms-2 p-0 restore-exam {{ true ? 'd-none' : '' }}"
                                                                        type="button" aria-label="Restore"
                                                                >
                                                                    <span class="opacity-100 uil uil-plus fs-7"></span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button class="btn btn-primary" type="submit">Submit form</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 5">
        <div class="toast align-items-center text-white bg-dark border-0" id="icon-copied-toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex" data-bs-theme="dark">
                <div class="toast-body p-3"></div>
                <button class="btn-close me-2 m-auto" type="button" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const examItems = document.querySelectorAll('.exam-item');

        examItems.forEach(item => {
            const removeBtn = item.querySelector('.remove-exam');
            const restoreBtn = item.querySelector('.restore-exam');
            const examInput = item.querySelector('.exam-input');

            removeBtn.addEventListener('click', function () {
                item.classList.add('exam-removed');
                removeBtn.classList.add('d-none');
                restoreBtn.classList.remove('d-none');
                examInput.disabled = true;
            });

            restoreBtn.addEventListener('click', function () {
                item.classList.remove('exam-removed');
                restoreBtn.classList.add('d-none');
                removeBtn.classList.remove('d-none');
                examInput.disabled = false;
            });
        });
    });
</script>
@endsection