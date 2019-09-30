<?php

use Illuminate\Http\Request;

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

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@login');
	Route::put('user', 'UserController@updateSaldo');
Route::middleware(['jwt.verify'])->group(function() {
	Route::get('/transaksi', 'SaldoController@saldo');
	Route::get('/transaksiall', 'SaldoController@saldoAuth');
	Route::get('/saldo', 'SaldoController@index');
	Route::get('/saldo{id}', 'SaldoController@show');
	Route::put('/saldo{id}', 'SaldoController@update');
	Route::post('/saldo', 'SaldoController@store');

});

