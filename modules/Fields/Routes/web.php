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

Route::prefix('fields')->group(function() {
    Route::get('/', 'FieldsController@index');
});

Route::group([
    'middleware' =>[ 'web','impersonate'],
    'namespace' => 'Modules\Fields\Http\Controllers'
], function () {
    Route::prefix('fields')->group(function() { 
            Route::get('/list', 'Main@index')->name('fields.index');
            Route::post('/list', 'Main@store')->name('fields.store');
            Route::get('/remove/{field}', 'Main@remove')->name('fields.remove');
            Route::get('/image/{field}', 'Main@imageOfField')->name('fields.image');
            Route::get('/notes/widget/{field}', 'Main@noteforfield')->name('fields.note');
    });
});
