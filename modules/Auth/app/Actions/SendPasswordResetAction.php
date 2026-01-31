<?php

namespace Modules\Auth\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Modules\Auth\DTOs\ForgotPasswordDto;

class SendPasswordResetAction
{
    public function execute(ForgotPasswordDto $dto): string
    {
        $user = User::where('email', $dto->email)->first();

        if (!$user) {
            throw new \Exception('User not found', 404);
        }

        $status = Password::sendResetLink(['email' => $dto->email]);

        if ($status !== Password::RESET_LINK_SENT) {
            throw new \Exception('Failed to send reset link', 500);
        }

        return __($status);
    }
}
