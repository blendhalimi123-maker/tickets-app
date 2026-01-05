<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameCart;
use Carbon\Carbon;

class GameCartController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $cartItems = GameCart::where('user_id', auth()->id())
            ->where('status', 'in_cart')
            ->orderBy('match_date')
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function addSeat(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Please log in first.'], 401);
        }

        $validated = $request->validate([
            'api_game_id' => 'required|string',
            'home_team' => 'required|string',
            'away_team' => 'required|string',
            'match_date' => 'required|date',
            'stadium' => 'required|string',
            'stand' => 'required|string',
            'row' => 'required|string',
            'seat_number' => 'required|integer',
            'category' => 'required|string',
            'price' => 'required|numeric',
        ]);

        $gameSeatsCount = GameCart::where('user_id', auth()->id())
            ->where('api_game_id', $validated['api_game_id'])
            ->where('status', 'in_cart')
            ->count();

        if ($gameSeatsCount >= 5) {
            return response()->json([
                'error' => 'Maximum 5 seats per match.'
            ], 422);
        }

        $existing = GameCart::where('user_id', auth()->id())
            ->where('api_game_id', $validated['api_game_id'])
            ->where('stand', $validated['stand'])
            ->where('row', $validated['row'])
            ->where('seat_number', $validated['seat_number'])
            ->where('status', 'in_cart')
            ->exists();

        if ($existing) {
            return response()->json([
                'error' => 'This seat is already in your cart.'
            ], 422);
        }

        $reservedUntil = Carbon::now()->addMinutes(15);

        $cartItem = GameCart::create([
            'user_id' => auth()->id(),
            ...$validated,
            'quantity' => 1,
            'status' => 'in_cart',
            'reserved_until' => $reservedUntil,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Seat added to cart!',
            'cart_count' => GameCart::where('user_id', auth()->id())->where('status', 'in_cart')->count(),
        ]);
    }

    public function addMultipleSeats(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'api_game_id' => 'required|string',
            'home_team' => 'required|string',
            'away_team' => 'required|string',
            'match_date' => 'required|date',
            'stadium' => 'required|string',
            'selected_seats_json' => 'required|json',
        ]);

        $seatsData = json_decode($request->selected_seats_json, true);

        if (empty($seatsData)) {
            return back()->with('error', 'No seats selected.');
        }

        $gameSeatsCount = GameCart::where('user_id', auth()->id())
            ->where('api_game_id', $request->api_game_id)
            ->where('status', 'in_cart')
            ->count();

        if ($gameSeatsCount + count($seatsData) > 5) {
            return back()->with('error', 'Maximum 5 seats per match.');
        }

        foreach ($seatsData as $seat) {
            $existing = GameCart::where('user_id', auth()->id())
                ->where('api_game_id', $request->api_game_id)
                ->where('stand', $seat['stand'])
                ->where('row', $seat['row'])
                ->where('seat_number', $seat['number'])
                ->where('status', 'in_cart')
                ->exists();

            if (!$existing) {
                GameCart::create([
                    'user_id' => auth()->id(),
                    'api_game_id' => $request->api_game_id,
                    'home_team' => $request->home_team,
                    'away_team' => $request->away_team,
                    'match_date' => $request->match_date,
                    'stadium' => $request->stadium,
                    'stand' => $seat['stand'],
                    'row' => is_numeric($seat['row']) ? $seat['row'] : $seat['row'],
                    'seat_number' => $seat['number'],
                    'category' => $seat['category'],
                    'price' => $seat['price'],
                    'quantity' => 1,
                    'status' => 'in_cart',
                    'reserved_until' => Carbon::now()->addMinutes(15),
                ]);
            }
        }

        return redirect()->route('cart.index')->with('success', count($seatsData) . ' seat(s) added to cart!');
    }

    public function remove($id)
    {
        $cartItem = GameCart::findOrFail($id);
        
        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('success', 'Item removed from cart.');
    }

public function myTickets()
{
    $tickets = GameCart::where('user_id', auth()->id())
        ->where('status', 'paid')
        ->orderBy('match_date', 'desc')
        ->get()
        ->groupBy('api_game_id'); 

    return view('tickets.index', compact('tickets'));
}














}
