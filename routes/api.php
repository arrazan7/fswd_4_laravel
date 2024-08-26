<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthAPIController;
use App\Http\Controllers\UserAPIController;
use App\Http\Controllers\TravelAPIController;
use App\Http\Controllers\BookingAPIController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Open Routes
Route::post('/authenticate', [AuthAPIController::class, 'login']);
Route::post('/register', [UserAPIController::class, 'store']);
Route::post('/update-user/{id}', [UserAPIController::class, 'update']);
Route::post('/update-photo', [UserAPIController::class, 'updatePhoto']);
Route::get('/destroy-user/{id}', [UserAPIController::class, 'destroy']);
Route::get('/index-travel', [TravelAPIController::class, 'index']);
Route::post('/filter-travel', [TravelAPIController::class, 'filter']);
Route::get('/show-travel/{id}', [TravelAPIController::class, 'show']);
Route::get('/show-booking/{id}', [BookingAPIController::class, 'show']);
Route::post('/store-booking', [BookingAPIController::class, 'store']);
Route::get('/destroy-booking/{id}', [BookingAPIController::class, 'destroy']);


// Protected Routes
Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::get('/logout', [AuthAPIController::class, 'logout']);
    Route::get('/show-user', [UserAPIController::class, 'show']);
});
