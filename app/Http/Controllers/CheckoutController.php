<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameCart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; 
use App\Mail\UserTicketMail; 
use App\Mail\AdminNewSaleMail;

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

        GameCart::where('user_id', $user->id)
            ->where('status', 'in_cart')
            ->update([
                'status' => 'paid',
                'reserved_until' => null,
                'updated_at' => now()
            ]);

        try {
            Mail::to($user->email)->send(new UserTicketMail($cartItems, $user));
            Mail::to('blendhalimi123@gmail.com')->send(new AdminNewSaleMail($cartItems, $user));
        } catch (\Exception $e) {
            \Log::error("Mail failed: " . $e->getMessage());
            session()->flash('mail_warning', 'Payment succeeded, but we could not send the email receipt right now (mail is not configured on this server).');
        }

        return redirect()->route('checkout.success', ['id' => $purchasedId]);
    }

    public function success($id)
    {
        return view('checkout.success', compact('id'));
    }
}