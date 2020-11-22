<?php

Route::group(['module' => 'User', 'middleware' => ['web','auth'], 'namespace' => 'App\Modules\User\Controllers'], function() {

    /*
     * User Management Routes
     */
    Route::get('users/approve/{userId}','UserController@approveUser')->name('users.approve');
    Route::get('users/activate/{userId}','UserController@activateUser')->name('users.activate');
    Route::get('users/deactivate/{userId}','UserController@deactivateUser')->name('users.deactivate');
    Route::resource('users', 'UserController', ['names' => [
        'index'     => 'users.index',
        'create'    => 'users.create',
        'edit'      => 'users.edit',
        'store'     => 'users.store',
        'update'    => 'users.update',
        'show'      => 'users.show'
    ]]);

});
