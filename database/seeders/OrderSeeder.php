<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
    }
}
