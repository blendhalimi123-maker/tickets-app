<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Game;
use App\Models\GameCart;
use App\Models\Team;

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

    public function favoriteTeams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'user_favorite_teams')->withTimestamps();
    }

    public function hasFavoritedTeam($apiTeamId): bool
    {
        return $this->favoriteTeams()->where('api_team_id', $apiTeamId)->exists();
    }

    public function favoriteTeam($apiTeamId): void
    {
        $team = Team::firstOrCreate(
            ['api_team_id' => $apiTeamId],
            ['name' => 'Team ' . $apiTeamId]
        );

        $this->favoriteTeams()->syncWithoutDetaching([$team->id]);
    }

    public function unfavoriteTeam($apiTeamId): void
    {
        $team = Team::where('api_team_id', $apiTeamId)->first();
        if ($team) {
            $this->favoriteTeams()->detach($team->id);
        }
    }
}
