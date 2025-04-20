<?php

namespace App\Enum;

/**
 * enum Auth
 */
enum User: int
{
    case TYPE_USER_EMPLOYEE = 1;
    case TYPE_USER_LECTURER = 2;

}
