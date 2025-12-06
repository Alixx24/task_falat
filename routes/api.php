<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/user-list', [UserController::class, 'index']);

// نمایش یک کاربر
Route::get('/user/{id}', [UserController::class, 'show']);

// آپدیت یک کاربر
Route::put('/user/{id}', [UserController::class, 'update']);

//search
Route::get('/user-search', [UserController::class, 'search']);
