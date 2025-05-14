<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait HasInputIdentify
{
    public function getInputIdentifyAttribute(): string
    {
        return self::INPUT_IDENTIFY_PREFIX .  Str::random(4) . $this->id . Str::random(4);
    }
    public static function formatId($id): string
    {
        if (!$id) {
            $id = 0;
        }

        return self::INPUT_IDENTIFY_PREFIX .  Str::random(4) . $id . Str::random(4);
    }

    public static function toOriginId(string $formattedId): bool|int
    {
        $prefixLength = strlen(self::INPUT_IDENTIFY_PREFIX);
        $randomLength = 4;

        $start = $prefixLength + $randomLength;
        $length = strlen($formattedId) - $start - $randomLength;

        if ($length <= 0) {
            return false;
        }

        $originId = substr($formattedId, $start, $length);

        return is_numeric($originId) ? (int) $originId : false;
    }
}