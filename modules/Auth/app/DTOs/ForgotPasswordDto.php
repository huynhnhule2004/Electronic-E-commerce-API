<?php

namespace Modules\Auth\DTOs;

readonly class ForgotPasswordDto
{
    public function __construct(
        public string $email,
    ) {
    }

    public static function fromRequest(array $data): self
    {
        return new self(
            email: $data['email'],
        );
    }
}
