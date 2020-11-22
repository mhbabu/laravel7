<?php

Route::group(['module' => 'Purchase', 'middleware' => ['web','auth'], 'namespace' => 'App\Modules\Purchase\Controllers'], function() {

    /*
    * Premium Model Test Routes
    */
    Route::get('exam/live-exams/{id}/add-question','LiveExamController@addQuestion');
    Route::post('products/auto-suggest', 'PurchaseController@productAutoSuggest');
    Route::post('products/add-cart', 'PurchaseController@productAddCart')->name('product.add-cart');
    Route::post('products/add-cart/delete', 'PurchaseController@addCartProductDelete')->name('product.add-cart.delete');
    Route::post('exam/live-exams/questions/store', 'LiveExamController@storeAddQuestion')->name('exam.live-exam.questions.store');

    /*
    * Purchase Products Routes
    */
    Route::get('purchases/{id}/delete','PurchaseController@delete');
    Route::resource('purchases', 'PurchaseController');

});
