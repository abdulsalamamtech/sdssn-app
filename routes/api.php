<?php

use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


// API test route
Route::get('/', function (Request $request) {
    return [
        'success' => true,
        'message' => 'api is live',
        'data' => [
            'date' => now()->format('Y-m-d H:i:s'),
            'request' => $request->all(),
            'environment' => config('app.env'),
            'debug' => config('app.debug')
        ],
        'Laravel' => app()->version()
    ];
});


// Authentication routes
// Register route
Route::post('register', [AuthController::class, 'register'])
    ->middleware('guest');
// Login route
Route::post('login', [AuthController::class, 'login'])
    ->middleware('guest');
// Logout route
Route::post('logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

