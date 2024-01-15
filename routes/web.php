<?php

use App\Models\User;
use App\Notifications\APICallFailedNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// // TODO: remove
// Route::get('/test-email', function(){
//     $user = User::where('email', 'admin@bat-signal-api.com')->first();
//     Notification::send($user , new APICallFailedNotification('ERROR: failed to send panic'));
// });