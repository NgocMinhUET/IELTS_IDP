<?php

namespace App\Repositories\Admin;

use App\Enum\UserRole;
use App\Models\Admin;
use App\Repositories\BaseRepository;

class AdminRepository extends BaseRepository implements AdminInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Admin::class;
    }

    public function getPaginateTeachers()
    {
        return $this->model->where('role', UserRole::TEACHER)
            ->with('createdBy')
            ->paginate(10);
    }
}