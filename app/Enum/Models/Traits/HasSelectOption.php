<?php

namespace App\Enum\Models\Traits;

trait HasSelectOption
{
    public static function options(): array
    {
        return array_map(
            fn($case) => ['value' => $case->value, 'label' => $case->label()],
            self::cases()
        );
    }

    public static function assoc(): array
    {
        return array_column(
            self::options(),
            'label',
            'value'
        );
    }

    public static function optionFromValue($value): array
    {
        return [
            'value' => $value->value,
            'label' => $value->label()
        ];
    }
}
