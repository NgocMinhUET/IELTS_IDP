@extends('layouts.master')

@section('contents')
    <div class="mt-4" id="exams">
        <div class="row align-items-center justify-content-between mt-3 g-3">
            <div class="col col-auto">
                <div class="search-box">
                    <form class="position-relative">
                        <input class="form-control search-input search" type="search" placeholder="Search teachers" aria-label="Search" />
                        <span class="fas fa-search search-box-icon"></span>
                    </form>
                </div>
            </div>
            <div class="col-auto">
                <div class="d-flex align-items-center">
                    <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary"><span class="fas fa-plus me-2"></span>Create Teacher</a>
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
                                        List Teachers
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
                                            <th class="align-middle" scope="col" style="width:25%; min-width:200px;">NAME</th>
                                            <th class="align-middle pe-3" scope="col" style="width:25%; min-width:200px;">EMAIL</th>
                                            <th class="align-middle" scope="col" style="width:35%;">CREATED BY</th>
                                            <th class="sort align-middle text-end" scope="col" data-sort="created_at" style="width:15%; min-width:200px;">CREATED AT</th>
                                        </tr>
                                        </thead>
                                        <tbody class="list" id="members-table-body">
                                        @if(!empty($teachers))
                                            @foreach($teachers as $teacher)
                                                <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                                                    <td class="pt-3 pb-3 align-middle white-space-nowrap">
                                                        <a class="d-flex align-items-center text-body text-hover-1000" href="{{ route('admin.teachers.detail', $teacher->id) }}">
                                                            <h6>{{ $teacher->name }}</h6>
                                                        </a>
                                                    </td>
                                                    <td class="align-middle white-space-nowrap">
                                                        <h6>{{ $teacher->email }}</h6>
                                                    </td>
                                                    <td class="align-middle white-space-nowrap">
                                                        @php
                                                            $createdBy = $teacher->createdBy;
                                                            $createdByTxt = '';
                                                            if ($createdBy) {
                                                                $createdByTxt = $createdBy->name . '( ' . $createdBy->email . ' )';
                                                            }
                                                        @endphp
                                                        <h6>{{ $createdByTxt  }}</h6>
                                                    </td>
                                                    <td class="align-middle white-space-nowrap text-end">
                                                        <h6>{{ $teacher->created_at }}</h6>
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
                                        {{ $teachers->links('pagination::bootstrap-5') }}
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