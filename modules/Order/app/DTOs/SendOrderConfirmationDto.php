<?php

namespace Modules\Order\DTOs;

readonly class SendOrderConfirmationDto
{
    public function __construct(
        public readonly string $email,
        public readonly string $customerName,
        public readonly string $orderNumber,
        public readonly float $totalAmount
    ) {
    }
}
