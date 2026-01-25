<?php

namespace Modules\Product\Models;

use App\Traits\HandlesTrash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes, HandlesTrash;

    protected $fillable = [
        'name',
        'slug',
        'brand_id',
        'category_id',
        'description',
        'keywords',
    ];

    protected $casts = [
        'keywords' => 'array',
    ];

    public string $name {
        get => $this->attributes['name'] ?? '';
        set => $this->attributes['name'] = trim($value);
    }

    public string $slug {
        get => $this->attributes['slug'] ?? '';
        set => $this->attributes['slug'] = trim($value);
    }
}
