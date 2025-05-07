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
                <div class="list-group" id="exam-list">
                    @foreach($exams as $exam)
                        <label class="list-group-item d-flex align-items-center">
                            <input class="form-check-input me-2 exam-checkbox" type="checkbox"
                                   value="{{ $exam->id }}" data-name="{{ $exam->title }}">
                            <span class="exam-title">{{ $exam->title }}</span>
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
    // find by title
    document.getElementById('exam-search').addEventListener('input', function () {
        const keyword = this.value.trim().toLowerCase();
        let hasResult = false;
        console.log(keyword)

        document.querySelectorAll('#exam-list .list-group-item').forEach(item => {
            const text = item.querySelector('.exam-title').textContent.toLowerCase();
            console.log(text)
            const isVisible = text.includes(keyword);
            // item.style.display = isVisible ? '' : 'none';
            item.classList.toggle('d-none', !isVisible);
            if (isVisible) hasResult = true;
        });

        document.getElementById('exam-placeholder').classList.toggle('d-none', hasResult);
    });
</script>