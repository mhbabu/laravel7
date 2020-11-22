<?php

Route::group(['module' => 'Purchase', 'middleware' => ['api'], 'namespace' => 'App\Modules\Purchase\Controllers'], function() {

    Route::resource('Purchase', 'PurchaseController');

});
