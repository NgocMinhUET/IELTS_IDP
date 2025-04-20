<?php

namespace App\Http\Controllers\V1\Auth;

use Exception;
use Illuminate\Support\Facades\DB;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

use App\Common\ResponseApi;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Repositories\Auth\AuthInterface;
use App\Repositories\PasswordReset\PasswordResetInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Services\OtpService;
use App\Enum\Auth;
use App\Http\Requests\Auth\ForgotPasswordVerifyOtpRequest;

/**
 * Class ForgotPasswordController.
 *
 * @package namespace App\Http\Controllers\Auth;
 */
class ForgotPasswordController extends Controller
{
    /**
     * @var PasswordResetInterface $passwordResetRepository
     */
    public PasswordResetInterface $passwordResetRepository;

    /**
     * @var AuthInterface $authRepository
     */
    public AuthInterface $authRepository;

    /**
     * @var OtpService $service
     */
    private OtpService $service;

    /**
     * @param AuthInterface $authRepository
     * @param PasswordResetInterface $passwordResetRepository
     */
    public function __construct(
        AuthInterface          $authRepository,
        PasswordResetInterface $passwordResetRepository,
        OtpService             $service
    ) {
        $this->authRepository = $authRepository;
        $this->passwordResetRepository = $passwordResetRepository;
        $this->service = $service;
    }

    /**
     * Forgot password
     *
     * @param ForgotPasswordRequest $request
     * @return Response|ResponseFactory
     * @throws ValidatorException
     */
    public function forgot(ForgotPasswordRequest $request): Response|ResponseFactory
    {
        $email = $request->get('email');

        $user = $this->authRepository->getUserByEmail($email);

        if (!$user || $user->deleted_at) {
            return ResponseApi::dataNotFound();
        }

        $otp = $this->service->generateOtp();

        $params = [
            'email' => $email,
            'otp' => $otp,
        ];

        $this->passwordResetRepository->forgot($params, $user);

        return ResponseApi::success(__('auth.otp.resend_success'));
    }

    /**
     * Check otp
     *
     * @param ForgotPasswordRequest $request
     * @return Response|ResponseFactory
     * @throws ValidatorException
     */
    public function verifyOtp(ForgotPasswordVerifyOtpRequest $request)
    {
        $otp = $request->get('otp');

        try {
            $passwordReset = $this->passwordResetRepository->findToken($otp);
            if (!$passwordReset) {
                return ResponseApi::bad([], __('auth.otp.invalid_or_expired'));
            }

            if (Carbon::parse($passwordReset->updated_at)->addHours(Auth::RESET_PASSWORD_EXPIRE->value)->isPast()) {
                $passwordReset->delete();

                return ResponseApi::bad([], __('auth.otp.invalid_or_expired'));
            }

            return ResponseApi::success();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return ResponseApi::error();
        }
    }

    /**
     * Reset password
     *
     * @param ForgotPasswordRequest $request
     * @return Response|ResponseFactory
     * @throws ValidatorException
     */
    public function reset(ResetPasswordRequest $request)
    {
        try {
            DB::beginTransaction();

            $params = $request->all();

            $passwordReset = $this->passwordResetRepository->checkResetPassword($request->all());
            if (!$passwordReset) {
                return ResponseApi::bad([], __('auth.update_password_fail'));
            }

            $user = $this->authRepository->getUserByEmail($params['email']);
            if (!$user || $user->deleted_at) {
                return ResponseApi::bad([], __('auth.update_password_fail'));
            }

            $params = [
                'email' => $params['email'],
                'password' => $params['new_password'],
            ];

            $resetPass = $this->authRepository->resetPassword($params);
            if (!$resetPass) {
                return ResponseApi::bad([], __('auth.update_password_fail'));
            }

            $this->passwordResetRepository->deleteResetPassword($passwordReset['data']->id);

            DB::commit();

            return ResponseApi::success(__('auth.update_password_success'));
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return ResponseApi::error();
        }
    }
}
