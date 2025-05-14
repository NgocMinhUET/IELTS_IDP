<?php

namespace App\Enum\Traits;

trait HasValue
{
    public static function fromValue(string $value): ?self
    {
        return collect(self::cases())->first(fn($case) => $case->value === $value);
    }

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
