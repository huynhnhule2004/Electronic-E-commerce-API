<?php

namespace Modules\Branch\DTOs;

readonly class CreateBranchDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $address
    ) {
    }
}
