<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_game_id',
        'title',
        'match_date',
        'total_tickets',
        'tickets_sold',
    ];

    protected $casts = [
        'match_date' => 'datetime',
    ];

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'user_favorites')->withTimestamps();
    }

    public function ticketsLeft(): int
    {
        return max(0, $this->total_tickets - $this->tickets_sold);
    }
}
