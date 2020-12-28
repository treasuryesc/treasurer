<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'admin',
    'namespace' => 'Modules\Operations\Http\Controllers'
], function () {
    //SETTINGS
    Route::group(['prefix' => 'operations', 'as' => 'operations.'], function () {
        Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
            Route::get('/', 'Settings@index')->name('index');

            //LOAN TYPES
            Route::get('loan-types', 'Settings@loanTypes')->name('loan-types.index');
            Route::get('loan-types/create', 'Settings@loanTypesCreate')->name('loan-types.create');
            Route::post('loan-types/store', 'Settings@loanTypesStore')->name('loan-types.store');
            Route::get('loan-types/{id}/edit', 'Settings@loanTypesEdit')->name('loan-types.edit');
            Route::patch('loan-types/update', 'Settings@loanTypesUpdate')->name('loan-types.update');

            //RECEIVABLE TYPES
            Route::get('receivable-types', 'Settings@receivableTypes')->name('receivable-types.index');
            Route::get('receivable-types/create', 'Settings@receivableTypesCreate')->name('receivable-types.create');
            Route::post('receivable-types/store', 'Settings@receivableTypesStore')->name('receivable-types.store');
            Route::get('receivable-types/{id}/edit', 'Settings@receivableTypesEdit')->name('receivable-types.edit');
            Route::patch('receivable-types/update', 'Settings@receivableTypesUpdate')->name('receivable-types.update');
        });
    });
    //RECEIVABLES
    Route::group(['prefix' => 'operations', 'as' => 'operations.'], function () {
        Route::group(['prefix' => 'receivables', 'as' => 'receivables.'], function () {
            Route::get('/', 'Receivables@index')->name('index');
        });
    });
    //LOANS
    Route::group(['prefix' => 'operations', 'as' => 'operations.'], function () {
        Route::group(['prefix' => 'loans', 'as' => 'loans.'], function () {
            Route::get('/', 'Loans@index')->name('index');
            Route::get('create', 'Loans@create')->name('create');
            Route::post('store', 'Loans@store')->name('store');
            Route::get('{id}/edit', 'Loans@edit')->name('edit');
            Route::patch('update', 'Loans@update')->name('update');
        });
    });
    //DROPS
    Route::group(['prefix' => 'operations', 'as' => 'operations.'], function () {
        Route::group(['prefix' => 'drops', 'as' => 'drops.'], function () {
            Route::get('/', 'Drops@index')->name('index');
        });
    });
});
