<div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pickup Students</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="student-search" placeholder="Search code, name or email ...">
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="student-prefix" placeholder="Search prefix ...">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle">
                        <thead>
                        <tr>
                            <th class="text-center"><input type="checkbox" class="form-check-input" id="select-all-students"></th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Prefix</th>
                        </tr>
                        </thead>
                        <tbody id="student-list">
                        @foreach($students as $student)
                            <tr
                                    data-code="{{ $student->code }}"
                                    data-name="{{ $student->name }}"
                                    data-email="{{ $student->email }}"
                                    data-prefix="{{ $student->search_prefix }}"
                            >
                                <td class="text-center"><input class="form-check-input student-checkbox" type="checkbox" value="{{ $student->id }}"></td>
                                <td>{{ $student->code }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->search_prefix }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div id="student-placeholder" class="text-muted mt-3 d-none">Not found.</div>
            </div>

            <div class="modal-footer">
                <button type="button" id="confirm-student-select" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('student-search');
        const prefixInput = document.getElementById('student-prefix');
        const studentRows = document.querySelectorAll('#student-list tr');

        function filterStudents() {
            const keyword = searchInput.value.trim().toLowerCase();
            const prefix = prefixInput.value.trim().toLowerCase();
            let hasResult = false;

            studentRows.forEach(row => {
                const code = row.dataset.code.toLowerCase();
                const name = row.dataset.name.toLowerCase();
                const email = row.dataset.email.toLowerCase();
                const studentPrefix = row.dataset.prefix.toLowerCase();

                const matchesKeyword = keyword === '' || code.includes(keyword) || name.includes(keyword) || email.includes(keyword);
                const matchesPrefix = prefix === '' || studentPrefix.includes(prefix);

                const visible = matchesKeyword && matchesPrefix;
                row.classList.toggle('d-none', !visible);
                if (visible) hasResult = true;
            });

            document.getElementById('student-placeholder').classList.toggle('d-none', hasResult);
        }

        searchInput.addEventListener('input', filterStudents);
        prefixInput.addEventListener('input', filterStudents);

        // Select all toggle
        document.getElementById('select-all-students').addEventListener('change', function () {
            const visibleCheckboxes = document.querySelectorAll('#student-list tr:not(.d-none) .student-checkbox');
            visibleCheckboxes.forEach(cb => cb.checked = this.checked);
        });
    });

    // // Confirm Selection
    // document.getElementById('confirm-student-select').addEventListener('click', () => {
    //     document.querySelectorAll('#student-list .student-checkbox').forEach(cb => {
    //         const id = cb.value;
    //         const name = cb.dataset.name;
    //         if (cb.checked) {
    //             selectedStudents.set(id, name);
    //         } else {
    //             selectedStudents.delete(id);
    //         }
    //     });
    //
    //     renderSelectedStudents();
    //     bootstrap.Modal.getInstance(document.getElementById('studentModal')).hide();
    // });
    //
    // When opening modal: sync checked
    document.getElementById('studentModal').addEventListener('show.bs.modal', () => {
        document.getElementById('student-prefix').value = '';
        document.getElementById('student-search').value = '';

        document.querySelectorAll('#student-list tr').forEach(item => {
            item.classList.remove('d-none');
        });

        document.getElementById('student-placeholder').classList.add('d-none');
        document.getElementById('select-all-students').checked = false;

        document.querySelectorAll('#student-list .student-checkbox').forEach(cb => {
            cb.checked = selectedStudents.has(cb.value);
        });
    });

</script>