<?php

namespace Modules\Order\DTOs;

readonly class CheckoutOrderDto
{
    public function __construct(
        public readonly int $userId,
        public readonly string $paymentMethod
    ) {
    }
}
