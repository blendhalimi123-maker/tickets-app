<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'east_price',
        'west_price',
        'west_vip_price',
        'north_price',
        'south_price',
    ];
}


