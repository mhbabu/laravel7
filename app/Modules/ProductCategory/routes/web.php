<?php

Route::group(['module' => 'ProductCategory', 'middleware' => ['web','auth'], 'namespace' => 'App\Modules\ProductCategory\Controllers'], function() {

    Route::get('product-categories/{id}/delete','ProductCategoryController@delete');
    Route::resource('product-categories', 'ProductCategoryController');

});
