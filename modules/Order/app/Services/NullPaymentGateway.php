<?php

namespace Modules\Order\Services;

use Modules\Order\Contracts\PaymentGatewayInterface;

class NullPaymentGateway implements PaymentGatewayInterface
{
    public function charge(string $reference, int $amountCents): bool
    {
        return true;
    }
}
