<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Otp;

use App\Enum\Auth;

/**
 * Class OtpService.
 *
 * @package namespace App\Services;
 */
class OtpService extends BaseService
{
    /**
     * construct
     *
     */
    public function __construct() {}

    public function generateOtp(): int
    {
        $otp = rand(100000, 999999);

        return $otp;
    }

    public function createOrUpdateOtp($email): int
    {
        $otp = $this->generateOtp();

        // Set expiration time
        $timeExpired = Carbon::now()->addMinutes(Auth::REGISTER_OTP_EXPIRE->value);

        // Save OTP
        Otp::updateOrCreate(
            ['email' => $email],
            [
                'otp' => $otp,
                'expires_at' => $timeExpired,
            ]
        );

        return $otp;
    }
}
