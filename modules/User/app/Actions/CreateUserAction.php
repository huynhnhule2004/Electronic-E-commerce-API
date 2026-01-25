<?php

namespace Modules\User\Actions;

use App\Models\User;
use Modules\User\DTOs\CreateUserDto;

class CreateUserAction
{
    public function __invoke(CreateUserDto $dto): User
    {
        return User::query()->create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password,
        ]);
    }
}
