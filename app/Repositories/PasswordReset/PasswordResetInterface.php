<?php

namespace App\Repositories\PasswordReset;

use App\Repositories\BaseInterface;

interface PasswordResetInterface extends BaseInterface
{
    /**
     * @param string $token
     * @return mixed
     */
    public function findToken(string $token): mixed;

    /**
     * @param string $email
     * @return mixed
     */
    public function getByEmail(string $email): mixed;

    /**
     * @param array $params
     * @return mixed
     */
    public function createOrUpdate(array $params): mixed;

    /**
     * @param array $params
     * @return mixed
     */
    public function getDataByTokenAndEmail(array $params): mixed;

    /**
     * @param array $params
     * @param $user
     * @return mixed
     */
    public function forgot(array $params, $user): bool;

    /**
     * @param array $params
     * @return mixed
     */
    public function checkResetPassword(array $params): mixed;

    /**
     * @param int $id
     * @return int
     */
    public function deleteResetPassword(int $id): int;
}
