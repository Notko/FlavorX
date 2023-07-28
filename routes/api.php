<?php

use App\Http\Controllers\LikeController;
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
Route::middleware('auth:api')->post('/recipe/like', [LikeController::class, 'add']);

// GET Routes
Route::get('/user/{id}', [UserController::class, 'get']);

Route::get('/recipe/{id}', [RecipeController::class, 'get']);
Route::get('/recipes', [RecipeController::class, 'getAll']); //?limit=10&offset=0


// PUT Routes

// PATCH Routes
Route::middleware('auth:api')->patch('/user/update', [UserController::class, 'update']);
Route::middleware('auth:api')->patch('/recipe/update', [RecipeController::class, 'update']);

// DELETE Routes
Route::middleware('auth:api')->delete('/user/delete', [UserController::class, 'delete']);
Route::middleware('auth:api')->delete('/recipe/delete', [RecipeController::class, 'delete']);
Route::middleware('auth:api')->delete('/recipe/unlike', [LikeController::class, 'delete']);
