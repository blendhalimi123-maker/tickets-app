<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_category_id',
        'match_id',
        'seat_identifier',
        'row',
        'number',
        'section',
        'stand',
        'category',
        'price',
        'status'
    ];
}