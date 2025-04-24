<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Otp;

use App\Enum\Auth;

/**
 * Class ExamService.
 *
 * @package namespace App\Services;
 */
class ExamService extends BaseService
{
    public function __construct() {}

    public function getExams(): int
    {
        return 123;
    }
}
