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


Route::middleware(['web', 'auth', 'locale'])->group(function () {

    Route::get('test', function () {

        // Just a test
        if (config('app.debug') == true) {
            print_r(url('/'));
            dd(app()->request->header());
        } else {
            abort(404);
        }
        
    });    

});
