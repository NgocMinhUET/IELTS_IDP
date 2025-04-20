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
    public function getDataPaginate();
    public function createUser(array $params);
}
