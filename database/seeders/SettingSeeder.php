<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General & SEO
            'site_name' => ['value' => 'MicroTools Hub', 'group' => 'general'],
            'site_tagline' => ['value' => 'Công Cụ Tiện Ích Trực Tuyến Nhanh Chóng & Miễn Phí', 'group' => 'general'],
            'meta_description' => ['value' => 'Trọn bộ công cụ tiện ích trực tuyến tốt nhất: Chuyển đổi và nén ảnh WebP/PNG, Format JSON & SQL, tính thuế TNCN, tính lãi kép, tạo mã QR và Mockup thiết bị.', 'group' => 'general'],
            'contact_email' => ['value' => 'admin@microtools.com', 'group' => 'general'],
            'announcement_banner' => ['value' => '⚡ Chào mừng đến với MicroTools Hub - Nền tảng tiện ích 0đ, tự động hóa 100%!', 'group' => 'general'],

            // Google AdSense
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

        foreach ($settings as $key => $item) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $item['value'], 'group' => $item['group']]
            );
        }
    }
}
