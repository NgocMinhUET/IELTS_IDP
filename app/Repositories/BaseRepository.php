<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository as BaseRepositories;

abstract class BaseRepository extends BaseRepositories implements BaseInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    abstract public function model(): string;

    /**
     * @param $params
     * @return mixed
     */
    public function findById($id): mixed
    {
        return $this->model->find($id);
    }
}
