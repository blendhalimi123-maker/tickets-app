<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Game;
use App\Models\GameCart;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

  
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }


    public function gameCartItems(): HasMany
    {
        return $this->hasMany(GameCart::class);
    }

    public function cartCount(): int
    {
        return $this->gameCartItems()
            ->where('status', 'in_cart')
            ->count();
    }

   
    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'user_favorites')
                    ->withTimestamps();
    }

    
    public function hasFavorited($apiGameId): bool
    {
        return $this->favorites()->where('api_game_id', $apiGameId)->exists();
    }

    public function favorite($apiGameId): void
    {
        $game = Game::firstOrCreate(
            ['api_game_id' => $apiGameId], 
            ['title' => 'Match ' . $apiGameId] 
        );
        
        $this->favorites()->syncWithoutDetaching([$game->id]);
    }

    public function unfavorite($apiGameId): void
    {
        $game = Game::where('api_game_id', $apiGameId)->first();
        
        if ($game) {
            $this->favorites()->detach($game->id);
        }
    }
}