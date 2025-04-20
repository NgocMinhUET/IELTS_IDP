<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * BaseInterface
 */
interface BaseInterface extends RepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string;

    /**
     * @param $params
     * @return mixed
     */
    public function findById($id): mixed;
}
