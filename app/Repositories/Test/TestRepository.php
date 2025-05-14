<?php

namespace App\Repositories\Test;

use App\Models\Test;
use App\Repositories\BaseRepository;

class TestRepository extends BaseRepository implements TestInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Test::class;
    }

    public function getAssignedToUserTests($userId)
    {
        return $this->getAssignedToUserTestsQuery($userId)
            ->select('id', 'desc', 'start_time', 'end_time')
            ->get();
    }

    private function getAssignedToUserTestsQuery($userId)
    {
        return $this->model
            ->isActive()
            ->whereHas('exams')
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            });
    }

    public function getAssignedToUserTest($id, $userId)
    {
        return $this->getAssignedToUserTestsQuery($userId)
            ->where('id', $id)
            ->first();
    }
}
