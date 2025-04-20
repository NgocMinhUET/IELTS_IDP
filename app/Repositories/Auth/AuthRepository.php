<?php

namespace App\Repositories\Auth;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Auth\AuthInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\BaseRepository;

/**
 * Class AuthRepositories
 * extends BaseRepository
 * implements AuthRepository
 * @package namespace App\Repositories\Eloquent;
 */
class AuthRepository extends BaseRepository implements AuthInterface
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
     * Register
     *
     * @param array $data
     * @return LengthAwarePaginator|Collection|mixed
     * @throws ValidatorException
     */
    public function register(array $data): mixed
    {
        return $this->create($data);
    }

    /**
     * Get user by email
     *
     * @param string $email
     * @return LengthAwarePaginator|Collection|mixed
     */
    public function getUserByEmail(string $email): mixed
    {
        return $this->model->withTrashed()->where('email', $email)->first();
    }

    /**
     * Get user by email
     *
     * @param array $params
     * @return LengthAwarePaginator|Collection|mixed
     */
    public function resetPassword(array $params): mixed
    {
        $user = $this->model->where('email', $params['email'])->first();
        $user->password = Hash::make($params['password']);

        if ($user->is_active == false) {
            $user->is_active = true;
            $user->email_verified_at = now();
        }

        return $user->save();
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function active(array $params): mixed
    {
        $data = $this->model
            ->where('email', $params['email'])
            ->where('is_active', false)
            ->first();

        if (!$data) {
            return false;
        }

        $data->is_active = true;
        $data->email_verified_at = now();
        $data->save();

        return $data;
    }

    /**
     * Active User
     * @param $params
     * @return mixed
     */
    public function activeUser($params): mixed
    {
        return $this->active($params);
    }

    public function updateUserByEmail(array $params): mixed
    {
        $email = $params['email'];
        $params['password'] = Hash::make($params['password']);
        $params['activation_code'] = '';
        unset($params['email']);
        unset($params['password_confirm']);

        $user = $this->model->where(['email' => $email])->first();
        $userId = $user->id;
        $user->update($params);
        return $this->model->find($userId);
    }

    public function changePassword($newPassword, $userId): mixed
    {
        $user = $this->model->find($userId);
        $user->password = Hash::make($newPassword);
        $user->save();
        return $user;
    }
}
