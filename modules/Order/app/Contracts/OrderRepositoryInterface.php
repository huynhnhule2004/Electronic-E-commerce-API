<?php

namespace Modules\Order\Contracts;

use Modules\Order\Models\Order;

interface OrderRepositoryInterface
{
    /**
     * @param array<int, array{product_id:int, product_name:string, quantity:int, price:float}> $items
     */
    public function create(
        int $userId,
        string $orderNumber,
        string $paymentMethod,
        float $totalAmount,
        array $items
    ): Order;
}
