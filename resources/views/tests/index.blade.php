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
                                            <th class="align-middle" scope="col" style="width:20%; min-width:200px;">DESCRIPTION</th>
                                            <th class="align-middle pe-3" scope="col" style="width:20%; min-width:200px;">EXAMS</th>
                                            <th class="align-middle" scope="col" style="width:20%;">TIME</th>
                                            <th class="align-middle" scope="col" style="width:20%;">CREATED BY</th>
                                            <th class="align-middle" scope="col" style="width:10%;">STATUS</th>
                                            <th class="sort align-middle text-end" scope="col" data-sort="created_at" style="width:10%; min-width:200px;">CREATED AT</th>
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
                                                    <h6>{{ $test->exams_count }}</h6>
                                                </td>
                                                <td class="align-middle white-space-nowrap">
                                                    {{ $test->start_time }} ~ {{ $test->end_time }}
                                                </td>
                                                <td class="align-middle white-space-nowrap">
                                                    @php
                                                        $createdBy = $test->createdBy;
                                                        $createdByTxt = '';
                                                        if ($createdBy) {
                                                            $createdByTxt = $createdBy->name . '( ' . $createdBy->email . ' )';
                                                        }
                                                    @endphp
                                                    <h6>{{ $createdByTxt  }}</h6>
                                                </td>

                                                @admin
                                                <td class="align-middle white-space-nowrap">
                                                    <select class="form-select form-select-sm status-select"
                                                            data-id="{{ $test->id }}"
                                                            data-url="{{ route('admin.tests.status', $test->id) }}">
                                                        @foreach(\App\Enum\Models\ApproveStatus::cases() as $status)
                                                            <option value="{{ $status->value }}" {{ $test->approve_status == $status ? 'selected' : '' }}>
                                                                {{ ucfirst($status->label()) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="spinner-border spinner-border-sm text-primary d-none ms-2" role="status"></div>
                                                </td>
                                                @endadmin

                                                @teacher
                                                <td class="align-middle white-space-nowrap">
                                                    <h6 class="{{ $test->approve_status->textColor() }}">{{ $test->approve_status->label() }}</h6>
                                                </td>
                                                @endteacher

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
                    console.error(e);
                    alert('Failed to update status.');
                    this.value = previousValue;
                } finally {
                    globalSpinner.style.display = 'none';
                }
            });
        });
    </script>
@endsection