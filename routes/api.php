<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\ParkingSlotController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('/login', 'login');
    Route::get('/logout', 'logout')->middleware('auth:sanctum');
    Route::post('/register', 'register');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/users', 'index');
        Route::get('/users/{user}', 'show');
        Route::delete('/users/{user}', 'delete');
        Route::get('/users/{user}/cars', 'cars');
        Route::get('/users/{user}/payments', 'payments');
        Route::get('/users/{user}/bookings', 'bookings');
    });

    Route::controller(CarController::class)->group(function () {
        Route::post('/cars', 'store');
        Route::get('/cars', 'index');
        Route::get('/cars/{car}', 'show');
        Route::patch('/cars/{car}', 'update');
        Route::delete('/cars/{car}', 'delete');
    });

    Route::controller(BookingController::class)->group(function () {
        Route::get('/bookings', 'index');
        Route::post('/bookings', 'store');
        Route::get('/bookings/{booking}', 'show');
        Route::get('/bookings/{booking}/cancel', 'cancel');
        Route::patch('/bookings/{booking}/update', 'update');
        Route::post('/bookings/{booking}/payments/creditCard', 'storeCreditCard');
        Route::get('/bookings/{booking}/payments/cash', 'storeCash');
    });

    Route::controller(PaymentController::class)->group(function () {
        Route::get('/payments', 'index');
        Route::patch('/payments/{payment}', 'update');
        Route::post('/payments/{payment}/pay', 'pay');
        Route::get('/payments/{payment}', 'show');
    });

    Route::controller(ParkingSlotController::class)->group(function () {
        Route::patch('/slots/{slot}', 'update');
        Route::post('/slots', 'store');
        Route::delete('/slots/{slot}', 'delete');
        Route::get('/slots/{slot}/show', 'show');
    });
});

Route::controller(ParkingSlotController::class)->group(function () {
    Route::get('/slots', 'index');
    Route::get('/slots/sort', 'sort');
    Route::get('/slots/search', 'search');
});


