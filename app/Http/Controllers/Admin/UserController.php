<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        //
        $users = User::orderBy('created_at', 'desc')->get();

        
        return view('admin.ManageUsers', compact('users'));
    }
}
