<?php
// routes/web.php (Simpler version without custom middleware)

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountAuthController;

Route::get('/', function () {
    return view('welcome');
});

// Public Routes
Route::get('/register', [AccountAuthController::class, 'register']);
Route::post('/register', [AccountAuthController::class, 'registerPost']);

Route::get('/login', [AccountAuthController::class, 'login']);
Route::post('/login', [AccountAuthController::class, 'loginPost']);

// Protected Routes (authentication check is done inside each controller method)
Route::get('/dashboard', [AccountAuthController::class, 'dashboard']);
Route::get('/logout', [AccountAuthController::class, 'logout']);
Route::get('/login-attempts', [AccountAuthController::class, 'loginAttempts']);
Route::get('/admin/blocked-accounts', [AccountAuthController::class, 'blockedAccounts'])->name('admin.blocked');
Route::post('/admin/unblock/{id}', [AccountAuthController::class, 'unblockAccount'])->name('admin.unblock');
Route::get('/profile', [AccountAuthController::class, 'showProfile']);
Route::post('/profile/update', [AccountAuthController::class, 'updateProfile']);