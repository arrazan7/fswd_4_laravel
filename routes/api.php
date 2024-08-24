<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthAPIController;
use App\Http\Controllers\UserAPIController;
use App\Http\Controllers\TravelAPIController;
use App\Http\Controllers\OrderAPIController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Open Routes
Route::post('/authenticate', [AuthAPIController::class, 'login']);
Route::post('/register', [UserAPIController::class, 'store']);
Route::post('/update-user/{id}', [UserAPIController::class, 'update']);
Route::get('/destroy-user/{id}', [UserAPIController::class, 'destroy']);
Route::get('/index-travel', [TravelAPIController::class, 'index']);
Route::post('/filter-travel', [TravelAPIController::class, 'filter']);
Route::get('/show-travel/{id}', [TravelAPIController::class, 'show']);
Route::get('/show-order/{id}', [OrderAPIController::class, 'show']);
Route::post('/store-order', [OrderAPIController::class, 'store']);
Route::get('/destroy-order/{id}', [OrderAPIController::class, 'destroy']);


// Protected Routes
Route::group(["middleware" => ["auth:sanctum"]], function(){
    Route::get('/logout', [AuthAPIController::class, 'logout']);
    Route::get('/show-user', [UserAPIController::class, 'show']);
});
