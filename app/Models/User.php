<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HandlesTrash;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HandlesTrash;

    public string $name {
        get => $this->attributes['name'] ?? '';
        set => $this->attributes['name'] = trim($value);
    }

    public string $email {
        get => $this->attributes['email'] ?? '';
        set => $this->attributes['email'] = strtolower(trim($value));
    }

    public string $username {
        get => $this->attributes['username'] ?? '';
        set => $this->attributes['username'] = trim($value);
    }

    public string $phone {
        get => $this->attributes['phone'] ?? '';
        set => $this->attributes['phone'] = trim($value);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'phone',
        'email',
        'password',
        'profile_status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
