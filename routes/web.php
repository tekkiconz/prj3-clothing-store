<?php

Route::get('/', 'Front\WebController@index');
Route::get('page/{slug}', 'Front\WebController@ShowPage');
Route::get('category-list', 'Front\WebController@categoryList');
Route::get('home-offers', 'Front\WebController@homeOffers');
Route::get('hot-deal', 'Front\WebController@hotDeal');
Route::get('offers', 'Offers\CampaignController@allOffer');
Route::get('offer/{id}/{name}', 'Offers\CampaignController@getOffer')->name('all-offer.product');

// product searching by keyword
Route::get('search-product', 'Front\WebController@searchProduct');


Route::post('user/subscribe', 'Front\WebController@subscribe');

Route::get('offered-product/{id}', 'Offers\CampaignController@getOfferProduct');

Route::get('product/category/{id}/{slug}', 'Front\WebController@categoryProduct')->name('category.product');

Route::get('category-product-list/{id}', 'Front\WebController@CategoryProductList');

// product details

// category product
Route::get('product/category/{id}/{slug?}', 'Front\WebController@categoryProduct')->name('category.product');

Route::get('category-product-list/{id}', 'Front\WebController@CategoryProductList');

// sub category product
Route::get('product/sub-category/{id}/{slug?}', 'Front\WebController@subCategory')->name('subcategory.product');

Route::get('sub-category-product-list/{id}', 'Front\WebController@subCategoryProductList');


// sub category product
Route::get('product/sub-sub-category/{id}/{slug?}', 'Front\WebController@subSubCategory')->name('subsubcategory.product');

Route::get('sub-sub-category-product-list/{id}', 'Front\WebController@subSubCategoryProductList');
Route::get('viewlist', 'Front\WebController@viewList');


// product details

Route::get('product/{id}/{slug}', 'Front\WebController@productDetails')->name('product.details');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Login with Social media
Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');
