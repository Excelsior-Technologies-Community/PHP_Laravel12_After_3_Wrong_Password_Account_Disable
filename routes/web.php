<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountAuthController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/register', [AccountAuthController::class, 'register']);
Route::post('/register', [AccountAuthController::class, 'registerPost']);

Route::get('/login', [AccountAuthController::class, 'login']);
Route::post('/login', [AccountAuthController::class, 'loginPost']);

Route::get('/dashboard', [AccountAuthController::class, 'dashboard']);
Route::get('/logout', [AccountAuthController::class, 'logout']);
