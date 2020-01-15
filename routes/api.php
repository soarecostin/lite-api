<?php

use App\Http\Controllers\TokensController;
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

Route::post('auth/register', 'RegisterController@register');
Route::post('auth/login', 'AuthController@login')->name('login');

Route::middleware('auth:api,airlock')->group(function () {
    Route::post('auth/logout', 'AuthController@logout');
    Route::post('auth/refresh', 'AuthController@refresh');
    Route::get('auth/me', 'AuthController@me');

    Route::get('field-types', 'FieldTypesController@index');
    Route::apiResource('fields', 'FieldsController');
    Route::apiResource('subscribers', 'SubscribersController');

    Route::get('/tokens', 'TokensController@index');
    Route::post('/tokens', 'TokensController@store');
    Route::delete('/tokens/{token}', 'TokensController@destroy');
});