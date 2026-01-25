<?php

namespace Modules\Order\Contracts;

interface PaymentGatewayInterface
{
    public function charge(string $reference, int $amountCents): bool;
}
