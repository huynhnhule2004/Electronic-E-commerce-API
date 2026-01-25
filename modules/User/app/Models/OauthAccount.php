<?php

namespace Modules\User\Models;

use App\Traits\HandlesTrash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OauthAccount extends Model
{
    use SoftDeletes, HandlesTrash;

    protected $table = 'user_oauth_accounts';

    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public string $provider {
        get => $this->attributes['provider'] ?? '';
        set => $this->attributes['provider'] = strtolower(trim($value));
    }

    public string $provider_id {
        get => $this->attributes['provider_id'] ?? '';
        set => $this->attributes['provider_id'] = trim($value);
    }
}
