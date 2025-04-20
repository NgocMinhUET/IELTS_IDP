<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Models\User;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class UsersRepositories.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class UserRepository extends BaseRepository implements UserInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return User::class;
    }

    /**
     * @return LengthAwarePaginator|Collection|mixed
     */
    public function getDataPaginate(): mixed
    {
        return $this->paginate(\App\Enum\User\User::LIMIT->value);
    }

    /**
     * Create user
     *
     * @param array $params
     * @return LengthAwarePaginator|Collection|mixed
     * @throws ValidatorException
     */
    public function createUser(array $params): mixed
    {
        $create = $this->create($params);
        $create->roles()->sync($params['roles']);

        return $create;
    }
}
