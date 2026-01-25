<?php

declare(strict_types=1);

use Mockery as m;
use Modules\Order\Actions\CreateOrderAction;
use Modules\Order\Contracts\OrderRepositoryInterface;
use Modules\Order\Contracts\PaymentGatewayInterface;
use Modules\Order\DTOs\CreateOrderDto;
use Modules\Order\Models\Order;

it('creates order via repository and charges payment', function () {
    $repo = m::mock(OrderRepositoryInterface::class);
    $payments = m::mock(PaymentGatewayInterface::class);

    $dto = new CreateOrderDto(
        userId: 1,
        orderNumber: 'ORD-TEST',
        paymentMethod: 'bank_transfer',
        totalAmount: 150.5,
        items: [
            ['product_id' => 1, 'product_name' => 'Item', 'quantity' => 1, 'price' => 150.5],
        ]
    );

    $payments->shouldReceive('charge')->once()->with('ORD-TEST', 15050)->andReturnTrue();

    $repo->shouldReceive('create')->once()->andReturn(new Order());

    $action = new CreateOrderAction($repo, $payments);

    $result = $action($dto);

    expect($result)->toBeInstanceOf(Order::class);
});
