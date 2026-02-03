<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FootballService;

class SportMonksController extends Controller
{
    protected $football;

    public function __construct(FootballService $football)
    {
        $this->football = $football;
    }

    public function inplay(Request $request)
    {
        $data = $this->football->getSportMonksInplay(1);
        return response()->json($data);
    }
}
