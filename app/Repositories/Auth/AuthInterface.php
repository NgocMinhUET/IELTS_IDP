<?php

namespace App\Repositories\Auth;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface AuthRepository.
 *
 * @package namespace App\Contracts\Repositories;
 */
interface AuthInterface extends RepositoryInterface
{
    public function register(array $data): mixed;

    public function getUserByEmail(string $email): mixed;

    public function resetPassword(array $params): mixed;

    public function updateUserByEmail(array $params): mixed;

    public function active(array $params): mixed;

    public function changePassword($newPassword, $userId): mixed;

    public function activeUser(array $array);
}
