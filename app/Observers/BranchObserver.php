<?php

namespace App\Observers;

use Modules\Branch\Models\Branch;
use Modules\Branch\Services\BranchCacheService;

class BranchObserver
{
    public function saved(Branch $branch): void
    {
        app(BranchCacheService::class)->forgetList();
    }

    public function deleted(Branch $branch): void
    {
        app(BranchCacheService::class)->forgetList();
    }

    public function restored(Branch $branch): void
    {
        app(BranchCacheService::class)->forgetList();
    }

    public function forceDeleted(Branch $branch): void
    {
        app(BranchCacheService::class)->forgetList();
    }
}
