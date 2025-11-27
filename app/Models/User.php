<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // role column for RBAC
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // -------------------------
    // Role helpers
    // -------------------------
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    // -------------------------
    // Cart relationship
    // -------------------------
    /**
     * Get all cart items for the user.
     */
    public function cartItems()
    {
        return $this->hasMany(\App\Models\Cart::class);
    }

    // -------------------------
    // Helper to count cart items
    // -------------------------
    /**
     * Get the total number of items in the user's cart
     */
    public function cartCount(): int
    {
        return $this->cartItems()->sum('quantity');
    }
}
