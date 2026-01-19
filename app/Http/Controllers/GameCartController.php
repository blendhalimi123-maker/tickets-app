<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameCart;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GameCartController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $cartItems = GameCart::where('user_id', auth()->id())
            ->where('status', 'in_cart')
            ->orderBy('match_date', 'desc')
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Add a single seat (AJAX)
     */
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
            return response()->json(['error' => 'Maximum 5 seats per match.'], 422);
        }

        try {
            GameCart::updateOrCreate(
                [
                    'user_id'     => auth()->id(),
                    'api_game_id' => $validated['api_game_id'],
                    'stand'       => $validated['stand'],
                    'row'         => $validated['row'],
                    'seat_number' => $validated['seat_number'],
                    'status'      => 'in_cart',
                ],
                [
                    'home_team'      => $validated['home_team'],
                    'away_team'      => $validated['away_team'],
                    'match_date'     => $validated['match_date'],
                    'stadium'        => $validated['stadium'],
                    'category'       => $validated['category'],
                    'price'          => $validated['price'],
                    'quantity'       => 1,
                    'reserved_until' => Carbon::now()->addMinutes(15),
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Seat added to cart!',
                'cart_count' => GameCart::where('user_id', auth()->id())->where('status', 'in_cart')->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'This seat is already being processed.'], 422);
        }
    }

    /**
     * Add multiple seats (Form Submit)
     */
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

        $processedCount = 0;

        foreach ($seatsData as $seat) {
            // Provide fallback values if JS data is missing
            $stand  = !empty($seat['stand']) ? $seat['stand'] : 'General Admission';
            $row    = !empty($seat['row']) ? $seat['row'] : 'Standard';
            $number = $seat['number'] ?? ($seat['seat_number'] ?? rand(1000, 9999));

            try {
                // updateOrCreate inside Try-Catch ensures the loop never crashes the whole site
                GameCart::updateOrCreate(
                    [
                        'user_id'     => auth()->id(),
                        'api_game_id' => $request->api_game_id,
                        'stand'       => $stand,
                        'row'         => $row,
                        'seat_number' => $number,
                        'status'      => 'in_cart',
                    ],
                    [
                        'home_team'      => $request->home_team,
                        'away_team'      => $request->away_team,
                        'match_date'     => $request->match_date,
                        'stadium'        => $request->stadium,
                        'category'       => $seat['category'] ?? 'General',
                        'price'          => $seat['price'] ?? 0,
                        'quantity'       => 1,
                        'reserved_until' => Carbon::now()->addMinutes(15),
                    ]
                );
                $processedCount++;
            } catch (\Exception $e) {
                // Log the error but keep the loop running for other seats
                Log::warning("Duplicate seat skipped: User " . auth()->id() . " Game " . $request->api_game_id);
                continue;
            }
        }

        return redirect()->route('cart.index')->with('success', $processedCount . ' seat(s) updated in your cart!');
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
        $tickets = GameCart::where('user_id', Auth::id())
            ->where('status', 'paid')
            ->orderBy('match_date', 'desc')
            ->get()
            ->groupBy('api_game_id');

        return view('tickets.mytickets', compact('tickets'));
    }

    public function showMyTicket($id)
    {
        $reference = GameCart::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'paid')
            ->firstOrFail();

        $tickets = GameCart::where('api_game_id', $reference->api_game_id)
            ->where('user_id', Auth::id())
            ->where('status', 'paid')
            ->get()
            ->groupBy('api_game_id'); 

        return view('tickets.myticket', compact('tickets'));
    }
}