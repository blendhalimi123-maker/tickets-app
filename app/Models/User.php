<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Game;

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

    public function gameCartItems()
    {
        return $this->hasMany(GameCart::class);
    }

    public function cartCount(): int
    {
        return $this->gameCartItems()
            ->where('status', 'in_cart')
            ->count();
    }

   
    public function favorites()
    {
        return $this->belongsToMany(Game::class, 'user_favorites')->withTimestamps();
    }

    public function hasFavorited($gameId): bool
    {
        return $this->favorites()->where('games.api_game_id', $gameId)->exists();
    }

    public function favorite($gameId): void
    {
        $game = Game::firstOrCreate(['api_game_id' => $gameId], ['title' => 'Match ' . $gameId]);
        $this->favorites()->syncWithoutDetaching([$game->id]);
    }

    public function unfavorite($gameId): void
    {
        $game = Game::where('api_game_id', $gameId)->first();
        if ($game) {
            $this->favorites()->detach($game->id);
        }
    }
}