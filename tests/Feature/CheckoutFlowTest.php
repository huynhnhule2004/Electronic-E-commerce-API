<?php

declare(strict_types=1);

use App\Mail\OrderConfirmationMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Modules\Product\Models\Product;
use App\Models\User;
use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

it('allows a user to add to cart and checkout', function () {
    Mail::fake();

    $user = User::factory()->create([
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
    ]);

    $product = Product::query()->create([
        'name' => 'Smartphone X',
        'slug' => 'smartphone-x',
    ]);

    actingAs($user)
        ->postJson('/api/cart/items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ])
        ->assertOk();

    actingAs($user)
        ->postJson('/api/orders/checkout', [
            'payment_method' => 'cod',
        ])
        ->assertOk();

    Mail::assertQueued(OrderConfirmationMail::class);
});
