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

Route::post('login', 'api\EmployeeController@login')->name('login');
Route::post('register', 'api\EmployeeController@register')->name('register');
//Route::group(['middleware' => ['auth']], function () {
//    Route::get('logout', 'api\EmployeeController@logout');
//    Route::get('user', 'api\EmployeeController@user');
//});

/*=== api/vehicle ===*/
Route::group(['prefix' => 'vehicle'], function () {
    Route::post('/create', 'api\VehicleController@create')->name('vehicle.create');
    Route::post('/update/{id}', 'api\VehicleController@update')->name('vehicle.update');
    Route::delete('/delete/{id}', 'api\VehicleController@delete')->name('vehicle.delete');
    Route::post('/search', 'api\VehicleController@search')->name('vehicle.search');
    Route::get('/{id}', 'api\VehicleController@findId')->name('vehicle.findId');
});


/*=== api/station ===*/
Route::group(['prefix' => 'vehicle-station'], function () {
    Route::post('/create', 'api\StationController@create')->name('station.create');
    Route::post('/update/{id}', 'api\StationController@update')->name('station.update');
    Route::delete('/delete/{id}', 'api\StationController@delete')->name('station.delete');
    Route::post('/search', 'api\StationController@search')->name('station.search');
});


/*=== api/vehicle-station-detail ===*/
Route::group(['prefix' => 'vehicle-station-detail'], function () {
    Route::post('/create', 'api\StationDetailController@create')->name('station-detail.create');
    Route::post('/update/{id}', 'api\StationDetailController@update')->name('station-detail.update');
    Route::delete('/delete/{id}', 'api\StationDetailController@delete')->name('station-detail.delete');
    Route::post('/search', 'api\StationDetailController@search')->name('station-detail.search');
});

