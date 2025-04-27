@extends('layouts.master')

@section('contents')
    @php
        $isUpdate = !!isset($exam);

        $skillTypes = \App\Enum\Models\SkillType::options();
        if ($isUpdate) {
            $skillTypes = $exam->skills->map(function ($skill) {
                $options = \App\Enum\Models\SkillType::optionFromValue($skill->type);
                $options['id'] = $skill->id;
                return $options;
            })->toArray();
        }
    @endphp
{{--    <h2 class="mb-2 lh-sm">Create Exam</h2>--}}
{{--    TODO: make component--}}
    @foreach (['success', 'error', 'warning', 'info'] as $msg)
        @if(session($msg))
            <div class="alert alert-{{ $msg }} alert-dismissible fade show" role="alert">
                {{ session($msg) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    @endforeach

    <div class="mt-4">
        <div class="row g-4">
            <div class="col-12 col-xl-12 order-1 order-xl-0">
                <div class="mb-9">
                    <div class="card shadow-none border my-4" data-component-card="data-component-card">
                        <div class="card-header p-4 border-bottom bg-body">
                            <div class="row g-3 justify-content-between align-items-center">
                                <div class="col-12 col-md">
                                    <h4 class="text-body mb-0">
                                        {{ $isUpdate ? 'Detail Exam' : 'Create Exam' }}
                                    </h4>
                                </div>
                                <div class="col col-md-auto">

                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="p-4 code-to-copy">
                                @if($isUpdate)
                                <form class="row g-3" action="{{ route('admin.exams.update', $exam->id) }}" method="POST">
                                    @method('put')
                                @else
                                <form class="row g-3" novalidate="" action="{{ route('admin.exams.store') }}" method="POST">
                                @endif
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label" for="titleFormControlInput">Title <span class="text-danger">*</span></label>
                                        <input class="form-control  {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                               id="titleFormControlInput" name="title" placeholder=""
                                               value="{{ old('title', $exam->title ?? '') }}"
                                        >
                                        @if($errors->has('title'))
                                            <div class="invalid-feedback mt-0">{{ $errors->first('title') }}</div>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="descTextarea">Description</label>
                                        <textarea class="form-control  {{ $errors->has('desc') ? 'is-invalid' : '' }}"
                                                  id="descTextarea" name="desc" rows="3">{{ old('desc', $exam->desc ?? '') }}</textarea>
                                        @if($errors->has('desc'))
                                            <div class="invalid-feedback mt-0">{{ $errors->first('desc') }}</div>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label mb-1" for="skill">Select Skills <span class="text-danger">*</span></label>
                                        @if($errors->has('skills'))
                                            <div class="invalid-feedback mt-0 d-block">{{ $errors->first('skills') }}</div>
                                        @endif
                                        <div class="row g-4">
                                            @foreach ($skillTypes as $option)
                                                <div class="col-sm-3 skill-item">
                                                    <div class="card-body p-0">
                                                        <div class="code-to-copy">
                                                            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                                                <div class="toast-header border-bottom-0">
                                                                    <strong class="me-auto">
                                                                        @if($isUpdate)
                                                                            <a href="{{ route('admin.skills.detail', $option['id']) }}">
                                                                                {{ $option['label'] }}
                                                                            </a>
                                                                        @else
                                                                            {{ $option['label'] }}
                                                                        @endif
                                                                    </strong>
                                                                    <input type="hidden" name="skills[]" value="{{ $option['value'] }}">
                                                                    <button class="btn ms-2 p-0 remove-skill" type="button" data-bs-dismiss="toast" aria-label="Close"><span class="uil uil-times fs-7"></span></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
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
    document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-skill')) {
            e.target.closest('.skill-item').remove();
        }
    });
</script>
@endsection