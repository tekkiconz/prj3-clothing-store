<?php

// payment related all route
Route::group(['middleware' => 'auth'], function () {

    Route::get('ssl-commerz/{order_id}',
        [
            'as' => 'payment.ssl',
            'uses' => 'Payment\PaymentController@sslCommerz'
        ]);

});


// getting available payment method form database

Route::get('payment-method-list', 'Setting\PaymentSettingController@frontMethodList');

// ssl call back
Route::post('ssl/success',
    [
        'as' => 'ssl.success',
        'uses' => 'Payment\PaymentController@sslCommerzSuccess'
    ]);

Route::post('ssl/failed',
    [
        'as' => 'ssl.failed',
        'uses' => 'Payment\PaymentController@sslCommerzFailed'
    ]
);

Route::post('ssl/cancel',
    [
        'as' => 'ssl.cancel',
        'uses' => 'Payment\PaymentController@sslCommerzCancel'
    ]
);

// route for the paypal
Route::get('paypal/{order_id}', [
    'as' => 'paypal', 'uses' => 'Payment\PaypalController@payWithpaypal'
]);
// paypal payment status
Route::get('paypal/status/{order_id}', 'Payment\PaypalController@getPaymentStatus')
    ->name('paypal.status');

// route for the stripe
Route::get('stripe/{order_id}', [
    'as' => 'addmoney.stripe',
    'uses' => 'Payment\StripeController@postPaymentStripe'
]);

// route for order complete

Route::get('order-completed',
    [
        'as' => 'order.completed',
        'uses' => 'Order\OrderController@orderSuccess'
    ]);
