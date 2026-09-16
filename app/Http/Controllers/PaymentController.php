<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProLicense;
use App\Models\Setting;
use App\Services\SeoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Display the Pricing & Pro Upgrade Page.
     */
    public function pricing(): View
    {
        $proConfig = [
            'price_monthly' => (int) Setting::get('price_monthly', config('ads.pro.price_monthly', 49000)),
            'price_yearly' => (int) Setting::get('price_yearly', config('ads.pro.price_yearly', 399000)),
            'bank_code' => Setting::get('vietqr_bank_code', config('ads.pro.bank_code', 'MB')),
            'account_number' => Setting::get('vietqr_account_number', config('ads.pro.account_number', '0988888888')),
            'account_name' => Setting::get('vietqr_account_name', config('ads.pro.account_name', 'MICROTOOLS HUB')),
        ];

        $seo = SeoService::getMetadata([
            'title' => 'Nâng Cấp Gói Pro - Không Quảng Cáo, Tốc Độ Tối Đa',
            'description' => 'Mở khóa toàn bộ tính năng Pro: Tắt 100% quảng cáo, xử lý tệp tin không giới hạn dung lượng, cấp quyền truy cập Developer REST API.',
        ]);

        return view('pages.pricing', [
            'pro' => $proConfig,
            'seo' => $seo,
        ]);
    }

    /**
     * Generate VietQR Napas247 payment URL and record Order.
     */
    public function generateVietQr(Request $request): JsonResponse
    {
        $plan = $request->input('plan', 'monthly');

        $monthlyPrice = (int) Setting::get('price_monthly', config('ads.pro.price_monthly', 49000));
        $yearlyPrice = (int) Setting::get('price_yearly', config('ads.pro.price_yearly', 399000));
        $amount = $plan === 'yearly' ? $yearlyPrice : $monthlyPrice;

        $orderCode = 'PRO'.strtoupper(substr(uniqid(), -6));
        $memo = "Nang cap Pro $orderCode";

        $bankCode = Setting::get('vietqr_bank_code', config('ads.pro.bank_code', 'MB'));
        $accountNumber = Setting::get('vietqr_account_number', config('ads.pro.account_number', '0988888888'));
        $accountName = Setting::get('vietqr_account_name', config('ads.pro.account_name', 'MICROTOOLS HUB'));

        // Lưu đơn hàng vào database
        Order::create([
            'order_code' => $orderCode,
            'plan' => $plan,
            'amount' => $amount,
            'bank_code' => $bankCode,
            'status' => 'pending',
            'customer_name' => $request->input('name', 'Khách hàng ẩn danh'),
            'customer_phone' => $request->input('phone', ''),
        ]);

        // Format chuẩn ảnh VietQR Napas247
        $vietQrUrl = sprintf(
            'https://img.vietqr.io/image/%s-%s-compact2.png?amount=%d&addInfo=%s&accountName=%s',
            $bankCode,
            $accountNumber,
            $amount,
            urlencode($memo),
            urlencode($accountName)
        );

        return response()->json([
            'success' => true,
            'order_code' => $orderCode,
            'amount' => $amount,
            'formatted_amount' => number_format($amount, 0, ',', '.').' VNĐ',
            'bank_code' => $bankCode,
            'account_number' => $accountNumber,
            'account_name' => $accountName,
            'memo' => $memo,
            'qr_image_url' => $vietQrUrl,
        ]);
    }

    /**
     * Verify Pro License Key from Database or config fallback.
     */
    public function verifyProCode(Request $request): JsonResponse
    {
        $code = strtoupper(trim((string) $request->input('license_code', '')));

        // Check in database first
        $license = ProLicense::where('code', $code)->where('is_active', true)->first();

        if ($license) {
            $license->used_at = now();
            $license->save();

            session(['is_pro_member' => true, 'pro_license' => $code]);

            return response()->json([
                'success' => true,
                'message' => 'Chúc mừng! Bạn đã kích hoạt thành công tài khoản Pro.',
                'is_pro' => true,
            ]);
        }

        // Fallback to static demo codes
        $validCodes = config('ads.pro.demo_pro_codes', []);
        if (in_array($code, array_map('strtoupper', $validCodes), true)) {
            session(['is_pro_member' => true, 'pro_license' => $code]);

            return response()->json([
                'success' => true,
                'message' => 'Chúc mừng! Bạn đã kích hoạt thành công tài khoản Pro.',
                'is_pro' => true,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Mã License không hợp lệ hoặc đã hết hạn. Hãy thử mã dùng thử: PRO-SUPER-2026',
        ], 422);
    }
}
