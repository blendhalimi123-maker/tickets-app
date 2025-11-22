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
     'price',
     'is_available',
     'user_id', // ID of the user selling the ticket
    ] ;
    
     
    protected $casts = [
        'game_date'=> 'datetime',
        'price'=> 'float',
        'is_available'=> 'boolean'  
      ] ;

      public function seller()
      {
        return $this->belongsTo(User::class, 'user_id');
      }

}
