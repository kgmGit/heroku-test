<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeleteUser;
use App\Http\Controllers\SpaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::post('/login', [AuthController::class, 'login'])->name('login');
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/user', DeleteUser::class)->name('delete-user');
});
Route::get('/{any?}', [SpaController::class, 'index'])->where('any', '.+');
