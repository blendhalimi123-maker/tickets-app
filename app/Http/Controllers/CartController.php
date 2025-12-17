<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Ticket;
use App\Models\Seat;
use App\Models\TicketCategory;

class CartController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('message', 'Please log in to view your cart.');
        }

        $cartItems = Cart::with(['ticket', 'seat'])
            ->where('user_id', auth()->id())
            ->whereHas('ticket') 
            ->get();

        $total = $cartItems->sum(function ($item) {
            return ($item->ticket->price ?? 0) * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Ticket $ticket, Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('message', 'Please log in to add tickets.');
        }

        $seat_id = $request->seat_id;

        if ($seat_id) {
            $seat = Seat::findOrFail($seat_id);

            if ($seat->status === 'booked') {
                return back()->with('error', 'This seat is already booked.');
            }

            $count = Cart::where('user_id', auth()->id())
                ->whereHas('ticket', function ($q) use ($ticket) {
                    $q->where('match_id', $ticket->match_id);
                })
                ->count();

            if ($count >= 5) {
                return back()->with('error', 'Maximum 5 seats per match.');
            }

            Cart::create([
                'user_id' => auth()->id(),
                'ticket_id' => $ticket->id,
                'seat_id' => $seat->id,
                'quantity' => 1,
            ]);

            return back()->with('success', 'Seat added to cart!');
        }

        Cart::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'ticket_id' => $ticket->id,
            ],
            [
                'quantity' => 1,
            ]
        );

        return back()->with('success', 'Ticket added to cart!');
    }

    public function addStadiumSeats(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('message', 'Please log in.');
        }

        $request->validate([
            'match_id' => 'required|integer',
            'selected_seats' => 'required|json',
            'match_info' => 'required|json',
        ]);

        $seats = json_decode($request->selected_seats, true);
        $matchInfo = json_decode($request->match_info, true);

        if (empty($seats)) {
            return back()->with('error', 'No seats selected.');
        }

        $currentCount = Cart::where('user_id', auth()->id())
            ->whereHas('ticket', function ($q) use ($request) {
                $q->where('match_id', $request->match_id);
            })
            ->count();

        if ($currentCount + count($seats) > 5) {
            return back()->with('error', 'Maximum 5 seats per match.');
        }

        foreach ($seats as $seatData) {

            $category = TicketCategory::where('name', $seatData['category'])->first();
            if (!$category) {
                return back()->with('error', 'Category not found.');
            }

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
                    'is_available' => true,
                ]
            );

            $seat = Seat::firstOrCreate(
                [
                    'match_id' => $request->match_id,
                    'seat_identifier' => $seatData['id'],
                ],
                [
                    'row' => $seatData['row'],
                    'number' => $seatData['number'],
                    'section' => $seatData['stand'],
                    'stand' => $seatData['stand'],
                    'category' => $seatData['category'],
                    'price' => $seatData['price'],
                    'status' => 'available',
                    'ticket_category_id' => $category->id,
                ]
            );

            Cart::create([
                'user_id' => auth()->id(),
                'ticket_id' => $ticket->id,
                'seat_id' => $seat->id,
                'quantity' => 1,
            ]);
        }

        return redirect()->route('cart.index')->with('success', count($seats) . ' seat(s) added to cart!');
    }

    public function remove(Cart $cart)
    {
        abort_if($cart->user_id !== auth()->id(), 403);
        $cart->delete();

        return back()->with('success', 'Item removed.');
    }

    public function update(Request $request, Cart $cart)
    {
        abort_if($cart->user_id !== auth()->id(), 403);

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Quantity updated.');
    }
}
