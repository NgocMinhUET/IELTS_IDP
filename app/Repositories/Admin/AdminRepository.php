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

    public function getPaginateTeachers($search)
    {
        $query = $this->model->where('role', UserRole::TEACHER);

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
                $query->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        return $query->with('createdBy')
            ->paginate(10);
    }
}