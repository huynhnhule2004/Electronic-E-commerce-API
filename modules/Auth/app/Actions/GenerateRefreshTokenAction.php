<?php

namespace Modules\Auth\Actions;

use Illuminate\Support\Str;
use Modules\Auth\Models\RefreshToken;

class GenerateRefreshTokenAction
{
    public function execute(int $userId): string
    {
        $token = Str::random(64);
        
        RefreshToken::create([
            'user_id' => $userId,
            'token' => $token,
            'expires_at' => now()->addMinutes(config('jwt.refresh_ttl', 43200)),
            'revoked' => false,
        ]);

        return $token;
    }
}
