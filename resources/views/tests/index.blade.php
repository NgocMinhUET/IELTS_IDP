@extends('layouts.master')

@section('contents')
    <div class="mt-4" id="exams">
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
                                        List Tests
                                    </h4>
                                </div>
                                <div class="col col-md-auto">

                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="p-4 code-to-copy">
                                <div class="table-responsive scrollbar ms-n1 ps-1">
                                    <table class="table table-sm fs-9 mb-0">
                                        <thead>
                                        <tr>
                                            <th class="align-middle" scope="col" style="width:30%; min-width:200px;">DESCRIPTION</th>
                                            <th class="align-middle pe-3" scope="col" style="width:30%; min-width:200px;">EXAM DESCRIPTION</th>
                                            <th class="align-middle" scope="col" style="width:25%;">TIME</th>
                                            <th class="sort align-middle text-end" scope="col" data-sort="created_at" style="width:15%; min-width:200px;">CREATED AT</th>
                                        </tr>
                                        </thead>
                                        <tbody class="list" id="members-table-body">
                                        @if(!empty($tests))
                                            @foreach($tests as $test)
                                            <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                                                <td class="pt-3 pb-3 align-middle white-space-nowrap">
                                                    <a class="d-flex align-items-center text-body text-hover-1000" href="{{ route('admin.tests.detail', $test->id) }}">
                                                        <h6 class="mb-0 fw-semibold">{{ $test->desc }}</h6>
                                                    </a>
                                                </td>
                                                <td class="align-middle white-space-nowrap">
                                                    <h6>{{ $test->exam->desc }}</h6>
                                                </td>
                                                <td class="align-middle white-space-nowrap">
                                                    {{ $test->start_time }} ~ {{ $test->end_time }}
                                                </td>
                                                <td class="align-middle white-space-nowrap text-end">
                                                    <h6>{{ $test->created_at }}</h6>
                                                </td>
                                            </tr>
                                          @endforeach
                                        @else
                                            No data
                                        @endif
                                        </tbody>
                                    </table>
                                </div>

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