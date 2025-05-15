@extends('layouts.master')

@section('css-link')
    <link href="{{ asset('build/vendors/dropzone/dropzone.css') }}" rel="stylesheet" />
@endsection

@section('css')
@endsection

@section('contents')
    <div class="mt-4">
        <div class="row g-4">
            <div class="col-12 col-xl-12 order-1 order-xl-0">
                <div class="mb-9">
                    <div class="card shadow-none border my-4" data-component-card="data-component-card">
                        <div class="card-header p-4 border-bottom bg-body">
                            <div class="row g-3 justify-content-between align-items-center">
                                <div class="col-12 col-md">
                                    <h4 class="text-body">
                                        Import Student
                                        <a href="{{ route('admin.students.import.download-template') }}"
                                           class="btn btn-sm btn-outline-secondary float-end">
                                            <i class="fas fa-download"></i> Download Template
                                        </a>
                                    </h4>
                                </div>
                                <div class="col col-md-auto">

                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="p-4">
                                @if(session('import_errors'))
                                    <div class="alert alert-subtle-danger alert-dismissible fade show" role="alert">
                                        <h5>Import Failed:</h5>
                                        <ul class="mb-0">
                                            @foreach(session('import_errors') as $error)
                                                <li>
                                                    {{ $error }}
                                                </li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                <form class="row g-3" action="{{ route('admin.students.import.execute') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label" for="importTextarea">
                                            Import File <span class="text-danger">*</span>
                                        </label>
                                        @if($errors->has('file'))
                                            <div class="invalid-feedback mt-0 d-block">{{ $errors->first('file') }}</div>
                                        @endif
                                        <div class="dropzone dropzone-multiple p-0" id="dropzone"
                                             data-dropzone="data-dropzone"
                                             data-options='{"autoProcessQueue":false,"maxFiles":1,"acceptedFiles":"text/csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"}'
                                        >
                                            <input type="file" name="file" id="import-input" hidden/>
                                            <div class="dz-message m-0" data-dz-message="data-dz-message">
                                                <div class="dz-message-text">
                                                    <img class="me-2" src="{{ asset('build/assets/img/icons/cloud-upload.svg') }}" width="25" alt="" />Drop import file here
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
    <script src="{{ asset('build/vendors/dropzone/dropzone-min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const dropzoneEl = document.querySelector("#dropzone");

            if (Dropzone.instances.length === 0 || !Dropzone.forElement(dropzoneEl)) {
                console.warn("Dropzone chưa được khởi tạo tự động.");
                return;
            }

            const dz = Dropzone.forElement(dropzoneEl);
            const hiddenInput = document.querySelector('#import-input');
            const dataTransfer = new DataTransfer();

            dz.on("addedfile", function (file) {
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
    </script>
@endsection