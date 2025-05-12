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

    public function getPaginateStudents(): LengthAwarePaginator
    {
        return $this->model->with('createdBy')
            ->paginate(10);
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

    public function countActiveUserByIds(array $userIds)
    {
        return $this->model->whereIn('id', $userIds)
            ->isActive()->count();
    }
}
