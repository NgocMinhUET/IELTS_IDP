@extends('layouts.master')

@section('contents')
    <div class="mt-4" id="members" data-list='{"valueNames":["customer","email","mobile_number","city","last_active","joined"],"page":10,"pagination":true}'>
        <div class="row align-items-center justify-content-between mt-3 g-3">
            <div class="col col-auto">
                <div class="search-box">
                    <form class="position-relative">
                        <input class="form-control search-input search" type="search" placeholder="Search exams" aria-label="Search" />
                        <span class="fas fa-search search-box-icon"></span>
                    </form>
                </div>
            </div>
            <div class="col-auto">
                <div class="d-flex align-items-center">
                    <a href="{{ route('admin.exams.create') }}" class="btn btn-primary"><span class="fas fa-plus me-2"></span>Create Exam</a>
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
                                        List Exams
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
                                            <th class="align-middle" scope="col" style="width:30%; min-width:200px;">TITLE</th>
                                            <th class="align-middle pe-3" scope="col" style="width:35%; min-width:200px;">DESCRIPTION</th>
                                            <th class="align-middle" scope="col" style="width:20%;">SKILLS</th>
                                            <th class="sort align-middle text-end" scope="col" data-sort="created_at" style="width:15%; min-width:200px;">CREATED AT</th>
                                        </tr>
                                        </thead>
                                        <tbody class="list" id="members-table-body">
                                        @foreach($exams as $exam)
                                            <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                                                <td class="pt-3 pb-3 align-middle white-space-nowrap">
                                                    <a class="d-flex align-items-center text-body text-hover-1000" href="{{ route('admin.exams.detail', $exam->id) }}">
                                                        <h6 class="mb-0 fw-semibold">{{ $exam->title }}</h6>
                                                    </a>
                                                </td>
                                                <td class="align-middle white-space-nowrap">
                                                    <h6>{{ $exam->desc }}</h6>
                                                </td>
                                                <td class="align-middle white-space-nowrap">
                                                    @php
                                                        $skillList = '';
                                                        foreach($exam->skills as $skill) {
                                                            $skillList .= $skill->type->name . ' ';
                                                        }
                                                    @endphp
                                                    {{ $skillList }}
                                                </td>
                                                <td class="align-middle white-space-nowrap text-end">
                                                    <h6>{{ $exam->created_at }}</h6>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-3">
                                    <nav aria-label="Page navigation">
                                        {{ $exams->links('pagination::bootstrap-5') }}
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