<?php

namespace Modules\Product\Services;

use Illuminate\Support\Facades\Cache;
use Modules\Product\Models\Product;

class ProductCacheService
{
    private const KEY_LIST = 'products:list';

    public function list(): mixed
    {
        return Cache::remember(self::KEY_LIST, now()->addMinutes(10), function () {
            return Product::query()
                ->select(['id', 'name', 'slug', 'description', 'brand_id', 'category_id'])
                ->latest('id')
                ->get();
        });
    }

    public function forgetList(): void
    {
        Cache::forget(self::KEY_LIST);
    }
}
