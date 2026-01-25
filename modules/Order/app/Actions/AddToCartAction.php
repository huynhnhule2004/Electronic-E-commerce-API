<?php

namespace Modules\Order\Actions;

use Illuminate\Support\Facades\Session;
use Modules\Order\DTOs\AddToCartDto;
use Modules\Product\Models\Product;

class AddToCartAction
{
    /**
     * @return array<int, array{product_id:int, product_name:string, quantity:int, price:float}>
     */
    public function __invoke(AddToCartDto $dto): array
    {
        $product = Product::query()->findOrFail($dto->productId);

        $cart = Session::get('cart', []);
        $cart[$dto->productId] = [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => ($cart[$dto->productId]['quantity'] ?? 0) + $dto->quantity,
            'price' => 0.0,
        ];

        Session::put('cart', $cart);

        return array_values($cart);
    }
}
