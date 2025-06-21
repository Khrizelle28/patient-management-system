<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });

    Route::get('login', [LoginController::class, 'index'])->name('login');
});

Route::post('login', [LoginController::class, 'login'])->name('login');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Route::resource('register', RegisterController::class);
    // regiter.in
    Route::get('admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('admin/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('admin/store', [AdminController::class, 'store'])->name('admin.store');
    Route::get('admin/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('admin/{id}', [AdminController::class, 'update'])->name('admin.update');

    Route::get('patient', [PatientController::class, 'index'])->name('patient.index');
    Route::get('patient/create', [PatientController::class, 'create'])->name('patient.create');
    Route::post('patient/store', [PatientController::class, 'store'])->name('patient.store');
    Route::get('patient/{id}/show', [PatientController::class, 'show'])->name('patient.show');
    Route::get('patient/{id}/edit', [PatientController::class, 'edit'])->name('patient.edit');
    Route::put('patient/{id}', [PatientController::class, 'update'])->name('patient.update');
    Route::get('patient/{id}/checkup', [PatientController::class, 'checkup'])->name('patient.checkup');
    Route::post('patient/{id}/diagnosis', [PatientController::class, 'diagnosis'])->name('patient.diagnosis');

    Route::get('appointment', [AppointmentController::class, 'index'])->name('appointment.index');
});
