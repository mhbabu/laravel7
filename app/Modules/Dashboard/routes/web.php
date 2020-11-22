<?php

Route::group(['module' => 'Dashboard', 'middleware' => ['web','auth'], 'namespace' => 'App\Modules\Dashboard\Controllers'], function() {

    Route::resource('dashboard', 'DashboardController');

});
