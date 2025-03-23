<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('register', RegisterController::class);
Route::get('logout', [LoginController::class, 'logout'])->name('logout');