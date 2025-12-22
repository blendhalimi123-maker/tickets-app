<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GameCart;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $bookedTickets = Cart::count();

        return view('admin.dashboard', compact('totalUsers', 'bookedTickets'));
    }
}