<?php

namespace App\Repositories\PasswordReset;

use App\Enum\Auth;
use App\Models\PasswordReset;
use App\Notifications\ForgotPassword;
use App\Repositories\BaseRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Response;

class PasswordResetRepository extends BaseRepository implements PasswordResetInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return PasswordReset::class;
    }

    /**
     * Get by token
     *
     * @param string $token
     * @return mixed
     */
    public function findToken(string $token): mixed
    {
        return $this->model->where('token', '=', $token)->first();
    }

    /**
     * Get by email
     *
     * @param string $email
     * @return mixed
     */
    public function getByEmail(string $email): mixed

    {
        return $this->model->where('token', '=', $email)->first();
    }

    /**
     * Create or update
     *
     * @param array $params
     * @return mixed
     * @throws ValidatorException
     */
    public function createOrUpdate(array $params): mixed
    {
        return $this->updateOrCreate(
            [
                'email' => $params['email']
            ],
            [
                'email' => $params['email'],
                'token' => $params['otp'],
            ]
        );
    }

    /**
     * Get data by token and email
     *
     * @param array $params
     * @return mixed
     */
    public function getDataByTokenAndEmail(array $params): mixed
    {
        return $this->model
            ->where('email', $params['email'])
            ->where('token', $params['otp'])
            ->first();
    }

    /**
     * Forgot
     *
     * @param array $params
     * @param       $user
     * @return array
     * @throws ValidatorException
     */
    public function forgot(array $params, $user): bool
    {
        try {
            $createOrUpdate = $this->createOrUpdate($params);
            $data = $params;

            if ($createOrUpdate) {
                Notification::send($user, new ForgotPassword($data));
                return true;
            }

            return false;
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return false;
        }
    }

    /**
     * Reset password
     *
     * @param array $params
     * @return array
     */
    public function checkResetPassword(array $params): mixed
    {
        $passwordReset = $this->getDataByTokenAndEmail($params);

        if (!$passwordReset) return false;

        if (Carbon::parse($passwordReset->updated_at)->addHours(Auth::RESET_PASSWORD_EXPIRE->value)->isPast()) {
            $passwordReset->delete();

            return false;
        }

        return [
            'success' => true,
            'data' => $passwordReset
        ];
    }

    /**
     * Delete
     *
     * @param int $id
     * @return int
     */
    public function deleteResetPassword(int $id): int
    {
        return $this->delete($id);
    }
}
