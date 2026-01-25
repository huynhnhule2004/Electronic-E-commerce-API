<?php

namespace Modules\Order\Models;

use App\Traits\HandlesTrash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes, HandlesTrash;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'payment_method',
        'total_amount',
    ];

    public string $order_number {
        get => $this->attributes['order_number'] ?? '';
        set => $this->attributes['order_number'] = strtoupper(trim($value));
    }
}
