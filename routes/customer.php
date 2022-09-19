<?php

// all order related route are here
Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin', 'permission']], function () {

    Route::resource('customer', 'Customer\CustomerController');

    Route::get('customer-list', 'Customer\CustomerController@getCustomer');
    Route::get('customer/status/{id}', 'Customer\CustomerController@changeStatus');
    Route::get('customer/{id}/show', 'Customer\CustomerController@customerOrder');
    Route::get('customer/orderdetails/{id}/show', 'Customer\CustomerController@customerOrderDetails');
});
