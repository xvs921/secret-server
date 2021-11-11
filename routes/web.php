<?php

use Illuminate\Support\Facades\Route;

//LIST SECRETS
Route::get('/', 'App\Http\Controllers\SecretController@index');
Route::get('/v1', 'App\Http\Controllers\SecretController@index');

Route::post('/v1/secret', 'App\Http\Controllers\SecretController@createSecret');
Route::get('/v1/secret', 'App\Http\Controllers\SecretController@secretFromForm');
Route::get('/v1/secret/{secret}', 'App\Http\Controllers\SecretController@getSecret');
