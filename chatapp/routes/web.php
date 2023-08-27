<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/search', 'App\Http\Controllers\ChatController@searchUsers');
Route::get('/users', 'App\Http\Controllers\ChatController@getAllUsers');


Route::get('/user/{id}', 'App\Http\Controllers\ChatController@userChat')->name('user.chat');
Route::post('insert-chat', 'App\Http\Controllers\ChatController@insertChat');
Route::post('get-chat', 'App\Http\Controllers\ChatController@getChat');
