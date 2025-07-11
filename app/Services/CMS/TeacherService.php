<?php

namespace App\Services\CMS;

use App\Enum\UserRole;
use App\Repositories\Admin\AdminInterface;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TeacherService extends BaseService
{
    public function __construct(
        public AdminInterface $adminRepository,
    ) {}

    public function getPaginateTeachers(Request $request)
    {
        return $this->adminRepository->getPaginateTeachers($request->input('search'));
    }

    public function getTeacher($id)
    {
        return $this->adminRepository->find($id);
    }

    public function storeTeacher($teacherPayload)
    {
        $teacherPayload['password'] = Hash::make($teacherPayload['password']);
        $teacherPayload['role'] = UserRole::TEACHER->value;
        $teacherPayload['created_by'] = Auth::user()->id;

        return $this->adminRepository->create($teacherPayload);
    }

    public function updateTeacher($teacherPayload, $id)
    {
        if (!empty($teacherPayload['new_password'])) {
            $teacherPayload['password'] = Hash::make($teacherPayload['new_password']);
            unset($teacherPayload['new_password']);
        }

        return $this->adminRepository->update($teacherPayload, $id);
    }
}
