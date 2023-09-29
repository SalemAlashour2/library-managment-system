<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/login',[AuthController::class,'login']);
Route::post('/auth/register',[AuthController::class,'register']);

Route::middleware('auth:sanctum')->get('/category/read',[CategoryController::class,'read']);
Route::middleware('auth:sanctum')->post('/category/create',[CategoryController::class,'create']);

Route::middleware('auth:sanctum')->get('/author/read', [AuthorController::class, 'read']);
Route::middleware('auth:sanctum')->post('/author/create', [AuthorController::class, 'create']);
