<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

//Auth Controller

Route::controller(AuthController::class)->group(function () {
Route::post('/register','register');
Route::post('/login', 'login');
});

Route::controller(AuthController::class)->middleware('auth:api')->group(function () {
        Route::post('/logout', 'logout');
        Route::get('/profile', 'profile');
        // Route::get('/check-role', 'checkRole');
    });

    //PostController
    Route::controller(PostController::class)->middleware('auth:api')->group(function () {
    Route::post('/posts', 'store');
    Route::get('/posts', 'index');
    Route::get('/posts/{id}','show');
    Route::put('/posts/{id}','update');
    Route::delete('/posts/{id}','destroy');
});


//CommentController
Route::controller(CommentController::class)->middleware('auth:api')->group(function () {
    Route::post('/posts/{id}/comments','store');
});
