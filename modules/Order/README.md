# Order Module

## Purpose
Cart + checkout flow, order creation, and payment integration.

## Structure
- app/Http/Controllers: `CartController`, `OrderController`
- app/DTOs: `AddToCartDto`, `CheckoutOrderDto`, `CreateOrderDto`
- app/Actions: `AddToCartAction`, `CheckoutOrderAction`, `CreateOrderAction`, `SendOrderConfirmationAction`
- app/Contracts: `OrderRepositoryInterface`, `PaymentGatewayInterface`
- app/Services: `EloquentOrderRepository`
- app/Models: `Order`, `OrderItem`, `OrderStatus`
- database/migrations: orders, items, statuses
- routes/api.php
- app/Providers: `OrderServiceProvider`

## API
- GET /api/cart
- POST /api/cart/items
- POST /api/orders/checkout

## Notes
- Order status timeline stored in `order_statuses`.
- Email confirmation queued on checkout.
