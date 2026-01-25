<?php

namespace Modules\Product\DTOs;

readonly class CreateProductDto
{
    /**
     * @param array<string> $keywords
     */
    public function __construct(
        public readonly string $name,
        public readonly string $sku,
        public readonly float $price,
        public readonly array $keywords = []
    ) {
    }
}
