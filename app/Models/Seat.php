<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'fixture_id',
        'match_id',
        'seat_identifier',
        'seat_info',
        'row',
        'number',
        'stand',
        'category',
        'price',
        'is_booked'
    ];
}