<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSeat extends Model
{
    use HasFactory;

    protected $table = 'event_seats';

    protected $fillable = [
        'event_id',
        'seat_id',
        'price',
        'status',
    ];
}


