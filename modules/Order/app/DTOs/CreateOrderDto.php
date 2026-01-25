<?php

namespace Modules\Order\DTOs;

readonly class CreateOrderDto
{
    /**
     * @param array<int, array{product_id:int, product_name:string, quantity:int, price:float}> $items
     */
    public function __construct(
        public readonly int $userId,
        public readonly string $orderNumber,
        public readonly string $paymentMethod,
        public readonly float $totalAmount,
        public readonly array $items
    ) {
    }
}
