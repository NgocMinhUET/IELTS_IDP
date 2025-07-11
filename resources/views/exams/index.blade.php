@extends('layouts.master')

@section('contents')
    <div class="mt-4" id="exams">
        <x-spinner></x-spinner>

        <div class="row align-items-center justify-content-between mt-3 g-3">
            <div class="col col-auto">
                <div class="search-box">
                    <form class="position-relative" method="GET" action="{{ route('admin.exams.index') }}">
                        <input class="form-control search-input search" type="search" name="search"
                               placeholder="Search exams" aria-label="Search" value="{{ request()->get('search', '') }}" />
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
                                            <th class="align-middle" scope="col" style="width:18%; min-width:200px;">TITLE</th>
                                            <th class="align-middle pe-3" scope="col" style="width:18%; min-width:200px;">DESCRIPTION</th>
                                            <th class="align-middle" scope="col" style="width:18%;">SKILLS</th>
                                            <th class="align-middle" scope="col" style="width:18%;">CREATED BY</th>
                                            <th class="align-middle" scope="col" style="width:8%;">TEST ASSIGNED</th>
                                            <th class="align-middle" scope="col" style="width:10%;">STATUS</th>
                                            <th class="sort align-middle text-end" scope="col" data-sort="created_at" style="width:10%; min-width:200px;">CREATED AT</th>
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
                                                    <h6>{{ $skillList }}</h6>
                                                </td>
                                                <td class="align-middle white-space-nowrap">
                                                    @php
                                                        $createdBy = $exam->createdBy;
                                                        $createdByTxt = '';
                                                        if ($createdBy) {
                                                            $createdByTxt = $createdBy->name . '( ' . $createdBy->email . ' )';
                                                        }
                                                    @endphp
                                                    <h6>{{ $createdByTxt  }}</h6>
                                                </td>
                                                <td class="align-middle white-space-nowrap">
                                                    <h6>{{ $exam->tests_count }}</h6>
                                                </td>
                                                @admin
                                                <td class="align-middle white-space-nowrap">
                                                    @if(!$exam->tests_count)
                                                        <select class="form-select form-select-sm status-select"
                                                                data-id="{{ $exam->id }}"
                                                                data-url="{{ route('admin.exams.status', $exam->id) }}">
                                                            @foreach(\App\Enum\Models\ApproveStatus::cases() as $status)
                                                                <option value="{{ $status->value }}" {{ $exam->approve_status == $status ? 'selected' : '' }}>
                                                                    {{ ucfirst($status->label()) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <div class="spinner-border spinner-border-sm text-primary d-none ms-2" role="status"></div>
                                                    @else
                                                        <h6 class="{{ $exam->approve_status->textColor() }}">{{ $exam->approve_status->label() }}</h6>
                                                    @endif
                                                </td>
                                                @endadmin

                                                @teacher
                                                <td class="align-middle white-space-nowrap">
                                                    <h6 class="{{ $exam->approve_status->textColor() }}">{{ $exam->approve_status->label() }}</h6>
                                                </td>
                                                @endteacher

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

@section('js')
<script>
    const globalSpinner = document.getElementById('global-spinner');
    const alertContainer = document.getElementById('alert-container');
    const examContainer = document.getElementById('exams');

    function showSuccessAlert(message) {
        const alert = document.createElement('div');
        alert.className = "alert alert-subtle-success alert-dismissible fade show";
        alert.setAttribute('role', 'alert');
        alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
        examContainer.before(alert);
    }

    document.querySelectorAll('.status-select').forEach(select => {
        let previousValue = select.value;

        select.addEventListener('focus', () => {
            previousValue = select.value;
        });

        select.addEventListener('change', async function () {
            const newStatus = this.value;
            const url = this.dataset.url;

            globalSpinner.style.display = 'flex';

            try {
                const response = await fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ status: newStatus }),
                });

                if (response.status !== 200) throw new Error('Update failed');

                showSuccessAlert('Change status success');
                previousValue = newStatus;
            } catch (e) {
                alert('Failed to update status.');
                this.value = previousValue;
            } finally {
                globalSpinner.style.display = 'none';
            }
        });
    });
</script>
@endsection