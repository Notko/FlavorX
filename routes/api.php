<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// POST Routes
Route::post('/user/create', [UserController::class, 'add']);
Route::post('/user/login', [UserController::class, 'login']);

// GET Routes
Route::get('/user/{id}', [UserController::class, 'get']);

// PUT Routes

// PATCH Routes

// DELETE Routes
