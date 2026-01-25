<?php

namespace Modules\Order\Actions;

use Modules\Order\Contracts\OrderRepositoryInterface;
use Modules\Order\Contracts\PaymentGatewayInterface;
use Modules\Order\DTOs\CreateOrderDto;
use Modules\Order\Models\Order;

class CreateOrderAction
{
    public function __construct(
        private readonly OrderRepositoryInterface $orders,
        private readonly PaymentGatewayInterface $payments
    ) {
    }

    public function __invoke(CreateOrderDto $dto): Order
    {
        if ($dto->paymentMethod !== 'cod') {
            $this->payments->charge($dto->orderNumber, (int) round($dto->totalAmount * 100));
        }

        return $this->orders->create(
            $dto->userId,
            $dto->orderNumber,
            $dto->paymentMethod,
            $dto->totalAmount,
            $dto->items
        );
    }
}
