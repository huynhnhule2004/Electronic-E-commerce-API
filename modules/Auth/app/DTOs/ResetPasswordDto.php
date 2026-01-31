<?php

namespace Modules\Auth\DTOs;

readonly class ResetPasswordDto
{
    public function __construct(
        public string $email,
        public string $token,
        public string $password,
    ) {
    }

    public static function fromRequest(array $data): self
    {
        return new self(
            email: $data['email'],
            token: $data['token'],
            password: $data['password'],
        );
    }
}
