<div class="modal fade" id="examModal" tabindex="-1" aria-labelledby="examModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pickup Exam</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" id="exam-search" placeholder="Search title ...">
                </div>

                <!-- Select All Checkbox -->
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" value="" id="select-all-checkbox">
                    <label class="form-check-label fw-semibold" for="select-all-checkbox">
                        Select All
                    </label>
                </div>

                <div class="list-group" id="exam-list">
                    @foreach($exams as $exam)
                        @php
                            $skills = $exam->skills;
                            $skillTxt = ' ';

                            foreach ($skills as $skill) {
                                $skillTxt .= $skill->type->label() . ' ';
                            }
                        @endphp
                        <label class="list-group-item d-flex align-items-center">
                            <input class="form-check-input me-2 exam-checkbox" type="checkbox"
                                   value="{{ $exam->id }}" data-name="{{ $exam->title }}">
                            <span class="exam-title">{{ $exam->title }} ({{ $skillTxt }})</span>
                        </label>
                    @endforeach
                </div>
                <div id="exam-placeholder" class="text-muted mt-3 d-none">Not found.</div>
            </div>
            <div class="modal-footer">
                <button type="button" id="confirm-exam-select" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    const searchInput = document.getElementById('exam-search');
    const selectAllCheckbox = document.getElementById('select-all-checkbox');
    const examListItems = document.querySelectorAll('#exam-list .list-group-item');
    const placeholder = document.getElementById('exam-placeholder');

    // Search filter
    searchInput.addEventListener('input', function () {
        const keyword = this.value.trim().toLowerCase();
        let hasResult = false;

        examListItems.forEach(item => {
            const text = item.querySelector('.exam-title').textContent.toLowerCase();
            const isVisible = text.includes(keyword);
            item.classList.toggle('d-none', !isVisible);
            if (isVisible) hasResult = true;
        });

        placeholder.classList.toggle('d-none', hasResult);
        updateSelectAllCheckbox();
    });

    // Toggle Select All
    selectAllCheckbox.addEventListener('change', function () {
        const shouldCheck = this.checked;
        document.querySelectorAll('.exam-checkbox').forEach(cb => {
            if (!cb.closest('.d-none')) cb.checked = shouldCheck;
        });
    });

    // Update Select All when individual checkboxes change
    document.querySelectorAll('.exam-checkbox').forEach(cb => {
        cb.addEventListener('change', updateSelectAllCheckbox);
    });

    function updateSelectAllCheckbox() {
        const visibleCheckboxes = Array.from(document.querySelectorAll('.exam-checkbox'))
            .filter(cb => !cb.closest('.d-none'));

        const allChecked = visibleCheckboxes.length > 0 &&
            visibleCheckboxes.every(cb => cb.checked);

        selectAllCheckbox.checked = allChecked;
        selectAllCheckbox.indeterminate = !allChecked && visibleCheckboxes.some(cb => cb.checked);
    }
</script>