<?php

namespace App\Http\Controllers\V1\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Carbon\Carbon;

use App\Common\ResponseApi;
use App\Http\Controllers\Controller;

use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\User\UpdateProfileRequest;

use App\Models\User;

use App\Repositories\Auth\AuthInterface;
use App\Repositories\PasswordReset\PasswordResetInterface;

/**
 * Class AuthController.
 *
 * @package namespace App\Http\Controllers\Auth;
 */
class AuthController extends Controller
{
    /**
     * @var PasswordResetInterface $passwordResetRepository
     */
    public PasswordResetInterface $passwordResetRepository;

    /**
     * @var AuthInterface $authInterface
     */
    public AuthInterface $authRepository;

    /**
     * DI Construct
     *
     * @param AuthInterface                        $authRepository
     * @param PasswordResetInterface $passwordResetRepository
     */
    public function __construct(
        PasswordResetInterface $passwordResetRepository,
        AuthInterface $authRepository,
    ) {
        $this->passwordResetRepository = $passwordResetRepository;
        $this->authRepository = $authRepository;
    }

    /**
     * @param LoginRequest $request
     * @return ResponseFactory|Response
     */
    public function login(LoginRequest $request): ResponseFactory|Response
    {
        $credentials = $request->only(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return ResponseApi::unauthorized(__('auth.login_failed'));
        }

        $user = User::where('email', $request->email)->first();

        if (!$user->is_active) {
            return ResponseApi::unauthorized(__('auth.login_failed'));
        }

        $data = $this->respondWithToken($token);

        // save last login
        $this->authRepository->update([
            'last_login_at' => Carbon::now(),
        ], $user->id);

        $res = $data->original;
        $res['is_active'] = $user->is_active == 1 ? true : false;
        $res['email'] = $user->email;

        return ResponseApi::success(__('auth.login_success'), $res);
    }

    /**
     * Get the authenticated User.
     *
     * @return ResponseFactory|Response
     */
    public function me(): ResponseFactory|Response
    {
        $user = auth()->user();

        // remove last login
        unset($user->last_login_at);
        unset($user->email_verified_at);
        $user->is_active = $user->is_active == 1 ? true : false;

        return ResponseApi::success(__('message.success'), $user);
    }

    /**
     * Update user profile
     *
     * @return ResponseFactory|Response
     */
    public function update(UpdateProfileRequest $request): ResponseFactory|Response
    {
        $name = $request->get('name');

        $user = Auth::user();

        $dataUpdate = [
            'name' => $name ?? 'Username',
        ];

        if ($request->has('explanation')) {
            $dataUpdate['explanation'] = $request->get('explanation');
        }

        $currentPathImage = null;

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                $currentPathImage = $user->avatar;
            }

            // Store new avatar
            $path = $request->file('avatar')->store('avatars', 's3');
            $dataUpdate['avatar'] = $path;
        }

        $user = $this->authRepository->update($dataUpdate, $user->id);

        // Delete old avatar if exists
        if ($currentPathImage) {
            Storage::disk('s3')->delete($currentPathImage);
        }

        return ResponseApi::success(__('message.update_success'));
    }

    /**
     * Change password
     *
     * @return ResponseFactory|Response
     */
    public function changePassword(ChangePasswordRequest $request): ResponseFactory|Response
    {
        $oldPassword = $request->get('old_password');
        $newPassword = $request->get('new_password');

        $user = auth()->user();

        // check old password
        if (!auth()->validate([
            'email' => $user->email,
            'password' => $oldPassword,
        ])) {
            return ResponseApi::validationError([
                'old_password' => [__('auth.old_password_not_match')],
            ], __('auth.update_failed'));
        }

        $this->authRepository->changePassword($newPassword, $user->id);

        return ResponseApi::success(__('message.update_success'));
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return ResponseFactory|Response
     */
    public function logout(): ResponseFactory|Response
    {
        auth()->logout();

        return ResponseApi::success(__('auth.logout_success'));
    }


    /**
     * Refresh a token.
     *
     * @return \Symfony\Component\HttpFoundation\Response|ResponseFactory
     */
    public function refresh(): ResponseFactory|\Symfony\Component\HttpFoundation\Response
    {
        $newToken = $this->respondWithToken(auth()->refresh());

        return ResponseApi::success(__('message.success'), $newToken->original);
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

    public function deleteAccount(): ResponseFactory|Response
    {
        $user = Auth::user();

        $this->authRepository->delete($user->id);

        return ResponseApi::success(__('auth.delete_account_success'));
    }
}
