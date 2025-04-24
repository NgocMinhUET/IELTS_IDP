@extends('layouts.master')

@section('contents')
{{--    <h2 class="mb-2 lh-sm">Create Exam</h2>--}}
    <div class="mt-4">
        <div class="row g-4">
            <div class="col-12 col-xl-12 order-1 order-xl-0">
                <div class="mb-9">
                    <div class="card shadow-none border my-4" data-component-card="data-component-card">
                        <div class="card-header p-4 border-bottom bg-body">
                            <div class="row g-3 justify-content-between align-items-center">
                                <div class="col-12 col-md">
                                    <h4 class="text-body mb-0">Create Exam</h4>
                                </div>
                                <div class="col col-md-auto">

                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="p-4 code-to-copy">
                                <form class="row g-3" novalidate="" action="{{ route('admin.exams.store') }}">
                                    @method('POST')
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label" for="titleFormControlInput">Title</label>
                                        <input class="form-control" id="titleFormControlInput" name="title" placeholder="">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="descTextarea">Description</label>
                                        <textarea class="form-control" id="descTextarea" name="desc" rows="3"> </textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label mb-1" for="skill">Select Skills</label>
                                        <div class="row g-4">
                                            <div class="col-sm-3">
                                                <div class="card-body p-0">
                                                    <div class="code-to-copy">
                                                        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                                            <div class="toast-header border-bottom-0">
                                                                <strong class="me-auto">Listing</strong>
                                                                <input type="hidden" name="types[]" value="1">
                                                                <button class="btn ms-2 p-0 remove_skill" type="button" data-bs-dismiss="toast" aria-label="Close"><span class="uil uil-times fs-7"></span></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="card-body p-0">
                                                    <div class="collapse code-collapse" id="basic-example-code">
                                                    </div>
                                                    <div class="code-to-copy">
                                                        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                                            <div class="toast-header border-bottom-0">
                                                                <strong class="me-auto">Speaking</strong>
                                                                <input type="hidden" name="types[]" value="2">
                                                                <button class="btn ms-2 p-0 remove_skill" type="button" data-bs-dismiss="toast" aria-label="Close"><span class="uil uil-times fs-7"></span></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="card-body p-0">
                                                    <div class="collapse code-collapse" id="basic-example-code">
                                                    </div>
                                                    <div class="code-to-copy">
                                                        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                                            <div class="toast-header border-bottom-0">
                                                                <strong class="me-auto">Reading</strong>
                                                                <input type="hidden" name="types[]" value="3">
                                                                <button class="btn ms-2 p-0 remove_skill" type="button" data-bs-dismiss="toast" aria-label="Close"><span class="uil uil-times fs-7"></span></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="card-body p-0">
                                                    <div class="collapse code-collapse" id="basic-example-code">
                                                    </div>
                                                    <div class="pb-4 code-to-copy">
                                                        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                                            <div class="toast-header border-bottom-0">
                                                                <strong class="me-auto">Writing</strong>
                                                                <input type="hidden" name="types[]" value="4">
                                                                <button class="btn ms-2 p-0 remove_skill" type="button" data-bs-dismiss="toast" aria-label="Close"><span class="uil uil-times fs-7"></span></button>
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
<script>
    $(document).on('click', '.remove_skill', function () {
        $(this).siblings('input[type="hidden"][name="types[]"]').remove();
    });
</script>
@endsection