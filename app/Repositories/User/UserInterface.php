<?php

namespace App\Repositories\User;

use App\Repositories\BaseInterface;

/**
 * Interface UsersRepository.
 *
 * @package namespace App\Contracts\Repositories;
 */
interface UserInterface extends BaseInterface
{
    public function getPaginateStudents();
    public function createUser(array $params);

    public function countActiveUserByIds(array $userIds);
}
