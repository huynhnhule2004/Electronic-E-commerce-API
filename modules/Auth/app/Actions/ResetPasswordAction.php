<?php

namespace Modules\Auth\Actions;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Modules\Auth\DTOs\ResetPasswordDto;

class ResetPasswordAction
{
    public function execute(ResetPasswordDto $dto): string
    {
        $status = Password::reset(
            [
                'email' => $dto->email,
                'password' => $dto->password,
                'token' => $dto->token,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw new \Exception(__($status), 400);
        }

        return __($status);
    }
}
