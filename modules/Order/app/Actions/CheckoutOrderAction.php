<?php

namespace Modules\Order\Actions;

use Illuminate\Support\Facades\Session;
use Modules\Order\DTOs\CheckoutOrderDto;
use Modules\Order\DTOs\CreateOrderDto;
use Modules\Order\Models\Order;

class CheckoutOrderAction
{
    public function __construct(
        private readonly CreateOrderAction $createOrder
    ) {
    }

    public function __invoke(CheckoutOrderDto $dto): Order
    {
        $items = array_values(Session::get('cart', []));

        $totalAmount = array_reduce($items, function (float $carry, array $item): float {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0.0);

        $orderDto = new CreateOrderDto(
            $dto->userId,
            'ORD-' . strtoupper(bin2hex(random_bytes(4))),
            $dto->paymentMethod,
            $totalAmount,
            $items
        );

        $order = ($this->createOrder)($orderDto);

        Session::forget('cart');

        return $order;
    }
}
