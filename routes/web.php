<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');


Route::view('/dashboard', 'dashboard');
Route::view('/login', 'auth.login');
Route::view('/register', 'auth.register');

Route::view('/users', 'users.index');
Route::view('/users/{user}/cars', 'users.user.cars');
Route::view('/users/{user}/payments', 'users.user.payments');
Route::view('/users/{user}/bookings', 'users.user.bookings');

Route::view('/cars', 'cars.index');
Route::view('/cars/create', 'cars.create');
Route::view('/cars/{car}/update', 'cars.car.update');

Route::view('/bookings', 'bookings.index');
Route::view('/bookings/{booking}/update', 'bookings.booking.update');
Route::view('/bookings/create', 'bookings.create');
Route::view('/bookings/{booking}/storeCreditCard', 'bookings.booking.storeCreditCard');

Route::view('/payments', 'payments.index');
Route::view('/payments/{payment}/update', 'payments.payment.update');
Route::view('/payments/{payment}/pay', 'payments.payment.pay');

Route::view('/slots', 'slots.index');
Route::view('/slots/create', 'slots.create');
Route::view('/slots/{slot}/update', 'slots.slot.update');
