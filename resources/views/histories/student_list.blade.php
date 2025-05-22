@extends('layouts.master')

@section('contents')
    <div class="mt-4" id="exams">
        <div class="row align-items-center justify-content-between mt-3 g-3">
            <div class="col col-auto">
                <div class="">
                    <h5>{{ $test->desc }}: {{ $test->start_time }} ~ {{ $test->end_time }}</h5>
                </div>
            </div>
            <div class="col-auto">

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
                                        List Students
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
                                            <th class="align-middle" scope="col" style="width:15%; min-width:200px;">CODE</th>
                                            <th class="align-middle" scope="col" style="width:25%; min-width:200px;">NAME</th>
                                            <th class="align-middle pe-3" scope="col" style="width:20%; min-width:200px;">EMAIL</th>
                                            <th class="align-middle pe-3" scope="col" style="width:10%; min-width:200px;">STATUS</th>
                                            <th class="align-middle" scope="col" style="width:10%; min-width:200px;">NUMBER OF START TEST</th>
                                            <th class="sort align-middle" scope="col" data-sort="created_at" style="width:10%; min-width:200px;">
                                                NUMBER OF COMPLETE TEST</th>
                                            <th class="sort align-middle" scope="col" data-sort="created_at" style="width:10%; min-width:200px;">
                                                NUMBER OF IN COMPLETE TEST</th>
                                        </tr>
                                        </thead>
                                        <tbody class="list" id="members-table-body">
                                        @if(!empty($students))
                                            @foreach($students as $student)
                                                <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                                                    <td class="pt-3 pb-3 align-middle white-space-nowrap">
                                                        <a class="d-flex align-items-center text-body text-hover-1000"
                                                           href="{{ route('admin.histories.list-exam-session', [$test->id, $student->id]) }}">
                                                            <h6>{{ $student->code }}</h6>
                                                        </a>
                                                    </td>
                                                    <td class="pt-3 pb-3 align-middle white-space-nowrap">
                                                        <a class="d-flex align-items-center text-body text-hover-1000"
                                                           href="{{ route('admin.histories.list-exam-session', [$test->id, $student->id]) }}">
                                                            <h6>{{ $student->name }}</h6>
                                                        </a>
                                                    </td>
                                                    <td class="align-middle white-space-nowrap">
                                                        <h6>{{ $student->email }}</h6>
                                                    </td>
                                                    <td class="align-middle white-space-nowrap">
                                                        @if($student->is_active)
                                                            <h6><i class="fas fa-circle-check text-success" title="Active"></i> Active</h6>
                                                        @else
                                                            <h6><i class="fas fa-circle-xmark text-danger" title="Not Active"></i> Not Active</h6>
                                                        @endif
                                                    </td>
                                                    @php
                                                        $examSessions = $student->examSessions;
                                                        $numberOfStart = $examSessions->count();
                                                        $numberOfComplete = $examSessions->where('status', \App\Enum\Models\ExamSessionStatus::COMPLETE)->count();
                                                        $numberOfInComplete = $examSessions->where('status', \App\Enum\Models\ExamSessionStatus::IN_COMPLETE)->count();
                                                    @endphp
                                                    <td class="align-middle white-space-nowrap">
                                                        <h6>{{ $numberOfStart  }}</h6>
                                                    </td>
                                                    <td class="align-middle white-space-nowrap">
                                                        <h6>{{ $numberOfComplete }}</h6>
                                                    </td>
                                                    <td class="align-middle white-space-nowrap">
                                                        <h6>{{ $numberOfComplete }}</h6>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            No data
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection