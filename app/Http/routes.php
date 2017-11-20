<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'api/v1'], function(){
    
    Route::post('login-with-jwt', 'Auth\JWTAuthController@login');
    Route::get('me', 'Auth\JWTAuthController@profile');
    
    Route::post('login', 'Auth\ApiController@login');
    Route::post('register','Auth\ApiController@register');
    Route::get('getUser','Auth\ApiController@user');
    
    Route::resource('denuncias', 'Auth\ApiController');
    Route::get('denuncias_usuario/{usuario_id}', 'Auth\ApiController@denuncias_usuario');
});

