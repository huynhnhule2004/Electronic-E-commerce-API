<?php

namespace Modules\Contact\Actions;

use Modules\Contact\DTOs\CreateContactDto;

class CreateContactAction
{
    /**
     * @return array<string, mixed>
     */
    public function __invoke(CreateContactDto $dto): array
    {
        return [
            'name' => $dto->name,
            'email' => $dto->email,
            'message' => $dto->message,
        ];
    }
}
