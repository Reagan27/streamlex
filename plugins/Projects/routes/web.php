<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| Now create something great!
|
*/

Route::prefix('projects')->group(function () {
    Route::get('/', 'ProjectsController@index')->middleware('auth');
});
