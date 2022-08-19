<?php

// this file will contain all offer related routes

Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin', 'permission']], function () {

    Route::resource('offer', 'Offers\CampaignController');
    Route::get('offer-list', 'Offers\CampaignController@offerList');
    Route::get('offer/{id}/delete', 'Offers\CampaignController@destroy');
    Route::post('offer/{id}/update', 'Offers\CampaignController@update');
    Route::get('offer/product-list/search', 'Offers\CampaignController@productList');
    Route::get('offer/status/{id}', 'Offers\CampaignController@offerStatus');

});
