@extends('layouts.master')

@section('contents')
    <div class="mt-4" id="tests">
        <x-spinner></x-spinner>

        <div class="row align-items-center justify-content-between mt-3 g-3">
            <div class="col col-auto">

            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-xl-12 order-1 order-xl-0">
                <div class="mb-9">
                    <div class="card shadow-none border my-4" data-component-card="data-component-card">
                        <div class="card-header p-4 border-bottom bg-body">
                            <div class="row g-3 justify-content-between align-items-center">
                                <div class="col-12 col-md">
                                    <h5 class="text-body mb-0">
                                        History Exam Of Student: {{ $student->name }} ( {{ $student->email }} )
                                    </h5>
                                    <h5 class="text-body mt-2 mb-0">
                                        Test: {{ $student->test_desc }} ( {{ $student->test_start_time }} ~ {{ $student->test_end_time }} )
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="p-4">
                                @php
                                    $examSessions = $student->examSessions;
                                @endphp
                                @if(!empty($examSessions))
                                    <div class="row g-4">
                                        @foreach($examSessions as $key => $examSession)
                                            <div class="col-sm-12 col-md-6">
                                                <div class="card border border-primary">
                                                    <div class="card-body">
                                                        <h4 class="card-title">
                                                            {{ $key + 1 }}. Pickup Exam:
                                                            <a href="{{ route('admin.exams.detail', $examSession->exam->id) }}" target="_blank">
                                                                {{ $examSession->exam->title }}
                                                            </a>
                                                        </h4>
                                                        <div class="row pt-1">
                                                            <p class="card-text">
                                                                <b>Exam Skills: </b>
                                                                @foreach($examSession->exam->skills as $skill)
                                                                    <span class="text-gray-500"><b>{{ $skill->type->label() }} &nbsp;</b></span>
                                                                @endforeach
                                                            </p>
                                                        </div>
                                                        <div class="row pt-2">
                                                            <p class="card-text">
                                                                <b>Status: </b>
                                                                <span class="text-gray-500"><b>{{ $examSession->status->label() }}</b></span>
                                                            </p>
                                                        </div>
                                                        <div class="row pt-2">
                                                            <p class="card-text">
                                                                <b>Start Time: </b>
                                                                <span class="text-gray-500"><b>{{ $examSession->created_at }}</b></span>
                                                            </p>
                                                        </div>
                                                        <div class="row pt-2 pb-2">
                                                            <p class="card-text">
                                                                <b>End Time: </b>
                                                                <span class="text-gray-500"><b>{{ $examSession->updated_at }}</b></span>
                                                            </p>
                                                        </div>
                                                        <hr>
                                                        <div class="row pt-2">
                                                            <p class="card-text">
                                                                <b>Submitted Skills Result: </b>
                                                                @php
                                                                    $skillSessions = $examSession->skillSessions;
                                                                @endphp
                                                            </p>
                                                            <div class="row">
                                                                @foreach($skillSessions as $skillSession)
                                                                    <div class="col-sm-6">
                                                                        <div class="card border border-primary">
                                                                            <div class="card-body">
                                                                                <h4 class="card-title">
                                                                                    @if($skillSession->skill->type == \App\Enum\Models\SkillType::WRITING)
                                                                                        <a href="{{ route('admin.histories.skill-detail', [
                                                                                            $test->id,
                                                                                            $student->id,
                                                                                            $skillSession->id,
                                                                                        ]) }}" target="_blank">
                                                                                            {{ $skillSession->skill->type->label() }}
                                                                                        </a>
                                                                                    @else
                                                                                        <a href="#">
                                                                                            {{ $skillSession->skill->type->label() }}
                                                                                        </a>
                                                                                    @endif
                                                                                </h4>
                                                                                <div class="row">
                                                                                    <p class="card-text">
                                                                                        <b>Skill Status: </b>
                                                                                        <span class="text-gray-500"><b>{{ $skillSession->status->label() }}</b></span>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <p class="card-text">
                                                                                        <b>Total Skill questions: </b>
                                                                                        <span class="text-gray-500"><b>{{ $skillSession->total_question ?? 0 }}</b></span>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <p class="card-text">
                                                                                        <b>Total Submitted Skill Answers: </b>
                                                                                        <span class="text-gray-500"><b>{{ $skillSession->total_submitted_answer ?? 0 }}</b></span>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <p class="card-text">
                                                                                        <b>Total Correct Skill Answers: </b>
                                                                                        <span class="text-gray-500"><b>{{ $skillSession->total_correct_answer ?? 0 }}</b></span>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <p class="card-text">
                                                                                        <b>Total Pending Skill Answers: </b>
                                                                                        <span class="text-gray-500"><b>{{ $skillSession->total_pending_answer ?? 0 }}</b></span>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <p class="card-text">
                                                                                        <b>Total Skill Score: </b>
                                                                                        <span class="text-gray-500"><b>{{ $skillSession->total_score ?? 0 }}</b></span>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <p class="card-text">
                                                                                        <b>Total Correct Skill Score: </b>
                                                                                        <span class="text-gray-500"><b>{{ $skillSession->total_correct_score ?? 0 }}</b></span>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <p class="card-text">
                                                                                        <b>Start Skill: </b>
                                                                                        <span class="text-gray-500"><b>{{ $skillSession->created_at }}</b></span>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <p class="card-text">
                                                                                        <b>End Skill: </b>
                                                                                        <span class="text-gray-500"><b>{{ $skillSession->updated_at }}</b></span>
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
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