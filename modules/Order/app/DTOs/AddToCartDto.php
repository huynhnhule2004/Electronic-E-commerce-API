<?php

namespace Modules\Order\DTOs;

readonly class AddToCartDto
{
    public function __construct(
        public readonly int $productId,
        public readonly int $quantity
    ) {
    }
}
