<?php

namespace App\Enum\Models;

use App\Enum\Models\Traits\HasSelectOption;

enum ApproveStatus: int
{
    use HasSelectOption;

    case PENDING = 1;
    case APPROVED = 2;
    case REJECTED = 3;

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
        };
    }

    public function textColor(): string
    {
        return match($this) {
            self::PENDING => 'text-warning',
            self::APPROVED => 'text-success',
            self::REJECTED => 'text-danger',
        };
    }
}
