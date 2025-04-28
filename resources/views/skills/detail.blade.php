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
                                        Detail Skill
                                    </h4>
                                </div>
                                <div class="col col-md-auto">

                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="p-4 code-to-copy">
                                <form class="row g-3" action="{{ route('admin.skills.update', $skill->id) }}" method="POST">
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

                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col">
                                                <label class="form-label" for="durationFormControlInput">Duration</label>
                                                <input class="form-control"
                                                       type="number"
                                                       name="duration"
                                                       placeholder=""
                                                       value="{{ old('duration', $skill->duration ?? '') }}"
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
                                        <label class="form-label mb-1" for="skill">Parts <span class="text-danger">*</span></label>
                                        @if($errors->has('parts'))
                                            <div class="invalid-feedback mt-0 d-block">{{ $errors->first('parts') }}</div>
                                        @endif

                                        <div class="mb-2 d-flex">
                                            <input type="text" class="form-control me-2" id="new-part-title" placeholder="Enter new part title">
                                            <button type="button" class="btn btn-primary" id="add-part">Add Part</button>
                                        </div>

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
                                                                    <button class="btn ms-2 p-0 remove-part" type="button" data-bs-dismiss="toast" aria-label="Close"><span class="uil uil-times fs-7"></span></button>
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
    </script>
@endsection