<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/cart', 'CartController@showCart');
Route::post('/cart', 'CartController@processCart');

Route::get('/history', 'HistoryController@searchHistory');
Route::post('/history', 'HistoryController@showHistory');
