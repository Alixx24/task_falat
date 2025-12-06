<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/user-list', [UserController::class, 'index']);

//show
Route::get('/user/{id}', [UserController::class, 'show']);

//update
Route::put('/user/{id}', [UserController::class, 'update']);

//search
Route::get('/user-search', [UserController::class, 'search']);

//store
Route::post('/user', [UserController::class, 'store']);

//delete
Route::delete('/user/{id}', [UserController::class, 'destroy']);
