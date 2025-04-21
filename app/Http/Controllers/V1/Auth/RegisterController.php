<?php

namespace App\Http\Controllers\V1\Auth;

use App\Repositories\Auth\AuthInterface;
use App\Repositories\PasswordReset\PasswordResetInterface;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Exception;

use App\Common\ResponseApi;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ActiveAccountRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResendOtpRequest;
use App\Models\Otp;
use App\Notifications\UserRegisteredSuccessfully;
use App\Repositories\Team\TeamInterface;
use App\Repositories\TeamStadium\TeamStadiumInterface;
use App\Services\OtpService;

/**
 * Class RegisterController.
 *
 * @package namespace App\Http\Controllers\Auth;
 */
class RegisterController extends Controller
{
    public PasswordResetInterface $passwordResetRepository;
    public AuthInterface $authRepository;

    private OtpService $service;

    public function __construct(
        AuthInterface          $authRepository,
        OtpService             $service,
        PasswordResetInterface $passwordResetRepository,
    ) {
        $this->authRepository = $authRepository;
        $this->passwordResetRepository = $passwordResetRepository;
        $this->service = $service;
    }

    /**
     * @param RegisterRequest $request
     * @return Exception|ResponseFactory|Response
     * @throws Exception
     */
    public function register(RegisterRequest $request): Response|Exception|ResponseFactory
    {
        try {
            DB::beginTransaction();

            $email = $request->get('email');

            // Generate OTP
            $otp = $this->service->createOrUpdateOtp($email);

            $data = [
                'name' => $request->get('name'),
                'email' => $email,
                'password' => Hash::make($request->get('password')),
                'is_active' => false,
            ];

            $user = $this->authRepository->getUserByEmail($email);

            if ($user) {
                // set deleted_at null
                $user->restore();

                $user->update($data);
            } else {
                $user = $this->authRepository->register($data);
            }

            if (!$user) {
                DB::rollBack();
                return ResponseApi::unauthorized();
            }

            // send mail active
            $user->notify(new UserRegisteredSuccessfully($otp));
            DB::commit();

            return ResponseApi::created([], __('auth.register_success'));
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * @param ActiveAccountRequest $request
     * @return ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws Exception
     */
    public function verifyOtp(ActiveAccountRequest $request): \Symfony\Component\HttpFoundation\Response|ResponseFactory
    {
        try {
            DB::beginTransaction();

            $email = $request->get('email');
            $otp = $request->get('otp');

            $otpData = Otp::where('email', $email)
                ->where('otp', $otp)
                ->where('expires_at', '>', Carbon::now())
                ->first();

            if (!$otpData) {
                return ResponseApi::bad([], __('auth.otp.invalid_or_expired'));
            }

            $user = $this->authRepository->activeUser([
                'email' => $email,
                'otp' => $otp,
            ]);

            if (!$user) {
                DB::rollBack();
                return ResponseApi::unauthorized();
            }

            $otpData->delete();

            // Generate token for the user
            $token = auth()->login($user);
            $res = [];
            if ($token) {
                $data = $this->respondWithToken($token);

                // save last login
                $this->authRepository->update([
                    'last_login_at' => Carbon::now(),
                ], $user->id);

                $res = $data->original;
                $res['is_active'] = $user->is_active == 1 ? true : false;
                $res['email'] = $user->email;
            }

            DB::commit();

            return ResponseApi::success(__('auth.account_active_success'), $res);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Symfony\Component\HttpFoundation\Response|ResponseFactory
     */
    protected function respondWithToken(string $token): ResponseFactory|\Symfony\Component\HttpFoundation\Response
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Carbon::now()->addMinutes(auth()->factory()->getTTL())->toDateTimeString()
        ]);
    }

    public function resendOtp(ResendOtpRequest $request): \Symfony\Component\HttpFoundation\Response|ResponseFactory
    {
        try {
            $email = $request->get('email');

            $user = $this->authRepository->getUserByEmail($email);

            if (!$user || $user->deleted_at) return ResponseApi::bad([], 'User not found');

            if ($user->is_active) return ResponseApi::bad([], __('auth.account_already_active'));

            // Check limit resend otp
            $otp = Otp::where('email', $email)
                ->where('expires_at', '>', Carbon::now())
                ->first();

            if ($otp) return ResponseApi::bad([], __('auth.otp.limit'));

            $otp = $this->service->createOrUpdateOtp($email);

            // send mail otp
            $user->notify(new UserRegisteredSuccessfully($otp));

            return ResponseApi::success(__('auth.otp.resend_success'));
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
