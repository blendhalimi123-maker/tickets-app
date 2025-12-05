<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Ticket;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('ticket')
            ->where('user_id', auth()->id())
            ->get();

        $total = $cartItems->sum(function($item) {
            return $item->ticket->price * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

 
    public function add(Ticket $ticket)
    {
        $cartItem = Cart::firstOrCreate(
            ['user_id' => auth()->id(), 'ticket_id' => $ticket->id],
            ['quantity' => 1]
        );

        return redirect()->back()->with('success', 'Ticket added to cart!');
    }

 
    public function remove(Cart $cart)
    {
        if ($cart->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $cart->delete();

        return redirect()->back()->with('success', 'Ticket removed from cart.');
    }

    
    
    
    public function update(Request $request, Cart $cart)
    {
        if ($cart->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart->update(['quantity' => $request->quantity]);

        return redirect()->back()->with('success', 'Quantity updated.');
    }
}
