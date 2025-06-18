<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::get('/connect', [ChatController::class, 'connect'])->name('connect');
    Route::post('/send-request', [ChatController::class, 'sendRequest'])->name('sendRequest');
    Route::post('/accept-request/{id}', [ChatController::class, 'acceptRequest']);
    Route::post('/send-message', [ChatController::class, 'sendMessage']);
    Route::get('/get-messages/{receiver_id}', [ChatController::class, 'getMessages']);
});