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
//aula 333 acrescentado os middlewares
// aula 334 agrupado em rotas e acrescentado prefixo de api

Route::prefix('v1')->middleware('jwt.auth')->group(function(){
    Route::apiresource('cliente','App\Http\Controllers\ClienteController');
    Route::apiresource('carro','App\Http\Controllers\CarroController');
    Route::apiresource('locacao','App\Http\Controllers\LocacaoController');
    Route::apiresource('marca','App\Http\Controllers\MarcaController');
    Route::apiresource('modelo','App\Http\Controllers\ModeloController');

    //aula 336
    Route::post('me','App\Http\Controllers\AuthController@me');

    //aula 337
    Route::post('refresh','App\Http\Controllers\AuthController@refresh');

    //aula 338
    Route::post('logout','App\Http\Controllers\AuthController@logout');
});



//Aula 330 - definindo rotas para usar jwt
Route::post('login','App\Http\Controllers\AuthController@login');
//Route::post('logout','App\Http\Controllers\AuthController@logout');
//Route::post('refresh','App\Http\Controllers\AuthController@refresh');
//Route::post('me','App\Http\Controllers\AuthController@me');

