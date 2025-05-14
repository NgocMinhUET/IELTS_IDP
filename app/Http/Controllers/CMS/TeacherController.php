<?php

namespace App\Http\Controllers\CMS;

use App\Http\Requests\Teacher\StoreTeacherRequest;
use App\Services\CMS\TeacherService;

class TeacherController extends CMSController
{
    private array $rootBreadcrumbs = ['Teacher' => null];

    public function __construct(
        public TeacherService $teacherService,
    ) {
        $this->rootBreadcrumbs['Teacher'] = route('admin.teachers.index');
    }

    public function index()
    {
        $this->breadcrumbs = array_merge($this->rootBreadcrumbs, [
            'List' => null
        ]);

        $teachers = $this->teacherService->getPaginateTeachers();

        return view('teachers.index', compact('teachers'));
    }


    public function create()
    {
        $this->breadcrumbs = array_merge($this->rootBreadcrumbs, [
            'Create' => null
        ]);

        return view('teachers.create');
    }

    public function store(StoreTeacherRequest $request)
    {
        $teacherPayload = $request->only(['name', 'email', 'password', 'is_active']);

        $teacher = $this->teacherService->storeTeacher($teacherPayload);

        return redirect()
            ->route('admin.teachers.detail', $teacher->id)
            ->with('success', 'Teacher created. The password is: ' . $teacherPayload['password'] . ', 
            this password not show again. Please save it in a safe place.');
    }

    public function detail($id)
    {
        $this->breadcrumbs = array_merge($this->rootBreadcrumbs, [
            'Detail' => null
        ]);

        $teacher = $this->teacherService->getTeacher($id);

        return view('teachers.create', compact('teacher'));
    }

    public function update(StoreTeacherRequest $request, $id)
    {
        $teacherPayload = $request->only(['name', 'email', 'new_password', 'is_active']);

        $teacher = $this->teacherService->updateTeacher($teacherPayload, $id);

        $alertMessage = 'Teacher updated.';
        if (!empty($teacherPayload['new_password'])) {
            $alertMessage .= ' The new password is: ' . $teacherPayload['new_password'] . ',
                this password not show again. Please save it in a safe place.';
        }

        return redirect()
            ->route('admin.teachers.detail', $teacher->id)
            ->with('success', $alertMessage);
    }
}