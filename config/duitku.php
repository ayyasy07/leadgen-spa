<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Duitku Payment Gateway Configuration
    |--------------------------------------------------------------------------
    */

    'merchant_code' => env('DUITKU_MERCHANT_CODE'),
    'api_key'       => env('DUITKU_API_KEY'),

    // true = sandbox, false = production
    'sandbox'       => env('DUITKU_SANDBOX', true),

    // URL callback dari Duitku (POST) — harus accessible dari internet (tunnel)
    'callback_url'  => env('DUITKU_CALLBACK_URL'),

    // URL redirect setelah user selesai bayar (browser customer)
    'return_url'    => env('DUITKU_RETURN_URL', env('APP_URL') . '/booking/success'),

    // Expiry period transaksi Duitku (menit)
    'expiry_period' => (int) env('DUITKU_EXPIRY_PERIOD', 60),

    /*
    |--------------------------------------------------------------------------
    | Endpoints — Sandbox vs Production
    |--------------------------------------------------------------------------
    */
    'endpoints' => [
        'sandbox' => [
            'inquiry'        => 'https://sandbox.duitku.com/webapi/api/merchant/v2/inquiry',
            'payment_method' => 'https://sandbox.duitku.com/webapi/api/merchant/paymentmethod/getpaymentmethod',
            'status'         => 'https://sandbox.duitku.com/webapi/api/merchant/transactionStatus',
        ],
        'production' => [
            'inquiry'        => 'https://passport.duitku.com/webapi/api/merchant/v2/inquiry',
            'payment_method' => 'https://passport.duitku.com/webapi/api/merchant/paymentmethod/getpaymentmethod',
            'status'         => 'https://passport.duitku.com/webapi/api/merchant/transactionStatus',
        ],
    ],

];