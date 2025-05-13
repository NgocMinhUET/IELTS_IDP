<?php

namespace App\Repositories\Test;

use App\Repositories\BaseInterface;

interface TestInterface extends BaseInterface
{
    public function getAssignedToUserTests($userId);

    public function getAssignedToUserTest($id, $userId);
}
