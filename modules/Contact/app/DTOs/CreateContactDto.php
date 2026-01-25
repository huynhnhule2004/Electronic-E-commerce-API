<?php

namespace Modules\Contact\DTOs;

readonly class CreateContactDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $message
    ) {
    }
}
