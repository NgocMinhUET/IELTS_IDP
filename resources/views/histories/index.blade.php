@extends('layouts.master')

@section('contents')
    <div class="mt-4" id="tests">
        <x-spinner></x-spinner>

        <div class="row align-items-center justify-content-between mt-3 g-3">
            <div class="col col-auto">
                <div class="search-box">
                    <form class="position-relative">
                        <input class="form-control search-input search" type="search" placeholder="Search tests" aria-label="Search" />
                        <span class="fas fa-search search-box-icon"></span>
                    </form>
                </div>
            </div>
            <div class="col-auto">
                <div class="d-flex align-items-center">
                    <a href="{{ route('admin.tests.create') }}" class="btn btn-primary"><span class="fas fa-plus me-2"></span>Create Test</a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-xl-12 order-1 order-xl-0">
                <div class="mb-9">
                    <div class="card shadow-none border my-4" data-component-card="data-component-card">
                        <div class="card-header p-4 border-bottom bg-body">
                            <div class="row g-3 justify-content-between align-items-center">
                                <div class="col-12 col-md">
                                    <h4 class="text-body mb-0">
                                        History Tests
                                    </h4>
                                </div>
                                <div class="col col-md-auto">

                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="p-4">
                                @if(!empty($tests))
{{--                                    <div class="history-test row g-4">--}}
                                    <div class="row g-3 mb-5">
                                        @foreach($tests as $test)
                                            <div class="col-md-6 col-xxl-3">
                                                <div class="card h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between">
                                                            <div>
                                                                <h5 class="mb-1">
                                                                    <strong class="me-auto exam-label">
                                                                        <a href="{{ route('admin.histories.test-detail', $test->id) }}">{{ $test->desc }}</a>
                                                                    </strong>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                        @php
                                                            $examSessions = $test->examSessions;
                                                            $startedUsers = $examSessions->unique('user_id')->count();
                                                            $completedUsers = $examSessions
                                                                ->where('status', \App\Enum\Models\ExamSessionStatus::COMPLETE)
                                                                ->unique('user_id')->count();
                                                            $completedUsers = $examSessions
                                                                ->where('status', \App\Enum\Models\ExamSessionStatus::COMPLETE)
                                                                ->unique('user_id')->count();
                                                        @endphp
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <h1 class="fs-5 pt-3">{{ $test->exams_count ?? 0 }} <span class="uil fs-5 lh-1 uil-notebooks text-primary"></span></h1>
                                                                <p class="fs-9 mb-0">Exams</p>
                                                            </div>
                                                            <div class="col-6">
                                                                <h1 class="fs-5 pt-3">{{ $test->users_count ?? 0 }} <span class="uil fs-5 lh-1 uil-user text-primary"></span></h1>
                                                                <p class="fs-9 mb-0">Users Assigned</p>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-6">
                                                                <h1 class="fs-5 pt-3">{{ $startedUsers ?? 0 }} <span class="uil fs-5 lh-1 uil-user text-primary"></span></h1>
                                                                <p class="fs-9 mb-0">Users Started</p>
                                                            </div>
                                                            <div class="col-6">
                                                                <h1 class="fs-5 pt-3">{{ $completedUsers ?? 0 }} <span class="uil fs-5 lh-1 uil-check-circle text-primary"></span></h1>
                                                                <p class="fs-9 mb-0">Users Completed</p>
                                                            </div>
                                                        </div>

                                                        <div class="mt-4">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <div class="bullet-item bg-primary me-2"></div>
                                                                <h6 class="text-body fw-semibold flex-1 mb-0">Start time</h6>
                                                                <h6 class="text-body fw-semibold mb-0">{{ $test->start_time }}</h6>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <div class="bullet-item bg-primary-subtle me-2"></div>
                                                                <h6 class="text-body fw-semibold flex-1 mb-0">End time</h6>
                                                                <h6 class="text-body fw-semibold mb-0">{{ $test->end_time }}</h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    No data
                                @endif
                                <div class="mt-3">
                                    <nav aria-label="Page navigation">
                                        {{ $tests->links('pagination::bootstrap-5') }}
                                    </nav>
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

@endsection