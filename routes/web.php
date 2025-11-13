<?php
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AdmissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RoomController::class, 'index'])->name('dashboard');
Route::post('/rooms/{room}/status', [RoomController::class, 'updateStatus'])->name('rooms.updateStatus');
Route::post('/admissions', [AdmissionController::class, 'store'])->name('admissions.store');
Route::post('/admissions/{admission}/discharge', [AdmissionController::class, 'discharge'])->name('admissions.discharge');
