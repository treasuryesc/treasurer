<?php

use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' => 'admin',
    'namespace' => 'Modules\Loans\Http\Controllers'
], function () {
    Route::group(['prefix' => 'loans', 'as' => 'loans.'], function () {
        Route::group(['prefix' => 'receivables', 'as' => 'receivables.'], function () {
            // Route::get('/', 'Receivables@index')->name('index');
        });
    });
});
