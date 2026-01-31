<?php

namespace Modules\Auth\DTOs;

readonly class UpdateProfileDto
{
    public function __construct(
        public ?string $username = null,
        public ?string $phone = null,
        public ?string $name = null,
    ) {
    }

    public static function fromRequest(array $data): self
    {
        return new self(
            username: $data['username'] ?? null,
            phone: $data['phone'] ?? null,
            name: $data['name'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'username' => $this->username,
            'phone' => $this->phone,
            'name' => $this->name,
        ], fn($value) => $value !== null);
    }
}
