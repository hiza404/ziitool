<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\ProLicense;
use App\Models\Setting;
use App\Models\ToolOverride;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Default Admin Account
        User::updateOrCreate(
            ['email' => 'admin@microtools.com'],
            [
                'name' => 'Quản Trị Viên',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
            ]
        );

        // 2. Default System Settings
        $defaultSettings = [
            // General & SEO
            'site_name' => ['value' => 'MicroTools Hub', 'group' => 'general'],
            'site_tagline' => ['value' => 'Công Cụ Tiện Ích Trực Tuyến Nhanh Chóng & Miễn Phí', 'group' => 'general'],
            'meta_description' => ['value' => 'Trọn bộ công cụ tiện ích trực tuyến tốt nhất: Chuyển đổi và nén ảnh WebP/PNG, Format JSON & SQL, tính thuế TNCN, tính lãi kép, tạo mã QR và Mockup thiết bị.', 'group' => 'general'],
            'contact_email' => ['value' => 'admin@microtools.com', 'group' => 'general'],
            'announcement_banner' => ['value' => '⚡ Chào mừng đến với MicroTools Hub - Nền tảng tiện ích 0đ, tự động hóa 100%!', 'group' => 'general'],

            // AdSense Settings
            'ads_enabled' => ['value' => '1', 'group' => 'adsense'],
            'ads_demo_mode' => ['value' => '1', 'group' => 'adsense'],
            'adsense_client_id' => ['value' => 'ca-pub-9988776655443322', 'group' => 'adsense'],
            'ads_slot_top' => ['value' => '1234567890', 'group' => 'adsense'],
            'ads_slot_in_tool' => ['value' => '2345678901', 'group' => 'adsense'],
            'ads_slot_sidebar' => ['value' => '3456789012', 'group' => 'adsense'],
            'ads_slot_sticky' => ['value' => '4567890123', 'group' => 'adsense'],

            // VietQR & Pricing
            'vietqr_bank_code' => ['value' => 'MB', 'group' => 'vietqr'],
            'vietqr_account_number' => ['value' => '0988888888', 'group' => 'vietqr'],
            'vietqr_account_name' => ['value' => 'MICROTOOLS HUB', 'group' => 'vietqr'],
            'price_monthly' => ['value' => '49000', 'group' => 'vietqr'],
            'price_yearly' => ['value' => '399000', 'group' => 'vietqr'],
        ];

        foreach ($defaultSettings as $key => $item) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $item['value'], 'group' => $item['group']]
            );
        }

        // 3. Default Pro Licenses
        $licenses = [
            [
                'code' => 'PRO-SUPER-2026',
                'plan' => 'yearly',
                'is_active' => true,
                'customer_name' => 'Demo Khách Hàng VIP',
                'notes' => 'Mã khuyến mãi thử nghiệm hệ thống',
            ],
            [
                'code' => 'VINICORP-VIP',
                'plan' => 'lifetime',
                'is_active' => true,
                'customer_name' => 'Vinicorp Partner',
                'notes' => 'Tài khoản đối tác chiến lược',
            ],
            [
                'code' => 'MICROTOOLS-PRO',
                'plan' => 'monthly',
                'is_active' => true,
                'customer_name' => 'Thành Viên Tiêu Chuẩn',
                'notes' => 'Mã dùng thử tháng đầu',
            ],
        ];

        foreach ($licenses as $lic) {
            ProLicense::updateOrCreate(['code' => $lic['code']], $lic);
        }

        // 4. Sample Orders
        $orders = [
            [
                'order_code' => 'PRO882190',
                'plan' => 'yearly',
                'amount' => 399000,
                'bank_code' => 'MB',
                'status' => 'paid',
                'customer_name' => 'Trần Văn Nam',
                'customer_phone' => '0912345678',
            ],
            [
                'order_code' => 'PRO773210',
                'plan' => 'monthly',
                'amount' => 49000,
                'bank_code' => 'VCB',
                'status' => 'paid',
                'customer_name' => 'Lê Thị Thu',
                'customer_phone' => '0988776655',
            ],
            [
                'order_code' => 'PRO991442',
                'plan' => 'monthly',
                'amount' => 49000,
                'bank_code' => 'MB',
                'status' => 'pending',
                'customer_name' => 'Hoàng Minh Tuấn',
                'customer_phone' => '0901239876',
            ],
        ];

        foreach ($orders as $ord) {
            Order::updateOrCreate(['order_code' => $ord['order_code']], $ord);
        }

        // 5. Initialize Tool Overrides
        $allTools = config('tools.list', []);
        foreach ($allTools as $slug => $tool) {
            ToolOverride::updateOrCreate(
                ['slug' => $slug],
                [
                    'is_active' => true,
                    'custom_title' => $tool['title'],
                    'custom_badge' => $tool['badge'],
                    'custom_desc' => $tool['short_desc'],
                ]
            );
        }
    }
}
