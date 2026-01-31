<?php

namespace Modules\Auth\Actions;

use Illuminate\Support\Facades\Auth;

class RefreshTokenAction
{
    public function execute(): array
    {
        $token = Auth::guard('api')->refresh();

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
        ];
    }
}
