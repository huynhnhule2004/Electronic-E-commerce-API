<?php

namespace Modules\Product\DTOs;

use Illuminate\Http\UploadedFile;

class CategoryDto
{
    public function __construct(
        public readonly string $name,
        public readonly ?int $parent_id,
        public readonly ?UploadedFile $icon,
        public readonly ?UploadedFile $image,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            parent_id: $data['parent_id'] ?? null,
            icon: $data['icon'] ?? null,
            image: $data['image'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'parent_id' => $this->parent_id,
            // Files are handled separately in Actions
        ], fn($value) => !is_null($value));
    }
}
