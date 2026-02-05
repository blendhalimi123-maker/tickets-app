<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameCartController;
use App\Http\Controllers\Auth\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| These routes are loaded by the Application and assigned the "api"
| middleware group.
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login'])
    ->name('api.login');

Route::middleware('auth:sanctum')->get('/tickets', [
    GameCartController::class,
    'tickets_api'
])->name('api.tickets');

Route::get('/team/map', [\App\Http\Controllers\Api\TeamMapController::class, 'map']);
