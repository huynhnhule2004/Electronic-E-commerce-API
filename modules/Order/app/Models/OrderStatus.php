<?php

namespace Modules\Order\Models;

use App\Traits\HandlesTrash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderStatus extends Model
{
    use SoftDeletes, HandlesTrash;

    protected $fillable = [
        'order_id',
        'status',
        'note',
    ];

    public string $status {
        get => $this->attributes['status'] ?? '';
        set => $this->attributes['status'] = strtolower(trim($value));
    }
}
