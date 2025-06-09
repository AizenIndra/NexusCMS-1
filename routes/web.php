<?php

use App\Http\Controllers\Frontend\ArmoryController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewsController;
use App\Http\Controllers\Frontend\UserController;
use App\Http\Controllers\GameAccountController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('news')->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('news');
    Route::get('/{slug}', [NewsController::class, 'show'])->name('news.show');
});

Route::prefix('auth')->group(function () {
    Route::get('/login', [UserController::class,'showLoginForm'])->name('login');
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/register', [UserController::class,'showRegisterForm'])->name('register');
    Route::post('/register', [UserController::class,'register']);
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});

Route::prefix('ucp')->middleware(['auth'])->group(function () {
    Route::get('/', [UserController::class, 'show'])->name('ucp.dashboard');
    Route::get('/gameaccount', [GameAccountController::class, 'index'])->name('ucp.gameaccount');
    Route::post('/gameaccount', [GameAccountController::class, 'store'])->name('ucp.gameaccount.store');
});

Route::prefix('armory')->group(function () {
    Route::get('/', [ArmoryController::class, 'index'])->name('armory');
    Route::get('armory/{id}', [ArmoryController::class, 'show'])->name('armory.show');
});