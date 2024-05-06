<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [OrderController::class, 'index']);
Route::get('/get-dishes', [OrderController::class, 'getDishes'])->name('get-dishes');
