<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameCart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; 
use App\Mail\UserTicketMail; 
use App\Mail\AdminNewSaleMail;
use Illuminate\Support\Facades\Log; 
use App\Events\TicketPurchased;
use App\Events\GameTicketSold;
use App\Models\Game;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = GameCart::where('user_id', Auth::id())
            ->where('status', 'in_cart')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        
        $serviceFee = $cartItems->count() * 2.50;
        $total = $subtotal + $serviceFee;

        return view('checkout.index', compact('cartItems', 'subtotal', 'serviceFee', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'card_name'   => 'required|string|min:3',
            'card_number' => 'required|digits:16',
            'expiry'      => 'required|string', 
            'cvv'         => 'required|digits:3',
        ]);

        $user = Auth::user();
        $cartItems = GameCart::where('user_id', $user->id)
            ->where('status', 'in_cart')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('football.schedule');
        }

        $purchasedId = $cartItems->first()->id;

        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $serviceFee = $cartItems->count() * 2.50;
        $total = $subtotal + $serviceFee;

        GameCart::where('user_id', $user->id)
            ->where('status', 'in_cart')
            ->update([
                'status' => 'paid',
                'reserved_until' => null,
                'updated_at' => now()
            ]);

        // Update games tickets_sold and broadcast to favorited subscribers
        try {
            $grouped = $cartItems->groupBy('api_game_id');
            foreach ($grouped as $apiId => $items) {
                $soldQty = $items->sum('quantity');
                $game = Game::firstOrCreate(['api_game_id' => $apiId], ['title' => 'Match ' . $apiId]);
                $game->increment('tickets_sold', $soldQty);
                // reload to get current tickets_sold/left
                $game->refresh();

                event(new GameTicketSold($game, $soldQty));
            }
        } catch (\Exception $e) {
            Log::error('Failed to update Game tickets or broadcast GameTicketSold: ' . $e->getMessage());
        }

        try {
            Mail::to('blendhalimi123@gmail.com')->send(new AdminNewSaleMail($cartItems, $user));

            if ($user && $user->email) {
                Mail::to($user->email)->send(new UserTicketMail($cartItems, $user));
            } else {
                Log::warning("User email missing for User ID: " . $user->id);
            }

            try {
                event(new TicketPurchased($cartItems, $user, $total));
            } catch (\Exception $e) {
                Log::error('Failed to dispatch TicketPurchased event: ' . $e->getMessage());
            }

        } catch (\Exception $e) {
            Log::error("Mail failed to send. Error: " . $e->getMessage());
            
            session()->flash('mail_warning', 'Payment succeeded, but we could not send the email receipt. Error: ' . $e->getMessage());
        }

        return redirect()->route('checkout.success', ['id' => $purchasedId]);
    }

    public function success($id)
    {
        return view('checkout.success', compact('id'));
    }
}