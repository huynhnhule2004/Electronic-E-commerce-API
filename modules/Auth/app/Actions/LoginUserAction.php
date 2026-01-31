<?php

namespace Modules\Auth\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\DTOs\LoginDto;

class LoginUserAction
{
    public function execute(LoginDto $dto): array
    {
        $credentials = $dto->toArray();

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            throw new \Exception('Invalid credentials', 401);
        }

        $user = Auth::guard('api')->user();

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'user' => $user,
        ];
    }
}
