<?php

namespace Modules\Order\Models;

use App\Traits\HandlesTrash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use SoftDeletes, HandlesTrash;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'quantity',
        'price',
    ];

    public string $product_name {
        get => $this->attributes['product_name'] ?? '';
        set => $this->attributes['product_name'] = trim($value);
    }
}
