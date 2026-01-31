<?php

namespace Modules\Auth\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\DTOs\ChangePasswordDto;

class ChangePasswordAction
{
    public function execute(User $user, ChangePasswordDto $dto): void
    {
        // Verify current password
        if (!Hash::check($dto->current_password, $user->password)) {
            throw new \Exception('Current password is incorrect', 400);
        }

        // Update password
        $user->update([
            'password' => Hash::make($dto->password),
        ]);
    }
}
