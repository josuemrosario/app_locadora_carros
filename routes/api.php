<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//aula 289 
//Route::resource('cliente','App\Http\Controllers\ClienteController');
Route::apiresource('cliente','App\Http\Controllers\ClienteController');
Route::apiresource('carro','App\Http\Controllers\CarroController');
Route::apiresource('locacao','App\Http\Controllers\LocacaoController');
Route::apiresource('marca','App\Http\Controllers\MarcaController');
Route::apiresource('modelo','App\Http\Controllers\ModeloController');