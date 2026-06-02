<?php

return [
    'currency' => env('FEE_CURRENCY', 'INR'),

    'billing_cycles' => [
        'monthly',
        'quarterly',
        'term',
        'one_time',
        'custom',
    ],

    'late_fee' => [
        // flat | daily | percentage
        'mode' => env('FEE_LATE_FEE_MODE', 'flat'),
        'flat_amount' => (float) env('FEE_LATE_FEE_FLAT', 0),
        'daily_amount' => (float) env('FEE_LATE_FEE_DAILY', 0),
        'percentage' => (float) env('FEE_LATE_FEE_PERCENT', 0),
    ],

    'dynamic_gateways' => env('FEE_DYNAMIC_GATEWAYS', true),

    'default_gateway' => env('FEE_DEFAULT_GATEWAY', 'cash'),

    'gateways' => [
        'cash' => [],

        'bank_transfer' => [
            'instructions' => env('BANK_TRANSFER_INSTRUCTIONS', ''),
        ],

        'upi' => [
            'upi_id' => env('UPI_ID', ''),
        ],

        'razorpay' => [
            'key_id' => env('RAZORPAY_KEY_ID', ''),
            'key_secret' => env('RAZORPAY_KEY_SECRET', ''),
            'webhook_secret' => env('RAZORPAY_WEBHOOK_SECRET', ''),
        ],

        'stripe' => [
            'publishable_key' => env('STRIPE_PUBLISHABLE_KEY', ''),
            'secret_key' => env('STRIPE_SECRET_KEY', ''),
            'webhook_secret' => env('STRIPE_WEBHOOK_SECRET', ''),
        ],
    ],

    'payment_methods' => [
        'cash',
        'bank_transfer',
        'card_pos',
        'upi',
        'razorpay',
        'stripe',
    ],
];

