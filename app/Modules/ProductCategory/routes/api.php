<?php

Route::group(['module' => 'ProductCategory', 'middleware' => ['api'], 'namespace' => 'App\Modules\ProductCategory\Controllers'], function() {

    Route::resource('ProductCategory', 'ProductCategoryController');

});
