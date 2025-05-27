@extends('layouts.master')

@section('css-link')
    <link href="{{ asset('build/vendors/dropzone/dropzone.css') }}" rel="stylesheet" />
@endsection

@php
    $notAssignTest = !$skill->exam->tests_count;
    $audioUrl = '';
@endphp

@section('contents')
    @if(!$notAssignTest)
        <x-has_assigned_tests_alert></x-has_assigned_tests_alert>
    @endif
    <div class="mt-4">
        <div class="row g-4">
            <div class="col-12 col-xl-12 order-1 order-xl-0">
                <div class="mb-9">
                    <div class="card shadow-none border my-4" data-component-card="data-component-card">
                        <div class="card-header p-4 border-bottom bg-body">
                            <div class="row g-3 justify-content-between align-items-center">
                                <div class="col-12 col-md">
                                    <h4 class="text-body mb-0">
                                        Detail {{ $skill->type->label() }} Skill
                                    </h4>
                                </div>
                                <div class="col col-md-auto">

                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="p-4 code-to-copy">
                                <form class="row g-3" action="{{ route('admin.skills.update', $skill->id) }}" method="POST" enctype="multipart/form-data">
                                    @method('put')
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label" for="descTextarea">Description</label>
                                        <textarea class="form-control {{ $errors->has('desc') ? 'is-invalid' : '' }}"
                                                  id="descTextarea" name="desc" rows="3">{{ old('desc', $skill->desc ?? '') }}</textarea>
                                        @if($errors->has('desc'))
                                            <div class="invalid-feedback mt-0">{{ $errors->first('desc') }}</div>
                                        @endif
                                    </div>

                                    @if ($skill->type == \App\Enum\Models\SkillType::LISTENING)
                                    @php
                                        $audioUrl = $skill->getFirstMediaUrl();
                                    @endphp
                                    @if ($audioUrl)
                                    <div class="mb-3">
                                        <label class="form-label" for="audioTextarea">
                                            Current Audio File
                                        </label>
                                        <div>
                                            <audio controls>
                                                <source src="{{ $audioUrl }}" type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="mb-3">
                                        <label class="form-label" for="audioTextarea">
                                            @if ($audioUrl)
                                                Change Audio File
                                            @else
                                                @if($notAssignTest)
                                                Upload Audio File <span class="text-danger">*</span>
                                                @else
                                                No Audio File
                                                @endif
                                            @endif
                                        </label>
                                        @if($errors->has('audio'))
                                            <div class="invalid-feedback mt-0 d-block">{{ $errors->first('audio') }}</div>
                                        @endif
                                        <div class="invalid-feedback mt-0 d-block" id="audio-error">{{ $errors->first('audio') }}</div>

                                        @if($notAssignTest)
                                        <div class="dropzone dropzone-multiple p-0" id="dropzone"
                                             data-dropzone="data-dropzone"
                                             data-options='{"autoProcessQueue":false,"maxFiles":1,"acceptedFiles":"audio/mpeg,audio/wav"}'
                                        >
                                            <input type="file" name="audio" id="audio-input" hidden/>
                                            <div class="dz-message m-0" data-dz-message="data-dz-message">
                                                <div class="dz-message-text">
                                                    <img class="me-2" src="{{ asset('build/assets/img/icons/cloud-upload.svg') }}" width="25" alt="" />Drop audio file here
                                                </div>
                                            </div>
                                            <div class="dz-preview dz-preview-multiple m-0 d-flex flex-column" style="min-height: 0">
                                                <div class="d-flex pb-3 border-bottom border-translucent media px-2">
                                                    <div class="border p-2 rounded-2 me-2">
                                                        <img class="rounded-2 dz-image" src="{{ asset('build/assets/img/icons/file.png') }}" alt="..." data-dz-thumbnail="data-dz-thumbnail" />
                                                    </div>
                                                    <div class="flex-1 d-flex flex-between-center">
                                                        <div>
                                                            <h6 data-dz-name="data-dz-name"></h6>
                                                            <div class="d-flex align-items-center">
                                                                <p class="mb-0 fs-9 text-body-quaternary lh-1" data-dz-size="data-dz-size"></p>
                                                                <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress=""></span></div>
                                                            </div>
                                                        </div>
                                                        <div class="dropdown">
                                                            <button class="btn btn-link text-body-quaternary btn-sm dropdown-toggle btn-reveal dropdown-caret-none" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <span class="fas fa-ellipsis-h"></span>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end border border-translucent py-2">
                                                                <a class="dropdown-item" href="#!" data-dz-remove="data-dz-remove">Remove File</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    @endif

                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col">
                                                <label class="form-label" for="durationFormControlInput">Duration <span class="text-danger">*</span></label>
                                                <input class="form-control"
                                                       type="number"
                                                       name="duration"
                                                       placeholder=""
                                                       value="{{ old('duration', $skill->duration ?? '') }}"
                                                       required
                                                >
                                                @if($errors->has('duration'))
                                                    <div class="invalid-feedback mt-0">{{ $errors->first('duration') }}</div>
                                                @endif
                                            </div>
                                            <div class="col">
                                                <div class="col">
                                                    <label class="form-label" for="bonusFormControlInput">Bonus</label>
                                                    <input class="form-control"
                                                           type="number"
                                                           name="bonus_time"
                                                           placeholder=""
                                                           value="{{ old('bonus_time', $skill->bonus_time ?? '') }}"
                                                    >
                                                    @if($errors->has('bonus_time'))
                                                        <div class="invalid-feedback mt-0">{{ $errors->first('bonus_time') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label mb-1" for="skill">Parts</label>
                                        @if($errors->has('parts'))
                                            <div class="invalid-feedback mt-0 d-block">{{ $errors->first('parts') }}</div>
                                        @endif

                                        @if($notAssignTest)
                                        <div class="mb-2 d-flex">
                                            <input type="text" class="form-control me-2" id="new-part-title" placeholder="Enter new part title">
                                            <button type="button" class="btn btn-primary" id="add-part">Add Part</button>
                                        </div>
                                        @endif

                                        <div class="row g-4" id="parts-list">
                                            @foreach ($skill->parts as $index => $part)
                                                <div class="col-sm-3 part-item">
                                                    <input type="hidden" name="parts[{{ $index }}][title]" value="{{ $part->title }}">
                                                    <input type="hidden" name="parts[{{ $index }}][id]" value="{{ $part->id }}">
                                                    <div class="card-body p-0">
                                                        <div class="code-to-copy">
                                                            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                                                <div class="toast-header border-bottom-0">
                                                                    <strong class="me-auto">
                                                                        <a href="{{ route('admin.parts.detail', $part->id) }}">
                                                                            {{ $part->title }}
                                                                        </a>
                                                                    </strong>
                                                                    @if($notAssignTest)
                                                                    <button class="btn ms-2 p-0 remove-part" type="button" data-bs-dismiss="toast" aria-label="Close"><span class="uil uil-times fs-7"></span></button>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    @if($notAssignTest)
                                        <div class="col-12">
                                            <button class="btn btn-primary" type="submit">Submit form</button>
                                        </div>
                                    @endif
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
    <script src="{{ asset('build/vendors/dropzone/dropzone-min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const dropzoneEl = document.querySelector("#dropzone");

            if (Dropzone.instances.length === 0 || !Dropzone.forElement(dropzoneEl)) {
                console.warn("Dropzone chưa được khởi tạo tự động.");
                return;
            }

            const dz = Dropzone.forElement(dropzoneEl);
            const hiddenInput = document.querySelector('#audio-input');
            const dataTransfer = new DataTransfer();

            dz.on("addedfile", function (file) {
                document.getElementById('audio-error').textContent = '';
                dataTransfer.items.clear(); // prepare for one file upload only
                dataTransfer.items.add(file);

                if (hiddenInput) {
                    hiddenInput.files = dataTransfer.files;
                }
            });

            dz.on("removedfile", function (file) {
                dataTransfer.items.clear(); // clear all files

                if (hiddenInput) {
                    hiddenInput.files = dataTransfer.files;
                }
            });
        });

        document.getElementById('add-part').addEventListener('click', function () {
            const title = document.getElementById('new-part-title').value.trim();
            if (title === '') return;

            const partsList = document.getElementById('parts-list');
            const index = partsList.children.length;

            const partHTML = `
            <div class="col-sm-3 part-item">
                <input type="hidden" name="parts[${index}][title]" value="${title}">
                <div class="card-body p-0">
                    <div class="code-to-copy">
                        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                            <div class="toast-header border-bottom-0">
                                <strong class="me-auto">${title}</strong>
                                <button class="btn ms-2 p-0 remove-part" type="button"><span class="uil uil-times fs-7"></span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

            partsList.insertAdjacentHTML('beforeend', partHTML);
            document.getElementById('new-part-title').value = '';
        });

        document.addEventListener('click', function (e) {
            if (e.target.closest('.remove-part')) {
                e.target.closest('.part-item').remove();
            }
        });

        document.querySelector('form').addEventListener('submit', function (e) {
            const skillTypeIsListening = "{{ $skill->type->value }}" === "{{ \App\Enum\Models\SkillType::LISTENING->value }}";
            const notAssignTest = @json($notAssignTest);
            const hasAudioUrl = @json((bool) $audioUrl);

            if (skillTypeIsListening && notAssignTest && !hasAudioUrl) {
                const audioInput = document.querySelector('#audio-input');

                if (!audioInput || audioInput.files.length === 0) {
                    e.preventDefault();

                    document.getElementById('audio-error').textContent = 'The audio file is required';

                    // alert('Please upload an audio file for Listening skill.');

                    // Optional: highlight Dropzone or show error message near it
                    // document.querySelector('#dropzone').classList.add('border-danger');

                    return false;
                }
            }
        });
    </script>
@endsection