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

/*=== api/vehicle ===*/
Route::group(['prefix' => 'vehicle'], function () {
    Route::post('/create', 'api\VehicleController@create');
    Route::post('/update/{id}', 'api\VehicleController@update');
    Route::delete('/delete/{id}', 'api\VehicleController@delete');
    Route::get('/search/kw={kw}', 'api\VehicleController@search');
    Route::get('/{id}', 'api\VehicleController@findId');
});


/*=== api/station ===*/
Route::group(['prefix' => 'vehicle-station'], function () {
    Route::post('/create', 'api\StationController@create');
    Route::post('/update/{id}', 'api\StationController@update');
    Route::delete('/delete/{id}', 'api\StationController@delete');
    Route::get('/search/kw={kw}', 'api\StationController@search');
});


/*=== api/vehicle-station-detail ===*/
Route::group(['prefix' => 'vehicle-station-detail'], function () {
    Route::post('/create', 'api\StationDetailController@create');
    Route::post('/update/{id}', 'api\StationDetailController@update');
    Route::delete('/delete/{id}', 'api\StationDetailController@delete');
    Route::get('/search/kw={kw}', 'api\StationDetailController@search');
});

