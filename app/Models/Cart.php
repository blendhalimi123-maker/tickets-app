<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ticket_id',
        'quantity',
    ];

    /**
     * The user who owns this cart item
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The ticket that this cart item refers to
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
