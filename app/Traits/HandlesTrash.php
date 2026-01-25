<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HandlesTrash
{
    /**
     * @return Builder<static>
     */
    public static function onlyTrash(): Builder
    {
        return static::onlyTrashed();
    }

    public function restoreFromTrash(): bool
    {
        return $this->restore();
    }

    public function forceDeleteFromTrash(): bool
    {
        return $this->forceDelete();
    }
}
