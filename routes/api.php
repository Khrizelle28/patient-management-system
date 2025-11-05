<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\MedicationAlertController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorScheduleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('doctor-schedule', [DoctorScheduleController::class, 'getDoctorSchedule']);
Route::post('appointments', [AppointmentController::class, 'store']);
Route::get('/appointments/patient/{patientId}', [AppointmentController::class, 'getPatientAppointments']);

// Product routes
Route::get('products', [ProductController::class, 'index']);
Route::get('available-products', [ProductController::class, 'getAvailableProducts']);

// Cart routes
Route::get('cart', [CartController::class, 'index']);
Route::post('cart', [CartController::class, 'store']);
Route::put('cart/{id}', [CartController::class, 'update']);
Route::delete('cart/{id}', [CartController::class, 'destroy']);
Route::delete('cart-clear', [CartController::class, 'clear']);

// Order routes
Route::get('orders', [OrderController::class, 'index']);
Route::post('orders/place', [OrderController::class, 'placeOrder']);
Route::get('orders/{id}', [OrderController::class, 'show']);
Route::post('orders/{id}/cancel', [OrderController::class, 'cancel']);

// Medication Alert routes
Route::get('medication-alerts/patient/{patientId}', [MedicationAlertController::class, 'index']);
Route::post('medication-alerts', [MedicationAlertController::class, 'store']);
Route::get('medication-alerts/{id}', [MedicationAlertController::class, 'show']);
Route::put('medication-alerts/{id}', [MedicationAlertController::class, 'update']);
Route::delete('medication-alerts/{id}', [MedicationAlertController::class, 'destroy']);
