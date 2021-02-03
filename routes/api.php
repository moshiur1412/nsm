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

Route::prefix('/back')->group(function(){
	Route::group(['middleware' => ['api']], function(){
		Route::resource('school', 'Api\SchoolController', ['except' => ['create', 'edit','store', 'update','delete', 'confirm']]);
	});
});
Route::prefix('/app')->group(function(){
	Route::group(['middleware' => ['api']], function(){
		Route::resource('school', 'Api\SchoolController', ['except' => ['create', 'edit','store', 'update','delete', 'confirm']]);
	});
});
