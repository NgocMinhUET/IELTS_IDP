@extends('layouts.master')

@section('css-link')
@endsection

@section('css')
@endsection

@section('contents')
    @php
        $isUpdate = !!isset($student);
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
                                        {{ $isUpdate ? 'Detail Student' : 'Create Student' }}
                                    </h4>
                                </div>
                                <div class="col col-md-auto">

                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="p-4">
                                @if($isUpdate)
                                <form class="row g-3" action="{{ route('admin.students.update', $student->id) }}" method="POST">
                                @method('put')
                                @else
                                <form class="row g-3" novalidate="" action="{{ route('admin.students.store') }}" method="POST">
                                    @endif
                                    @csrf
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="form-label" for="codeTextarea">Code <span class="text-danger">*</span></label>
                                                <input class="form-control  {{ $errors->has('code') ? 'is-invalid' : '' }}"
                                                       value="{{ $isUpdate ? ($student->code ?: '') : '' }}"
                                                       id="codeTextarea" name="code" type="text">
                                                @if($errors->has('code'))
                                                    <div class="invalid-feedback mt-0">{{ $errors->first('code') }}</div>
                                                @endif
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label" for="prefixTextarea">Search Prefix</label>
                                                <input class="form-control  {{ $errors->has('search_prefix') ? 'is-invalid' : '' }}"
                                                       value="{{ $isUpdate ? ($student->search_prefix ?: '') : '' }}"
                                                       id="prefixTextarea" name="search_prefix" type="text">
                                                @if($errors->has('search_prefix'))
                                                    <div class="invalid-feedback mt-0">{{ $errors->first('search_prefix') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="emailTextarea">Email <span class="text-danger">*</span></label>
                                        <input class="form-control  {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                               value="{{ $isUpdate ? ($student->email ?: '') : '' }}"
                                               id="emailTextarea" name="email" type="email">
                                        @if($errors->has('email'))
                                            <div class="invalid-feedback mt-0">{{ $errors->first('email') }}</div>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="nameTextarea">Name <span class="text-danger">*</span></label>
                                        <input class="form-control  {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                               value="{{ $isUpdate ? ($student->name ?: '') : '' }}"
                                               id="nameTextarea" name="name" type="text">
                                        @if($errors->has('name'))
                                            <div class="invalid-feedback mt-0">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>

                                    @if($isUpdate)
                                        <div class="mb-3">
                                            <label class="form-label" for="newPasswordTextarea">New Password</label>
                                            <input class="form-control  {{ $errors->has('new_password') ? 'is-invalid' : '' }}"
                                                   value="" placeholder="Enter new password"
                                                   id="newPasswordTextarea" name="new_password" type="text">
                                            @if($errors->has('new_password'))
                                                <div class="invalid-feedback mt-0">{{ $errors->first('new_password') }}</div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="mb-3">
                                            <label class="form-label" for="passwordTextarea">Password <span class="text-danger">*</span></label>
                                            <input class="form-control  {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                                   value="{{ $isUpdate ? ($student->password ?: '') : '' }}"
                                                   id="passwordTextarea" name="password" type="text">
                                            @if($errors->has('password'))
                                                <div class="invalid-feedback mt-0">{{ $errors->first('password') }}</div>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="mb-3">
                                        <label class="form-label d-block">Account Status</label>

                                        <div class="form-check form-check-inline">
                                            <input
                                                    class="form-check-input"
                                                    type="radio"
                                                    name="is_active"
                                                    id="activeStatus"
                                                    value="1"
                                                    {{ $isUpdate ? ($student->is_active ? 'checked' : '') : 'checked' }}>
                                            <label class="form-check-label" for="activeStatus">
                                                Active
                                            </label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input
                                                    class="form-check-input"
                                                    type="radio"
                                                    name="is_active"
                                                    id="inactiveStatus"
                                                    value="0"
                                                    {{ $isUpdate ? ($student->is_active ? '' : 'checked') : '' }}>
                                            <label class="form-check-label" for="inactiveStatus">
                                                Not Active
                                            </label>
                                        </div>

                                        @if($errors->has('is_active'))
                                            <div class="text-danger mt-1">{{ $errors->first('is_active') }}</div>
                                        @endif
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

    </script>
@endsection