<?php

namespace Modules\Product\Models;

use App\Traits\HandlesTrash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use SoftDeletes, HandlesTrash;

    protected $fillable = [
        'product_id',
        'sku',
        'color',
        'version',
        'price',
        'stock',
    ];

    public string $sku {
        get => $this->attributes['sku'] ?? '';
        set => $this->attributes['sku'] = strtoupper(trim($value));
    }
}
