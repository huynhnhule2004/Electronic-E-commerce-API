<?php

namespace Modules\Auth\DTOs;

readonly class ChangePasswordDto
{
    public function __construct(
        public string $current_password,
        public string $password,
    ) {
    }

    public static function fromRequest(array $data): self
    {
        return new self(
            current_password: $data['current_password'],
            password: $data['password'],
        );
    }
}
