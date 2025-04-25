@extends('layouts.master')

<style>
    .hover-shadow:hover {
        box-shadow: 0 0 10px rgba(0,0,0,0.12) !important;
        transform: translateY(-2px);
    }
    .transition {
        transition: all 0.2s ease-in-out;
    }
</style>

@section('contents')
    <div class="mt-4">
        <div class="row g-4">
            <div class="col-12 col-xl-10 order-1 order-xl-0">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item border-top" id="phoenix-buttons">
                        <h2 class="accordion-header" id="headingOne">

                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                How long does it take to ship my order?

                            </button>
                        </h2>
                        <div class="accordion-collapse collapse show" id="collapseOne" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                            <div class="accordion-body pt-0">
                                <strong>This is the first item's accordion body.</strong>
                                It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">

                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                How long does it take to ship my order?

                            </button>
                        </h2>
                        <div class="accordion-collapse collapse" id="collapseTwo" aria-labelledby="headingTwo" data-bs-parent="#accordionExample" style="">
                            <div class="accordion-body pt-0">
                                <strong>This is the second item's accordion body.</strong>
                                It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">

                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                How long does it take to ship my order?

                            </button>
                        </h2>
                        <div class="accordion-collapse collapse" id="collapseThree" aria-labelledby="headingThree" data-bs-parent="#accordionExample" style="">
                            <div class="accordion-body pt-0">
                                <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                            </div>
                        </div>
                    </div>

                    @foreach($choiceQuestions as $key => $choiceQuestion)
                        <div class="accordion-item" id="CQ_{{$key}}">
                            <h2 class="accordion-header" id="heading_{{$key}}">

                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{$key}}" aria-expanded="false" aria-controls="collapse_{{$key}}">
                                    {{ $choiceQuestion->title }}
                                </button>
                            </h2>
                            <div class="accordion-collapse collapse" id="collapse_{{$key}}" aria-labelledby="heading_{{$key}}" data-bs-parent="#accordionExample" style="">
                                <div class="accordion-body pt-0">
                                    @foreach ($choiceQuestion->choiceSubQuestions as $index => $sub)
                                        <div class="container py-4">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="mb-2 d-flex justify-content-between align-items-center">
                                                        <h6 class="mb-0">{{ $sub->question }}</h6>
                                                        <small class="text-muted"> MIN: {{ $sub->min_option }} MAX: {{ $sub->max_option }}</small>
                                                    </div>

                                                    <form>
                                                        <div class="row g-2 mt-2">
                                                            @foreach ($sub->choiceOptions as $i => $answer)
                                                                <div class="col-md-6">
                                                                    <div class="form-check border rounded p-3 d-flex align-items-start gap-2">
                                                                        <input
                                                                                class="form-check-input"
                                                                                type="{{ $sub->max_option > 1 ? 'checkbox' : 'radio' }}"
                                                                                name="sub_question_{{ $index }}[]"
                                                                                value="{{ $answer->id }}"
                                                                                id="answer_{{ $sub->id }}_{{ $i }}"
                                                                                disabled
                                                                                {{ $answer->is_correct ? 'checked' : '' }}
                                                                        >
                                                                        <label class="form-check-label w-100" for="answer_{{ $sub->id }}_{{ $i }}">
                                                                            {{ $answer->answer }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="accordion-item">
                        <div class="container py-4">
                            <h4 class="mb-4">Create new question</h4>
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <a href="{{ route('admin.parts.questions.create', ['type' => 'choice', 'id' => $part->id]) }}" class="text-decoration-none">
                                        <div class="card h-100 shadow-sm border-0 hover-shadow transition">
                                            <div class="card-body text-center">
                                                <div class="fs-2 mb-3">📝</div>
                                                <h5 class="card-title">Chọn đáp án</h5>
                                                <p class="card-text text-muted">Tạo câu hỏi chọn 1 hoặc nhiều đáp án đúng (A, B, C...)</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('questions.create', ['type' => 'fill_in_blank']) }}" class="text-decoration-none">
                                        <div class="card h-100 shadow-sm border-0 hover-shadow transition">
                                            <div class="card-body text-center">
                                                <div class="fs-2 mb-3">✍️</div>
                                                <h5 class="card-title">Điền đáp án</h5>
                                                <p class="card-text text-muted">Tạo câu hỏi dạng điền từ hoặc cụm từ vào chỗ trống</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('questions.create', ['type' => 'drag_drop_image']) }}" class="text-decoration-none">
                                        <div class="card h-100 shadow-sm border-0 hover-shadow transition">
                                            <div class="card-body text-center">
                                                <div class="fs-2 mb-3">🧩</div>
                                                <h5 class="card-title">Kéo thả vào ảnh</h5>
                                                <p class="card-text text-muted">Tạo dạng câu hỏi kéo đáp án vào vị trí trong hình ảnh</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('questions.create', ['type' => 'match_sentence']) }}" class="text-decoration-none">
                                        <div class="card h-100 shadow-sm border-0 hover-shadow transition">
                                            <div class="card-body text-center">
                                                <div class="fs-2 mb-3">🔗</div>
                                                <h5 class="card-title">Nối câu / đoạn</h5>
                                                <p class="card-text text-muted">Ghép nối các câu hoặc đoạn văn tương ứng</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('questions.create', ['type' => 'listening']) }}" class="text-decoration-none">
                                        <div class="card h-100 shadow-sm border-0 hover-shadow transition">
                                            <div class="card-body text-center">
                                                <div class="fs-2 mb-3">🎧</div>
                                                <h5 class="card-title">Nghe & trả lời</h5>
                                                <p class="card-text text-muted">Câu hỏi dạng nghe đoạn âm thanh rồi chọn đáp án</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-2">
                <div class="position-sticky mt-xl-4" style="top: 80px;">
                    <h5 class="lh-1">On this page </h5>
                    <hr />
                    <ul class="nav nav-vertical flex-column doc-nav" data-doc-nav="data-doc-nav">
                        <li class="nav-item"> <a class="nav-link" href="#phoenix-buttons">Phoenix Buttons</a>
                        </li>
                        @foreach($choiceQuestions as $key => $choiceQuestion)
                        <li class="nav-item">
                            <a class="nav-link" href="#CQ_{{$key}}">
                                {{ $choiceQuestion->title }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

@endsection
                    