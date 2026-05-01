<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_home',
        'phone_italy',
        'emergency_contact_name',
        'emergency_contact_email',
        'emergency_contact_phone',
        'postal_country',
        'postal_street',
        'postal_city',
        'postal_state',
        'postcode',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
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
            'preferences' => 'array',
            'last_login' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
            'super' => 'boolean',
        ];
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Return the user's primary key as a string. Mirrors the
     * `Statamic\Contracts\Auth\User::id()` contract so frontend code that
     * was written against Statamic users (mikomagni/simple-likes,
     * SyncGuestLikes listener, etc.) keeps working now that auth uses an
     * Eloquent provider directly.
     */
    public function id(): string
    {
        return (string) $this->getKey();
    }
}
