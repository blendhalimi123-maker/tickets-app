<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'title',
        'game_date',
        'stadium',
        'seat_info',
        'seat_id',
        'stand',
        'row',
        'seat_number',
        'category',
        'price',
        'is_available',
        'user_id',
        'fixture_id',
        'match_id'
    ];
    
    protected $casts = [
        'game_date' => 'datetime',
        'price' => 'float',
        'is_available' => 'boolean'
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}