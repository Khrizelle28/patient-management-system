<?php

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
