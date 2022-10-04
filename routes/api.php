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

Route::post('login', 'api\EmployeeController@login');
Route::post('register', 'api\EmployeeController@register');
//Route::group(['middleware' => ['auth']], function () {
//    Route::get('logout', 'api\EmployeeController@logout');
//    Route::get('user', 'api\EmployeeController@user');
//});

/*=== api/vehicle */
Route::post('vehicle/create', 'api\VehicleController@create');
Route::put('vehicle/update/{id}', 'api\VehicleController@update');
Route::delete('vehicle/delete/{id}', 'api\VehicleController@delete');
Route::get('vehicle/search/kw={kw}', 'api\VehicleController@search');

/*=== api/station */
Route::post('station/create', 'api\StationController@create');
