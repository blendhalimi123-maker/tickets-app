<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_team_id',
        'name',
        'crest',
    ];

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_favorite_teams')->withTimestamps();
    }
}

