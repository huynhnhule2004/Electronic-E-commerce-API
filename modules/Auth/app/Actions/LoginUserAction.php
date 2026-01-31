<?php

namespace Modules\Auth\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\DTOs\LoginDto;

class LoginUserAction
{
    public function __construct(
        private readonly GenerateRefreshTokenAction $generateRefreshTokenAction
    ) {}

    public function execute(LoginDto $dto): array
    {
        $credentials = $dto->toArray();

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            throw new \Exception('Invalid credentials', 401);
        }

        $user = Auth::guard('api')->user();
        $refreshToken = $this->generateRefreshTokenAction->execute($user->id);

        return [
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'user' => $user,
        ];
    }
}
