<?php
return [
    'adminEmail' => 'lightstudio.office@gmail.com',
    'liqpay' => [
        'publicKey' => 'i30988876794',
        'privateKey' => 'TWPXRXJTeJck8hUsg0dpCqzHwuA6bscoDDWI2gj0',
        'serverUrl' => 'https://api.lightstudio.ua/v1/payments/response',
        'resultUrl' => 'http://lightstudio.ua/thank',
        'sandbox' => 0,
        'paymentStatuses' => [
            'success' => 'success',
            'sandbox' => 'sandbox'
        ],
        'paymentCodes' => [
            'payed' => 5,
            'error' => 6,
        ],
    ],


];