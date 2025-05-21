<div class="container py-4">
    <h4 class="mb-4">Create new question for part {{ $part->title }} of {{ $part->skill->type->label() }}</h4>

    <div class="row g-4">
    @if($part->skill->type == \App\Enum\Models\SkillType::WRITING)
        <div class="col-md-4">
            <a href="{{ route('admin.parts.questions.create', ['type' => \App\Enum\QuestionType::WRITING, 'id' => $part->id]) }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 hover-shadow transition">
                    <div class="card-body text-center">
                        <div class="fs-3 mb-3">🗒️✍️</div>
                        <h5 class="card-title">Writing Question</h5>
                        <p class="card-text text-muted">Create a question for writing skill.</p>
                    </div>
                </div>
            </a>
        </div>
    @else
        <div class="col-md-4">
            <a href="{{ route('admin.parts.questions.create', ['type' => \App\Enum\QuestionType::CHOICE, 'id' => $part->id]) }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 hover-shadow transition">
                    <div class="card-body text-center">
                        <div class="fs-3 mb-3">🔘✅</div>
                        <h5 class="card-title">Choice Question</h5>
                        <p class="card-text text-muted">Create a question where students select one or multiple correct answers (A, B, C, etc.).</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('admin.parts.questions.create', ['type' => \App\Enum\QuestionType::FILL_IN_CONTENT, 'id' => $part->id]) }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 hover-shadow transition">
                    <div class="card-body text-center">
                        <div class="fs-3 mb-3">🗒️✍️</div>
                        <h5 class="card-title">Paragraph Fill-in-the-Blank</h5>
                        <p class="card-text text-muted">Create a question where students fill in a word or phrase into blanks within a paragraph.</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('admin.parts.questions.create', ['type' => \App\Enum\QuestionType::DRAG_DROP_IN_CONTENT, 'id' => $part->id]) }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 hover-shadow transition">
                    <div class="card-body text-center">
                        <div class="fs-3 mb-3">🗒️🎯</div>
                        <h5 class="card-title">Paragraph Drag-and-Drop</h5>
                        <p class="card-text text-muted">Create a question where students drag and drop answers into the correct positions within a paragraph.</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('admin.parts.questions.create', ['type' => \App\Enum\QuestionType::DRAG_DROP_IMAGE, 'id' => $part->id]) }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 hover-shadow transition">
                    <div class="card-body text-center">
                        <div class="fs-3 mb-3">🖼️🎯</div>
                        <h5 class="card-title">Image Drag-and-Drop</h5>
                        <p class="card-text text-muted">Create a question where students drag and drop answers into specific positions within an image.</p>
                    </div>
                </div>
            </a>
        </div>
{{--        <div class="col-md-4">--}}
{{--            <a href="{{ route('admin.parts.questions.create', ['type' => \App\Enum\QuestionType::FILL_IN_IMAGE, 'id' => $part->id]) }}" class="text-decoration-none">--}}
{{--                <div class="card h-100 shadow-sm border-0 hover-shadow transition">--}}
{{--                    <div class="card-body text-center">--}}
{{--                        <div class="fs-3 mb-3">🖼️✍️</div>--}}
{{--                        <h5 class="card-title">Image Fill-in-the-Blank</h5>--}}
{{--                        <p class="card-text text-muted">Create a question where students fill in a word or phrase into blanks on an image.</p>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </a>--}}
{{--        </div>--}}
    @endif
    </div>
</div>