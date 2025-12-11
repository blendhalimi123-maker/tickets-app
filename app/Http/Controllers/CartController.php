<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Ticket;
use App\Models\Seat;

class CartController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('message', 'Please log in to view your cart.');
        }

        $cartItems = Cart::with('ticket', 'seat')
            ->where('user_id', auth()->id())
            ->get();

        $total = $cartItems->sum(function($item) {
            return ($item->ticket->price ?? 0) * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Ticket $ticket)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('message', 'Please log in to add tickets to your cart.');
        }

        $seat_id = request()->seat_id ?? null;

        if ($seat_id) {
            $seat = Seat::findOrFail($seat_id);

            if ($seat->is_booked) {
                return redirect()->back()->with('error', 'This seat is already booked.');
            }

            $current_count = Cart::where('user_id', auth()->id())
                                 ->where('ticket_id', $ticket->id)
                                 ->count();

            if ($current_count >= 5) {
                return redirect()->back()->with('error', 'You can select maximum 5 seats per match.');
            }

            Cart::create([
                'user_id' => auth()->id(),
                'ticket_id' => $ticket->id,
                'seat_id' => $seat->id,
                'quantity' => 1,
            ]);

            return redirect()->back()->with('success', 'Seat added to cart!');
        }

        $cartItem = Cart::firstOrCreate(
            ['user_id' => auth()->id(), 'ticket_id' => $ticket->id],
            ['quantity' => 1]
        );

        return redirect()->back()->with('success', 'Ticket added to cart!');
    }

    public function remove(Cart $cart)
    {
        if (!auth()->check() || $cart->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $cart->delete();

        return redirect()->back()->with('success', 'Ticket removed from cart.');
    }

    public function update(Request $request, Cart $cart)
    {
        if (!auth()->check() || $cart->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart->update(['quantity' => $request->quantity]);

        return redirect()->back()->with('success', 'Quantity updated.');
    }
}
