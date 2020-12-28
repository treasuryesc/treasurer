<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'portal',
    'middleware' => 'portal',
    'namespace' => 'Modules\Operations\Http\Controllers'
], function () {
    // Route::get('invoices/{invoice}/operations', 'Main@show')->name('portal.invoices.operations.show');
    // Route::post('invoices/{invoice}/operations/confirm', 'Main@confirm')->name('portal.invoices.operations.confirm');
});
