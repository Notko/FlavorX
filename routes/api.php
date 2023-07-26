<?php

use App\Http\Controllers\RecipeController;
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

Route::middleware('auth:api')->post('/recipe/new', [RecipeController::class, 'add']);

// GET Routes
Route::get('/user/{id}', [UserController::class, 'get']);

// PUT Routes

// PATCH Routes
Route::middleware('auth:api')->patch('/user/update', [UserController::class, 'update']);

// DELETE Routes
Route::middleware('auth:api')->delete('/user/delete', [UserController::class, 'delete']);
