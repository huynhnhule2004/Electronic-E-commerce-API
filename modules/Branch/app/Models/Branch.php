<?php

namespace Modules\Branch\Models;

use App\Traits\HandlesTrash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes, HandlesTrash;

    protected $fillable = [
        'name',
        'address',
        'lat',
        'lng',
    ];

    public string $name {
        get => $this->attributes['name'] ?? '';
        set => $this->attributes['name'] = trim($value);
    }
}
