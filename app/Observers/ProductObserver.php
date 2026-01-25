<?php

namespace App\Observers;

use Modules\Product\Models\Product;
use Modules\Product\Services\ProductCacheService;

class ProductObserver
{
    public function saved(Product $product): void
    {
        app(ProductCacheService::class)->forgetList();
    }

    public function deleted(Product $product): void
    {
        app(ProductCacheService::class)->forgetList();
    }

    public function restored(Product $product): void
    {
        app(ProductCacheService::class)->forgetList();
    }

    public function forceDeleted(Product $product): void
    {
        app(ProductCacheService::class)->forgetList();
    }
}
