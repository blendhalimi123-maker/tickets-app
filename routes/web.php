<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Api\FootballController as ApiFootballController;
use App\Http\Controllers\StadiumController;
<<<<<<< HEAD
use App\Http\Controllers\Admin\UserController;
=======
use App\Models\User;
use App\Models\Ticket;
>>>>>>> e6680a0cba488afe038c406a86dcd800e3ed15d8

Route::get('/', function () {
    return redirect()->route('user.dashboard');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Admin
Route::middleware([RoleMiddleware::class . ':admin'])->group(function () {
    Route::resource('tickets', TicketController::class)->except(['index', 'show']);
    
    Route::get('/admin', function () {
        return view('admin.index');
    })->name('admin.index');
<<<<<<< HEAD
    Route::get('/admin/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/admin/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
=======
    
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard', [
            'totalUsers' => User::count(),
            'activeTickets' => Ticket::where('status', '!=', 'closed')->count(),
            'upcomingMatches' => 5,
            'pendingActions' => 3,
        ]);
    })->name('admin.dashboard');
    
    Route::get('/admin/schedules', function () {
        return view('admin.schedules');
    })->name('admin.schedules');
    
    Route::post('/admin/schedules', function () {
        return redirect()->route('admin.schedules')->with('success', 'Schedule added successfully!');
    })->name('admin.schedules.store');
    
    Route::get('/admin/users', function () {
        $users = User::all();
        return view('admin.users', compact('users'));
    })->name('admin.users');
    
    Route::get('/admin/users/create', function () {
        return view('admin.users.create');
    })->name('admin.users.create');
    
    Route::post('/admin/users', function () {
        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    })->name('admin.users.store');
    
    Route::get('/admin/users/{user}/edit', function ($user) {
        $user = User::findOrFail($user);
        return view('admin.users.edit', compact('user'));
    })->name('admin.users.edit');
    
    Route::put('/admin/users/{user}', function ($user) {
        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    })->name('admin.users.update');
    
    Route::delete('/admin/users/{user}', function ($user) {
        User::findOrFail($user)->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    })->name('admin.users.destroy');
>>>>>>> e6680a0cba488afe038c406a86dcd800e3ed15d8
});

Route::middleware([RoleMiddleware::class . ':user,admin'])->group(function () {
    Route::get('tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
});

Route::get('/dashboard', function () {
    return view('user.index');
})->name('user.dashboard');

Route::get('/team-schedule', function () {
    return view('football.schedule');
})->name('football.schedule');

Route::middleware([RoleMiddleware::class . ':user'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{ticket}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove/{cart}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/update/{cart}', [CartController::class, 'update'])->name('cart.update');
});

Route::prefix('api/football')->group(function () {
    Route::get('/all', [ApiFootballController::class, 'allCompetitions']);
    Route::get('/champions-league', [ApiFootballController::class, 'championsLeague']);
    Route::get('/premier-league', [ApiFootballController::class, 'premierLeague']);
    Route::get('/world-cup', [ApiFootballController::class, 'worldCup']);
});

Route::get('/stadium/{fixture_id}', [StadiumController::class, 'show'])->name('stadium.show');
Route::post('/stadium/select', [StadiumController::class, 'selectSeat'])->name('stadium.select');

Route::get('/password/change', [App\Http\Controllers\ProfileController::class, 'editPassword'])
    ->name('password.change')
    ->middleware('auth');

Route::post('/password/change', [App\Http\Controllers\ProfileController::class, 'updatePassword'])
    ->name('password.update')
    ->middleware('auth');