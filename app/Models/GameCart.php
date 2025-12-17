<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameCart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'api_game_id',
        'home_team',
        'away_team',
        'match_date',
        'stadium',
        'stand',
        'row',
        'seat_number',
        'category',
        'price',
        'quantity',
        'status',
        'api_metadata',
        'reserved_until'
    ];

    protected $casts = [
        'match_date' => 'datetime',
        'reserved_until' => 'datetime',
        'api_metadata' => 'array',
        'price' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}