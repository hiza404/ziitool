<?php

namespace Database\Seeders;

use App\Models\ProLicense;
use Illuminate\Database\Seeder;

class ProLicenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $licenses = [
            [
                'code' => 'PRO-SUPER-2026',
                'plan' => 'yearly',
                'is_active' => true,
                'customer_name' => 'Demo Khách Hàng VIP',
                'notes' => 'Mã khuyến mãi thử nghiệm hệ thống',
            ],
            [
                'code' => 'ZIITOOL-VIP',
                'plan' => 'lifetime',
                'is_active' => true,
                'customer_name' => 'ZiiTool Partner',
                'notes' => 'Tài khoản đối tác chiến lược',
            ],
            [
                'code' => 'ZIITOOL-PRO',
                'plan' => 'monthly',
                'is_active' => true,
                'customer_name' => 'Thành Viên Tiêu Chuẩn',
                'notes' => 'Mã dùng thử tháng đầu',
            ],
        ];

        foreach ($licenses as $lic) {
            ProLicense::updateOrCreate(['code' => $lic['code']], $lic);
        }
    }
}
