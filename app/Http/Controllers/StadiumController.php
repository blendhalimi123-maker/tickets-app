<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StadiumController extends Controller
{
    public function show($fixture_id)
    {
        return view('stadium.index', ['fixture_id' => $fixture_id]);
    }

    public function selectSeat(Request $request)
    {
        return response()->json([
            'success' => true,
            'seat' => $request->input('seat_number'),
            'message' => 'Seat selected successfully (placeholder)'
        ]);
    }
}
