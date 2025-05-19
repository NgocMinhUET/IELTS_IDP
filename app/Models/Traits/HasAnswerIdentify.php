<?php

namespace App\Models\Traits;

use Vinkla\Hashids\Facades\Hashids;

trait HasAnswerIdentify
{
    public function getAnswerIdentifyAttribute(): string
    {
        return self::ANSWER_IDENTIFY_PREFIX .  Hashids::encode($this->id);
    }
    public static function formatId($id): string
    {
        if (!$id) {
            $id = 0;
        }

        return self::ANSWER_IDENTIFY_PREFIX .  Hashids::encode($id);
    }

    public static function toOriginId(string $formattedId): bool|int
    {
        $prefixLength = strlen(self::ANSWER_IDENTIFY_PREFIX);

        $hashPart = substr($formattedId, $prefixLength);

        $decoded = Hashids::decode($hashPart);

        return count($decoded) === 1 ? (int) $decoded[0] : false;
    }
}