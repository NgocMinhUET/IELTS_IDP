<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait HasAnswerIdentify
{
    public function getAnswerIdentifyAttribute(): string
    {
        return self::ANSWER_IDENTIFY_PREFIX .  Str::random(3) . $this->id . Str::random(3);
    }
    public static function formatId($id): string
    {
        if (!$id) {
            $id = 0;
        }

        return self::ANSWER_IDENTIFY_PREFIX .  Str::random(3) . $id . Str::random(3);
    }

    public static function toOriginId(string $formattedId): bool|int
    {
        $prefixLength = strlen(self::ANSWER_IDENTIFY_PREFIX);
        $randomLength = 3;

        $start = $prefixLength + $randomLength;
        $length = strlen($formattedId) - $start - $randomLength;

        if ($length <= 0) {
            return false;
        }

        $originId = substr($formattedId, $start, $length);

        return is_numeric($originId) ? (int) $originId : false;
    }
}