<?php

namespace Modules\Auth\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\DTOs\RegisterDto;

class RegisterUserAction
{
    public function execute(RegisterDto $dto): User
    {
        $user = User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
            'profile_status' => 'pending',
        ]);

        return $user;
    }
}
