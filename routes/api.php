<?php

use App\Http\Controllers\API\PanicController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
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

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/send-panic', [PanicController::class, 'send']);
    Route::post('/cancel-panic', [PanicController::class, 'cancel']);
    Route::get('/get-panic-history', [PanicController::class, 'history']);
});

Route::post('/login', [UserController::class, 'login']);
