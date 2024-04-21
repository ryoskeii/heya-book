<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\ResourceController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/token', [AuthController::class, 'getToken']);
});

Route::prefix('booking')->group(function () {
    Route::get('/{id}', [BookingController::class, 'findById'])->middleware('auth');
    Route::get('/', [BookingController::class, 'findForUser'])->middleware('auth');
    Route::post('/', [BookingController::class, 'create'])->middleware('auth');
});

Route::prefix('resource')->group(function () {
    Route::get('/{id}', [ResourceController::class, 'findById'])->middleware('auth');
    Route::get('/', [ResourceController::class, 'find'])->middleware('auth');
    Route::post('/', [ResourceController::class, 'create'])->middleware('auth');
});

Route::prefix('facility')->group(function () {
  Route::get('/{id}', [FacilityController::class, 'findById'])->middleware('auth');
  Route::post('/', [FacilityController::class, 'create'])->middleware('auth');
});