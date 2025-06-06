@extends('layouts.master')

@php
    $firstPartId = array_keys($skillQuestionsByPart)[0] ?? 0;
@endphp
@section('contents')
    <div class="mt-4" id="tests">
        <x-spinner></x-spinner>

        <div class="p-4">
            <div class="sticky-top z-index-fixed" style="top: 64px;">
                <ul class="nav nav-underline fs-9 d-flex w-100 justify-content-between" id="partTab" role="tablist">
                <ul class="nav nav-underline fs-9 d-flex w-100 justify-content-between border-bottom" id="partTab" role="tablist">
                    @foreach($skillQuestionsByPart as $key => $part)
                        <li class="nav-item flex-fill text-center">
                            <a class="nav-link {{ $firstPartId == $key ? 'active' : '' }}" id="part-{{ $key }}-tab" data-bs-toggle="tab" href="#tab-part-{{ $key }}"
                               role="tab" aria-controls="tab-part-{{ $key }}" aria-selected="true">Part {{ $part['part']->title }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="tab-content mt-3" id="partTabContent">
                @foreach($skillQuestionsByPart as $key => $part)
                    <div class="tab-pane fade  {{ $firstPartId == $key ? 'show active' : '' }}" id="tab-part-{{ $key }}"
                         role="tabpanel" aria-labelledby="part-{{ $key }}-tab"
                    >
                        @foreach($questions = $part['questions'] as $question)
                            @php
                                $questionModel = $question->getTable();
                                $answer = $skillAnswers->where('question_model', $questionModel)
                                            ->where('question_id', $question->id)
                                            ->first();
                            @endphp
                            <div class="container py-4">
                                <div class="card mb-2">
                                    <div class="mt-4 mx-4">
                                        <div class="row">
                                            <div class="d-flex">
                                                <span class="input-group-text">Max score: {{ $question->score ?? 'not set' }}</span>
                                                <span class="input-group-text">Current score: {{ $answer['score'] ?? 'not set' }}</span>
                                                @if($answer)
                                                    <input type="number" class="form-control pt-3 pb-3 me-2" name="desc"
                                                           placeholder="Enter new score" max="{{ $question->score }}" min="0"
                                                    >
                                                    <button type="submit"
                                                            class="btn btn-primary pt-3 pb-3 submit-score-btn"
                                                            data-url="{{ route('admin.histories.update-score', $answer['id']) }}"
                                                    >Save</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-1">
                                            <span class="form-label"><b>Question: </b></span>
                                            <div class="card pt-2 pb-4 px-2">
                                                {!! $question->content !!}
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <span class="form-label"><b>Answer: </b></span>
                                            @if($answer)
                                                @if($answer['question_type'] == \App\Enum\QuestionTypeAPI::SPEAKING->value)
                                                    @php
                                                        $answerArr = json_decode($answer['answer'], true);
                                                        $audioUrl = '';
                                                        if (!empty($answerArr)) {
                                                            if ($answerArr['storage'] == 'minio') {
                                                                config([
                                                                    'filesystems.disks.minio.endpoint' =>
                                                                    config('filesystems.disks.minio.access_endpoint')
                                                                ]);
                                                            }
                                                            $audioUrl = \Illuminate\Support\Facades\Storage::disk($answerArr['storage'])
                                                                ->temporaryUrl($answerArr['path'], now()->addMinutes(60));
                                                        }
                                                    @endphp
                                                    <div>
                                                        @if(!empty($audioUrl))
                                                            <audio controls>
                                                                <source src="{{ $audioUrl }}" type="audio/webm">
                                                                Your browser does not support the audio element.
                                                            </audio>
                                                        @else
                                                            No answer submit
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="card pt-2 pb-4 px-2">
                                                        {!! $answer['answer'] !!}
                                                    </div>
                                                @endif
                                            @else
                                                <span class="text-warning form-label">No answer submit</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    const globalSpinner = document.getElementById('global-spinner');
    const alertContainer = document.getElementById('alert-container');
    const testContainer = document.getElementById('tests');

    function showSuccessAlert(message) {
        const alert = document.createElement('div');
        alert.className = "alert alert-subtle-success alert-dismissible fade show";
        alert.setAttribute('role', 'alert');
        alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
        testContainer.before(alert);
    }

    document.querySelectorAll('.submit-score-btn').forEach(button => {
        button.addEventListener('click', async function () {
            const container = this.closest('.d-flex'); // tìm block cha chứa input và nút
            const input = container.querySelector('input[type="number"]');
            const score = parseFloat(input?.value);
            const min = parseFloat(input.min);
            const max = parseFloat(input.max);

            if (isNaN(score)) {
                alert('Please enter a valid number.');
                return;
            }
            if (score < min || score > max) {
                alert(`Score must be between ${min} and ${max}.`);
                return;
            }

            const url = this.dataset.url;

            globalSpinner.style.display = 'flex';

            try {
                const response = await fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ score: score }),
                });

                if (!response.ok) throw new Error('Update score failed');

                const currentScoreSpan = Array.from(container.querySelectorAll('.input-group-text'))
                    .find(el => el.textContent.trim().startsWith('Current score:'));
                if (currentScoreSpan) {
                    currentScoreSpan.textContent = `Current score: ${score}`;
                }

                showSuccessAlert('Change score success');
            } catch (e) {
                console.error(e);
                alert('Failed to update status.');
            } finally {
                globalSpinner.style.display = 'none';
            }
        });
    });

    document.querySelectorAll('#partTab a[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
</script>
@endsection