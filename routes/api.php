<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KioskController;
use App\Http\Controllers\Api\PublicController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public API - No auth required
Route::prefix('public')->group(function () {
    Route::get('/books', [PublicController::class, 'books']);
});

// Kiosk API with rate limiting
Route::prefix('kiosk')->group(function () {
    // Login: 10 attempts per minute
    Route::post('/login', [KioskController::class, 'login'])
        ->middleware('throttle:10,1');
    
    Route::middleware(['auth:sanctum', 'throttle:5,1'])->group(function () {
        // Borrow/Return: 5 attempts per minute
        Route::post('/borrow', [KioskController::class, 'borrow']);
        Route::post('/return', [KioskController::class, 'returnBook']);
    });
});
