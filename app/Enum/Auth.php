<?php

namespace App\Enum;

/**
 * enum Auth
 */
enum Auth: int
{
    case LIMIT = 15;
    case REGISTER_OTP_EXPIRE = 2; // minutes
    case RESET_PASSWORD_EXPIRE = 24; // hours
    case REVOKE_TRUE = 1;
}
