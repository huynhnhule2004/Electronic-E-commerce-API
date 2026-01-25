<?php

namespace Modules\Branch\Actions;

use Modules\Branch\DTOs\CreateBranchDto;

class CreateBranchAction
{
    /**
     * @return array<string, mixed>
     */
    public function __invoke(CreateBranchDto $dto): array
    {
        return [
            'name' => $dto->name,
            'address' => $dto->address,
        ];
    }
}
