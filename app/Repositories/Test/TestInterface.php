<?php

namespace App\Repositories\Test;

use App\Repositories\BaseInterface;

interface TestInterface extends BaseInterface
{
    public function getPaginateTests();

    public function getAssignedToUserTests($userId);

    public function getAssignedToUserTest($id, $userId);

    public function getTestHistories($userId);

    public function getPaginateHistoryTests();

    public function getDetailHistoryTest($testId);
}
