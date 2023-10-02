<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BookController;
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

Route::prefix('/auth')->controller(AuthController::class)->group(function () {
    Route::post('/login','login');
    Route::post('/register','register');
    Route::post('/register/admin','registerAdmin');
    Route::post('/login/admin','loginAdmin');
});

Route::prefix('category')->controller(CategoryController::class)->group(function () {
    
    Route::get('read','read');
    Route::get('find','find');

    Route::middleware(['auth:sanctum','abilities:category-create,category-update,category-delete'])->group(function(){
        Route::post('create','create');
        Route::put('update','update');
    });
});



Route::prefix('author')->controller(AuthorController::class)->group(function () {

    Route::get('read','read');
    Route::get('find','find');

    Route::middleware(['auth:sanctum','abilities:author-create,author-delete,author-update'])->group(function () {
    Route::post('create','create');
    Route::put('update','update');
    });
    
});



Route::prefix('book')->controller(BookController::class)->group(function () {

    Route::get('read','read');
    Route::get('find','find');

    Route::middleware(['auth:sanctum','abilities:book-create,book-update,book-delete'])->group(function () {
    Route::post('create','create');
    Route::post('update','update');
    Route::post('upload','uploadImageRequest');
    });
});
