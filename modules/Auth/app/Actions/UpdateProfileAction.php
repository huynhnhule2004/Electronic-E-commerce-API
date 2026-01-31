<?php

namespace Modules\Auth\Actions;

use App\Models\User;
use Modules\Auth\DTOs\UpdateProfileDto;

class UpdateProfileAction
{
    public function execute(User $user, UpdateProfileDto $dto): User
    {
        $data = $dto->toArray();

        // Update profile fields
        $user->update($data);

        // Check if profile is completed (has username and phone)
        if ($user->username && $user->phone && $user->profile_status === 'pending') {
            $user->update(['profile_status' => 'completed']);
        }

        return $user->fresh();
    }
}
