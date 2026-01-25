<?php

namespace Modules\Branch\Services;

use Illuminate\Support\Facades\Cache;
use Modules\Branch\Models\Branch;

class BranchCacheService
{
    private const KEY_LIST = 'branches:list';

    public function list(): mixed
    {
        return Cache::remember(self::KEY_LIST, now()->addMinutes(10), function () {
            return Branch::query()
                ->select(['id', 'name', 'address', 'lat', 'lng'])
                ->latest('id')
                ->get();
        });
    }

    public function forgetList(): void
    {
        Cache::forget(self::KEY_LIST);
    }
}
