<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DeleteUser;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Resources\UserResource;
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
// */
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return new UserResource($request->user());
    });

    Route::middleware('verified')->group(function () {
        // email認証済みルート

        // Room
        Route::get('/rooms', [RoomController::class, 'index']);
        Route::post('/rooms', [RoomController::class, 'store']);
        Route::patch('/rooms/{room:name}', [RoomController::class, 'update']);
        Route::delete('/rooms/{room:name}', [RoomController::class, 'destroy']);
        Route::put('/rooms/{room:name}/members', [RoomController::class, 'join']);

        // Comment
        Route::get('/rooms/{room:name}/comments', [CommentController::class, 'index']);
        Route::post('/rooms/{room:name}/comments', [CommentController::class, 'store']);

        // User
        Route::get('/rooms/{room:name}/members', [UserController::class, 'index']);
    });
});
