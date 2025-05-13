<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Crypt;

trait HasEncryptedToken
{
    public function generateEncryptedToken(array $extra = []): string
    {
        $payload = array_merge([
            'id' => $this->id,
            'nonce' => bin2hex(random_bytes(5)),
        ], $extra);

        return Crypt::encryptString(json_encode($payload));
    }

    public static function decryptToken(string $token): mixed
    {
        try {
            $json = Crypt::decryptString($token);

            return json_decode($json, true);
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function decryptTokenId(string $token): ?int
    {
        $payload = self::decryptToken($token);

        return $payload['id'] ?? false;
    }
}
