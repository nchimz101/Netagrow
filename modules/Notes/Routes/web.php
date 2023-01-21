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

Route::prefix('notes')->group(function() {
    Route::get('/', 'NotesController@index');
});

Route::group([
    'namespace' => 'Modules\Notes\Http\Controllers'
], function () {
    Route::prefix('publicnote')->group(function() { 
            Route::get('/{note}', 'Main@show')->name('notes.show');
    });
});

Route::group([
    'middleware' =>[ 'web','impersonate'],
    'namespace' => 'Modules\Notes\Http\Controllers'
], function () {
    Route::prefix('notes')->group(function() { 
            Route::get('/new', 'Main@new')->name('notes.index');
            Route::get('/edit/{note}', 'Main@edit')->name('notes.edit');
            Route::put('/update/{note}', 'Main@update')->name('notes.update');
            Route::post('/new', 'Main@store')->name('notes.store');
            Route::get('/remove/{note}', 'Main@remove')->name('notes.remove');
    });
});


