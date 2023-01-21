<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([
    'namespace' => 'Modules\Weather\Http\Controllers'
], function () {
    Route::prefix('weather')->group(function() {
        Route::group([
            'middleware' =>[ 'web','impersonate'],
        ], function () {
            Route::get('/weather/{lat}/{lng}', 'Main@getWeatherForLatLng')->name('weather.getweather');
            Route::get('/forecast/{lat}/{lng}', 'Main@getForecastForLatLng')->name('weather.getforecast');
        });
        
    });
});


