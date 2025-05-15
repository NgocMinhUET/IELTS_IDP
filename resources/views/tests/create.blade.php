@extends('layouts.master')

@section('css-link')
    <link href="{{ asset('build/vendors/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endsection

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
                            <div class="p-4">
                                @if($isUpdate)
                                <form class="row g-3" action="{{ route('admin.tests.update', $test->id) }}" method="POST">
                                    @method('put')
                                @else
                                <form class="row g-3" novalidate="" action="{{ route('admin.tests.store') }}" method="POST">
                                @endif
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label" for="descTextarea">Description</label>
                                        <textarea class="form-control  {{ $errors->has('desc') ? 'is-invalid' : '' }}"
                                                  id="descTextarea" name="desc" rows="3">{{ old('desc', $test->desc ?? '') }}</textarea>
                                        @if($errors->has('desc'))
                                            <div class="invalid-feedback mt-0">{{ $errors->first('desc') }}</div>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="form-label" for="datetimepicker">Start Date Time</label>
                                                <input class="form-control datetimepicker flatpickr-input"
                                                       name="start_time"
                                                       type="text"
                                                       placeholder="yyyy-mm-dd hour : minute"
                                                       data-options="{&quot;enableTime&quot;:true,&quot;dateFormat&quot;:&quot;Y-m-d H:i&quot;,&quot;disableMobile&quot;:true}"
                                                       readonly="readonly"
                                                       value="{{ $isUpdate ? ($test->start_time ?: '') : '' }}"
                                                >
                                                @if($errors->has('start_time'))
                                                    <div class="invalid-feedback mt-0 d-block">{{ $errors->first('start_time') }}</div>
                                                @endif
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label" for="datetimepicker">End Date Time</label>
                                                <input class="form-control datetimepicker flatpickr-input"
                                                       name="end_time"
                                                       type="text"
                                                       placeholder="yyyy-mm-dd hour : minute"
                                                       data-options="{&quot;enableTime&quot;:true,&quot;dateFormat&quot;:&quot;Y-m-d H:i&quot;,&quot;disableMobile&quot;:true}"
                                                       readonly="readonly"
                                                       value="{{ $isUpdate ? ($test->end_time ?: '') : '' }}"
                                                >
                                                @if($errors->has('end_time'))
                                                    <div class="invalid-feedback mt-0 d-block">{{ $errors->first('end_time') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label mb-1" for="exam">Pickup Exams <span class="text-danger">*</span></label>
                                        @if($errors->has('exams'))
                                            <div class="invalid-feedback mt-0 d-block">{{ $errors->first('exams') }}</div>
                                        @endif
                                        <div class="row mb-3">
                                            <div class="col-auto">
                                                <div class="d-flex align-items-center">
                                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#examModal">
                                                        <span class="fas fa-plus me-2"></span>Pickup
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pickup-exam row g-4">
                                            @if($isUpdate)
{{--                                                @php $exam = $test->exam; @endphp--}}
                                                @foreach($test->exams as $exam)
                                                <div class="col-sm-3 exam-item exam-card">
                                                    <div class="card-body p-0">
                                                        <div class="toast show exam-toast" role="alert" data-bs-autohide="false">
                                                            <div class="toast-header border-bottom-0">
                                                                <strong class="me-auto exam-label">
                                                                    <a href="{{ route('admin.exams.detail', $exam->id) }}">{{ $exam->title }}</a>
                                                                </strong>
                                                                <input type="hidden"
                                                                       name="exams[]"
                                                                       value="{{ $exam->id }}"
                                                                       class="exam-input"
                                                                >
                                                                <button class="btn ms-2 p-0 remove-exam" type="button" aria-label="Remove">
                                                                    <span class="uil uil-times fs-7"></span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label mb-1" for="student">Pickup Students <span class="text-danger">*</span></label>
                                        @if($errors->has('students'))
                                            <div class="invalid-feedback mt-0 d-block">{{ $errors->first('students') }}</div>
                                        @endif
                                        <div class="row mb-3">
                                            <div class="col-auto">
                                                <div class="d-flex align-items-center">
                                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#studentModal">
                                                        <span class="fas fa-plus me-2"></span>Pickup
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="table-responsive {{ $isUpdate ? '' : 'd-none' }}" id="selected-student-table-wrapper">
                                            <table class="table table-bordered table-sm">
                                                <thead>
                                                <tr>
                                                    <th>Code</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Prefix</th>
                                                    <th style="width: 80px;">Remove</th>
                                                </tr>
                                                </thead>
                                                <tbody id="student-selected-table-body">
                                                @if($isUpdate)
                                                   @foreach($students = $test->users as $student)
                                                   <tr
                                                           data-code="{{ $student->code }}"
                                                           data-name="{{ $student->name }}"
                                                           data-email="{{ $student->email }}"
                                                           data-prefix="{{ $student->search_prefix }}"
                                                   >
                                                       <td class="ps-1"><input type="hidden" class="selected-student" name="students[]" value="{{ $student->id }}">
                                                           {{ $student->code }}</td>
                                                       <td>{{ $student->name }}</td>
                                                       <td>{{ $student->email }}</td>
                                                       <td>{{ $student->search_prefix }}</td>
                                                       <td class="text-center">
                                                           <button class="btn p-0 remove-student" type="button" data-id="{{ $student->id }}">
                                                               <span class="uil uil-times fs-7"></span>
                                                           </button>
                                                       </td>
                                                   </tr>
                                                   @endforeach
                                                @endif
                                                <!-- JS Render -->
                                                </tbody>
                                            </table>
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

    <x-exam_select_modal :exams="$allExams" />
    <x-student_select_modal :students="$allStudents" />
@endsection

@section('js')
<script src="{{ asset('build/vendors/flatpickr/flatpickr.min.js') }}"></script>
<script>
    const examDetailUrl = @json(route('admin.exams.detail', '__ID__'));
    const selectedExams = new Map();

    document.addEventListener('DOMContentLoaded', () => {
        initSelectedExamsFromDOM();
        renderSelectedExams();
    });

    function initSelectedExamsFromDOM() {
        document.querySelectorAll('.exam-card').forEach(card => {
            const input = card.querySelector('.exam-input');
            const id = input.value;
            const title = card.querySelector('.exam-label a').textContent.trim();
            if (id && title) {
                selectedExams.set(id, title);
            }
        });
    }

    function renderSelectedExams() {
        const container = document.querySelector(".pickup-exam.row.g-4");
        container.querySelectorAll('.exam-card').forEach(c => c.remove());

        selectedExams.forEach((title, id) => {
            const card = document.createElement('div');
            let url = examDetailUrl.replace('__ID__', id);
            card.className = "col-sm-3 exam-item exam-card";
            card.innerHTML = `
                <div class="card-body p-0">
                  <div class="toast show exam-toast" role="alert" data-bs-autohide="false">
                    <div class="toast-header border-bottom-0">
                      <strong class="me-auto exam-label">
                        <a href="${url}" target="_blank">${title}</a>
                      </strong>
                      <input type="hidden" name="exams[]" value="${id}" class="exam-input">
                      <button class="btn ms-2 p-0 remove-exam" type="button" aria-label="Remove">
                        <span class="uil uil-times fs-7"></span>
                      </button>
                    </div>
                  </div>
                </div>
            `;
            container.appendChild(card);

            card.querySelector('.remove-exam').addEventListener('click', () => {
                // remove from Map && re-render
                selectedExams.delete(id);
                renderSelectedExams();
            });
        });
    }

    // Click the button submit select exam modal event
    document.getElementById("confirm-exam-select").addEventListener("click", () => {
        document.querySelectorAll(".exam-checkbox").forEach(cb => {
            const id = cb.value;
            const name = cb.dataset.name;
            if (cb.checked) {
                selectedExams.set(id, name);
            } else {
                if (selectedExams.has(id) && cb.closest('label')) {
                    selectedExams.delete(id);
                }
            }
        });

        renderSelectedExams();
        bootstrap.Modal.getInstance(document.getElementById('examModal')).hide();
    });

    // re-check the checkbox and clear the old state
    document.getElementById('examModal').addEventListener('show.bs.modal', () => {
        const searchInput = document.getElementById('exam-search');
        searchInput.value = '';

        document.querySelectorAll('#exam-list .list-group-item').forEach(item => {
            item.classList.remove('d-none');
        });

        document.getElementById('exam-placeholder').classList.add('d-none');

        // re-check the selected checkbox
        document.querySelectorAll(".exam-checkbox").forEach(cb => {
            cb.checked = selectedExams.has(cb.value);
        });
    });


    // STUDENTS

    const selectedStudents = new Map();

    function renderSelectedStudents() {
        const container = document.getElementById("student-selected-table-body");
        const tableWrapper = document.getElementById("selected-student-table-wrapper");
        container.innerHTML = '';

        if (selectedStudents.size > 0) {
            tableWrapper.classList.remove('d-none');
        } else {
            tableWrapper.classList.add('d-none');
        }

        selectedStudents.forEach((data, id) => {
            const row = document.createElement('tr');
            row.innerHTML = `
            <td class="ps-1"><input type="hidden" name="students[]" value="${id}">${data.code}</td>
            <td>${data.name}</td>
            <td>${data.email}</td>
            <td>${data.prefix}</td>
            <td class="text-center"><button class="btn p-0 remove-student" type="button" data-id="${id}"><span class="uil uil-times fs-7"></span></button></td>
        `;
            container.appendChild(row);
        });

        document.querySelectorAll(".remove-student").forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                selectedStudents.delete(id);
                renderSelectedStudents();
            });
        });
    }

    window.addEventListener('DOMContentLoaded', () => {
        // Confirm select students
        document.getElementById("confirm-student-select").addEventListener("click", () => {
            document.querySelectorAll(".student-checkbox").forEach(cb => {
                const row = cb.closest('tr');
                const id = cb.value;
                const code = row.dataset.code;
                const name = row.dataset.name;
                const email = row.dataset.email;
                const prefix = row.dataset.prefix;

                if (cb.checked) {
                    selectedStudents.set(id, { code, name, email, prefix });
                } else {
                    selectedStudents.delete(id);
                }
            });

            renderSelectedStudents();
            bootstrap.Modal.getInstance(document.getElementById('studentModal')).hide();
        });

        initSelectedStudentsFromDOM();
    });

    function initSelectedStudentsFromDOM() {
        document.querySelectorAll(".selected-student").forEach(cb => {
            const row = cb.closest('tr');
            const id = cb.value;
            const code = row.dataset.code;
            const name = row.dataset.name;
            const email = row.dataset.email;
            const prefix = row.dataset.prefix;

            selectedStudents.set(id, { code, name, email, prefix });
        });

        renderSelectedStudents();
    }
</script>
@endsection