<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FootballController;

Route::get('/', function () {
    return redirect()->route('tickets.index');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

Route::middleware([RoleMiddleware::class . ':admin'])->group(function () {
    Route::resource('tickets', TicketController::class)->except(['index', 'show']);
    Route::get('/admin', function () {
        return view('admin.index');
    })->name('admin.index');
});

Route::middleware([RoleMiddleware::class . ':user,admin'])->group(function () {
    Route::get('tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
});

Route::get('/dashboard', function () {
    return view('user.index');
})->middleware([RoleMiddleware::class . ':user'])->name('user.dashboard');

Route::middleware([RoleMiddleware::class . ':user'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{ticket}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove/{cart}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/update/{cart}', [CartController::class, 'update'])->name('cart.update');
});

Route::middleware([RoleMiddleware::class . ':user,admin'])->group(function () {
    Route::get('/team-schedule', [FootballController::class, 'schedule'])->name('football.schedule');
});
