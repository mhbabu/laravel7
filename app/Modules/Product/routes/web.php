<?php

Route::group(['module' => 'Product', 'middleware' => ['web','auth'], 'namespace' => 'App\Modules\Product\Controllers'], function() {

    Route::get('products/{id}/delete','ProductController@delete');
    Route::resource('products', 'ProductController');

});
