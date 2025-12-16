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

    public function addStadiumSeats(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('message', 'Please log in to add seats to your cart.');
        }

        $validated = $request->validate([
            'match_id' => 'required|integer',
            'selected_seats' => 'required|json',
            'match_info' => 'required|json'
        ]);
        
        $seats = json_decode($request->selected_seats, true);
        $matchInfo = json_decode($request->match_info, true);
        
        if (empty($seats)) {
            return redirect()->back()->with('error', 'No seats selected');
        }
        
        $currentSeatCount = Cart::where('user_id', auth()->id())
            ->whereHas('ticket', function($query) use ($request) {
                $query->where('match_id', $request->match_id);
            })
            ->count();
        
        if (($currentSeatCount + count($seats)) > 5) {
            return redirect()->back()->with('error', 'Maximum 5 seats per match. You have ' . $currentSeatCount . ' seats.');
        }
        
        foreach ($seats as $seatData) {
            $ticket = Ticket::firstOrCreate(
                [
                    'match_id' => $request->match_id,
                    'seat_id' => $seatData['id'],
                ],
                [
                    'title' => $matchInfo['team1'] . ' vs ' . $matchInfo['team2'],
                    'game_date' => $matchInfo['match_date'],
                    'stadium' => $matchInfo['stadium'],
                    'seat_info' => $seatData['stand'] . ' Stand, Row ' . $seatData['row'] . ', Seat ' . $seatData['number'],
                    'stand' => $seatData['stand'],
                    'row' => $seatData['row'],
                    'seat_number' => $seatData['number'],
                    'category' => $seatData['category'],
                    'price' => $seatData['price'],
                    'status' => 'available',
                ]
            );
            
            $seatRecord = Seat::firstOrCreate(
                [
                    'match_id' => $request->match_id,
                    'seat_identifier' => $seatData['id'],
                ],
                [
                    'seat_info' => $seatData['stand'] . ' Stand, Row ' . $seatData['row'] . ', Seat ' . $seatData['number'],
                    'is_booked' => false,
                    'price' => $seatData['price'],
                ]
            );
            
            Cart::create([
                'user_id' => auth()->id(),
                'ticket_id' => $ticket->id,
                'seat_id' => $seatRecord->id,
                'quantity' => 1,
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', count($seats) . ' seat(s) added to cart!');
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