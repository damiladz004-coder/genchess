<?php

return [
    'public_key' => env('PAYSTACK_PUBLIC_KEY'),
    'secret_key' => env('PAYSTACK_SECRET_KEY'),
    'payment_url' => env('PAYSTACK_PAYMENT_URL', 'https://api.paystack.co'),
    'merchant_email' => env('PAYSTACK_MERCHANT_EMAIL'),
    'fees' => [
        'training' => (int) env('PAYSTACK_TRAINING_FEE', 0),
        'school' => (int) env('PAYSTACK_SCHOOL_FEE', 0),
        'consultation' => (int) env('PAYSTACK_CONSULTATION_FEE', 0),
        'tournament' => (int) env('PAYSTACK_TOURNAMENT_FEE', 0),
    ],
];
