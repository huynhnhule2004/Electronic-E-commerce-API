<?php

namespace Modules\Auth\Actions;

use Illuminate\Support\Facades\Auth;
use Modules\Auth\Models\RefreshToken;

class RefreshTokenAction
{
    public function __construct(
        private readonly GenerateRefreshTokenAction $generateRefreshTokenAction
    ) {}

    public function execute(string $token): array
    {
        $refreshToken = RefreshToken::where('token', $token)
            ->where('revoked', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$refreshToken) {
            throw new \Exception('Invalid refresh token', 401);
        }

        // Revoke old token (Rotation)
        $refreshToken->update(['revoked' => true]);

        $user = $refreshToken->user;
        
        // Generate new Access Token
        $accessToken = Auth::guard('api')->login($user);
        
        // Generate new Refresh Token
        $newRefreshToken = $this->generateRefreshTokenAction->execute($user->id);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $newRefreshToken,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
        ];
    }
}
