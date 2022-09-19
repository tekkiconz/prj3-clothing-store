<?php

// cart related all route will go there

Route::post('add-to-cart', 'Cart\CartController@store');
Route::get('cart-items', 'Cart\CartController@CartItem');
Route::get('cart/remove/{id}', 'Cart\CartController@destroy');
Route::get('cart/update/{id}/{status}', 'Cart\CartController@update');
Route::get('get-shipping', 'Cart\CartController@shippingAmount');

// will get after login
Route::group(['middleware' => 'auth'], function () {

    Route::get('checkout', ['as' => 'checkout.get', 'uses' => 'Cart\CartController@checkOutPage']);
    Route::post('checkout', ['as' => 'checkout.store', 'uses' => 'Cart\CartController@checkoutStore']);

});
