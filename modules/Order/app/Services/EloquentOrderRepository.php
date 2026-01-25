<?php

namespace Modules\Order\Services;

use Illuminate\Support\Facades\DB;
use Modules\Order\Contracts\OrderRepositoryInterface;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderItem;
use Modules\Order\Models\OrderStatus;

class EloquentOrderRepository implements OrderRepositoryInterface
{
    public function create(
        int $userId,
        string $orderNumber,
        string $paymentMethod,
        float $totalAmount,
        array $items
    ): Order {
        return DB::transaction(function () use ($userId, $orderNumber, $paymentMethod, $totalAmount, $items): Order {
            $order = Order::query()->create([
                'user_id' => $userId,
                'order_number' => $orderNumber,
                'status' => 'pending',
                'payment_method' => $paymentMethod,
                'total_amount' => $totalAmount,
            ]);

            foreach ($items as $item) {
                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            OrderStatus::query()->create([
                'order_id' => $order->id,
                'status' => 'pending',
                'note' => 'Order created',
            ]);

            return $order;
        });
    }
}
