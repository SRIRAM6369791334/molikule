<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
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
        'user_type',
        'phone',
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

    // User type constants
    const USER_TYPE_ADMIN = 'admin';
    const USER_TYPE_USER = 'user';

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * Check if user has admin user_type
     */
    public function isAdmin()
    {
        return $this->user_type === self::USER_TYPE_ADMIN;
    }

    /**
     * Check if user has manager user_type
     */
    public function isManager()
    {
        return $this->user_type === 'manager';
    }

    /**
     * Check if user has user user_type
     */
    public function isUser()
    {
        return $this->user_type === self::USER_TYPE_USER;
    }

    /**
     * Check if user is active
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Get user_type color for UI
     */
    public function getUserTypeColor()
    {
        return match($this->user_type) {
            self::USER_TYPE_ADMIN => 'danger',
            self::USER_TYPE_USER => 'success',
            default => 'secondary'
        };
    }

    /**
     * Get user_type badge text
     */
    public function getUserTypeBadge()
    {
        return ucfirst($this->user_type ?: 'User');
    }
}
