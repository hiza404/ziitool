<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google AdSense & Monetization Settings
    |--------------------------------------------------------------------------
    |
    | Cấu hình quảng cáo Google AdSense, vị trí hiển thị banner và chế độ Pro
    |
    */

    'enabled' => env('ADS_ENABLED', true),

    // Chế độ demo banner hiển thị mẫu giao diện quảng cáo CTR cao khi chưa gắn ID thật
    'demo_mode' => env('ADS_DEMO_MODE', true),

    'client_id' => env('ADSENSE_CLIENT_ID', 'ca-pub-9988776655443322'),

    'slots' => [
        'top_leaderboard' => env('ADS_SLOT_TOP', '1234567890'),
        'in_tool' => env('ADS_SLOT_IN_TOOL', '2345678901'),
        'sidebar' => env('ADS_SLOT_SIDEBAR', '3456789012'),
        'bottom_sticky' => env('ADS_SLOT_STICKY', '4567890123'),
    ],

    /*
    |--------------------------------------------------------------------------
    | VietQR & Pro Plan Settings
    |--------------------------------------------------------------------------
    */
    'pro' => [
        'price_monthly' => 49000, // 49.000 VNĐ / tháng
        'price_yearly' => 399000, // 399.000 VNĐ / năm (tiết kiệm 30%)
        'bank_code' => env('VIETQR_BANK_CODE', 'MB'), // MBBank, VCB, ACB, TPB, v.v.
        'account_number' => env('VIETQR_ACCOUNT_NUMBER', '0988888888'),
        'account_name' => env('VIETQR_ACCOUNT_NAME', 'ZIITOOL'),
        'demo_pro_codes' => [
            'PRO-SUPER-2026',
            'ZIITOOL-VIP',
            'ZIITOOL-PRO',
        ],
    ],
];
