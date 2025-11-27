<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home page redirects to tickets index
Route::get('/', function () {
    return redirect()->route('tickets.index');
});

// -------------------------
// Authentication routes
// -------------------------
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// -------------------------
// Ticket routes
// -------------------------

// Tickets index & show — accessible by admin and user
Route::middleware([RoleMiddleware::class.':user,admin'])->group(function () {
    Route::get('tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
});

// Admin-only: full CRUD (create, edit, update, delete)
Route::middleware([RoleMiddleware::class.':admin'])->group(function () {
    Route::resource('tickets', TicketController::class)->except(['index', 'show']);
});

// -------------------------
// RBAC dashboards
// -------------------------
Route::get('/admin', function () {
    return view('admin.index'); // admin dashboard blade
})->middleware([RoleMiddleware::class.':admin'])->name('admin.index');

Route::get('/dashboard', function () {
    return view('user.index'); // user dashboard blade
})->middleware([RoleMiddleware::class.':user'])->name('user.dashboard');

// -------------------------
// Shopping Cart routes (user only)
// -------------------------
Route::middleware([RoleMiddleware::class.':user'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index'); // View cart
    Route::post('/cart/add/{ticket}', [CartController::class, 'add'])->name('cart.add'); // Add to cart
    Route::post('/cart/remove/{cart}', [CartController::class, 'remove'])->name('cart.remove'); // Remove from cart
    Route::post('/cart/update/{cart}', [CartController::class, 'update'])->name('cart.update'); // Update quantity
});
