<?php

namespace App\Repositories\Admin;

use App\Repositories\BaseInterface;

interface AdminInterface extends BaseInterface
{
    public function getPaginateTeachers($search);
}
