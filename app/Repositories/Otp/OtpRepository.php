<?php

namespace App\Repositories\Otp;

use App\Models\Otp;
use App\Repositories\BaseRepository;

/**
 * Class OtpRepository.
 *
 * @package namespace App\Repositories\Otp;
 */
class OtpRepository extends BaseRepository implements OtpInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Otp::class;
    }
}
