@php
    $isExamRoot = false;
    $isTestRoot = false;
    if (isset($breadcrumbs)) {
        if (array_key_exists('Exam', $breadcrumbs)) {
            $isExamRoot = true;
        } else if (array_key_exists('Test', $breadcrumbs)) {
            $isTestRoot = true;
        }
    }
@endphp

<nav class="navbar navbar-vertical navbar-expand-lg">
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <!-- scrollbar removed-->
        <div class="navbar-vertical-content">
            <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                <li class="nav-item">
                    <!-- parent pages-->
                    <div class="nav-item-wrapper">
                        <a class="nav-link dropdown-indicator label-1 @if ($isTestRoot) active @endif"
                           href="#nv-test"
                           role="button"
                           data-bs-toggle="collapse"
                           aria-expanded="@if (Route::is('admin.tests.*')) true @else false @endif"
                           aria-controls="nv-test"
                        >
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper">
                                    <span class="fas fa-caret-right dropdown-indicator-icon"></span>
                                </div>
                                <span class="nav-link-icon">
                                    <span data-feather="compass"></span>
                                </span>
                                <span class="nav-link-text">Test</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent @if (Route::is('admin.tests.*')) show @endif"
                                data-bs-parent="#navbarVerticalCollapse" id="nv-test">
                                <li class="nav-item">
                                    <a class="nav-link @if(Route::is('admin.tests.index')) active @endif"
                                       href="{{ route('admin.tests.index') }}"
                                    >
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">List</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link @if(Route::is('admin.tests.create')) active @endif"
                                       href="{{ route('admin.tests.create') }}"
                                    >
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Create</span>
                                        </div>
                                    </a>
                                </li>

                                @admin
                                <li class="nav-item">
                                    <a class="nav-link @if(Route::is('admin.tests.create')) active @endif"
                                       href="{{ route('admin.tests.create') }}"
                                    >
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Pending Test</span>
                                        </div>
                                    </a>
                                </li>
                                @endadmin
                            </ul>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <div class="nav-item-wrapper">
                        <a class="nav-link dropdown-indicator label-1 @if ($isExamRoot) active @endif"
                           href="#nv-exam"
                           role="button"
                           data-bs-toggle="collapse"
                           aria-expanded="@if (Route::is('admin.exams.*')) true @else false @endif"
                           aria-controls="nv-exam"
                        >
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper">
                                    <span class="fas fa-caret-right dropdown-indicator-icon"></span>
                                </div>
                                <span class="nav-link-icon">
                                    <span data-feather="book-open"></span>
                                </span>
                                <span class="nav-link-text">Exam</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent @if (Route::is('admin.exams.*')) show @endif"
                                data-bs-parent="#navbarVerticalCollapse" id="nv-exam">
                                <li class="nav-item">
                                    <a class="nav-link @if(Route::is('admin.exams.index')) active @endif"
                                       href="{{ route('admin.exams.index') }}"
                                    >
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">List</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link @if(Route::is('admin.exams.create')) active @endif"
                                       href="{{ route('admin.exams.create') }}"
                                    >
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Create</span>
                                        </div>
                                    </a>
                                </li>

                                @admin
                                <li class="nav-item">
                                    <a class="nav-link @if(Route::is('admin.tests.create')) active @endif"
                                       href="{{ route('admin.tests.create') }}"
                                    >
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text">Pending Exam</span>
                                        </div>
                                    </a>
                                </li>
                                @endadmin
                            </ul>
                        </div>
                    </div>
                </li>

                @admin
                <li class="nav-item">
                    <hr class="navbar-vertical-line" />
                    <!-- parent pages-->
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1  @if(Route::is('admin.teachers.*')) active @endif"
                           href="{{ route('admin.teachers.index') }}" role="button" data-bs-toggle="" aria-expanded="false"
                        >
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon">
                                    <span data-feather="users"></span>
                                </span>
                                <span class="nav-link-text-wrapper">
                                    <span class="nav-link-text">Teacher</span>
                                </span>
                            </div>
                        </a>
                    </div>
                </li>
                @endadmin

                <li class="nav-item">
                    <!-- parent pages-->
                    <div class="nav-item-wrapper"><a class="nav-link label-1" href="#" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="users"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Members</span></span>
                            </div>
                        </a>
                    </div>
                </li>

                <li class="nav-item">
                    <!-- parent pages-->
                    <div class="nav-item-wrapper"><a class="nav-link label-1" href="#" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="bell"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Notifications</span></span>
                            </div>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="navbar-vertical-footer">
        <button class="btn navbar-vertical-toggle border-0 fw-semibold w-100 white-space-nowrap d-flex align-items-center"><span class="uil uil-left-arrow-to-left fs-8"></span><span class="uil uil-arrow-from-right fs-8"></span><span class="navbar-vertical-footer-text ms-2">Collapsed View</span></button>
    </div>
</nav>
