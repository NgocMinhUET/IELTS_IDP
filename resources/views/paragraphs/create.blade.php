@extends('layouts.master')

@section('contents')
    @php
        $isUpdate = isset($paragraph);
    @endphp

    @if ($isUpdate && $isParagraphInherit)
        <div class="alert alert-subtle-warning alert-dismissible fade show" role="alert">
            * There is currently a question that inherits the content of this paragraph. Please check carefully before making any changes. *
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
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
                                        @if ($isUpdate)
                                            Update Main Paragraph For Part {{ $part->title }} ( {{ $part->skill->type->label() }})
                                        @else
                                            Create Main Paragraph For Part {{ $part->title }} ( {{ $part->skill->type->label() }})
                                        @endif
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="p-4 code-to-copy">
                                @if ($isUpdate)
                                    <form action="{{ route('admin.parts.paragraphs.update', [$part->id, $paragraph->id]) }}" method="POST">
                                    @method('PUT')
                                @else
                                    <form action="{{ route('admin.parts.paragraphs.store', $part->id) }}" method="POST">
                                @endif
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Content <span class="text-danger">*</span></label>
                                        <textarea id="editor" class="form-control {{ $errors->has('content') ? 'is-invalid' : '' }}"
                                                  name="content">{{ old('content', $isUpdate ? $paragraph->content : '') }}</textarea>
                                        @if($errors->has('content'))
                                            <div class="invalid-feedback mt-0 d-block">{{ $errors->first('content') }}</div>
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
    </div>
@endsection

@section('js')
    <script src="{{ asset('build/vendors/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: '#editor',
            plugins: `
                advlist autolink lists link charmap preview anchor
                searchreplace visualblocks code fullscreen
                table paste help wordcount
            `,
            toolbar: `
                undo redo | formatselect | bold italic underline strikethrough |
                forecolor backcolor | alignleft aligncenter alignright alignjustify |
                bullist numlist outdent indent | link image media table |
                code fullscreen
            `,
            setup: function (editor) {

            }
        });
    </script>
@endsection
