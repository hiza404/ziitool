<?php

namespace App\Http\Controllers;

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
        $adsConfig = config('ads.pro');
        $seo = SeoService::getMetadata([
            'title' => 'Nâng Cấp Gói Pro - Không Quảng Cáo, Tốc Độ Tối Đa',
            'description' => 'Mở khóa toàn bộ tính năng Pro: Tắt 100% quảng cáo, xử lý tệp tin không giới hạn dung lượng, cấp quyền truy cập Developer REST API.',
        ]);

        return view('pages.pricing', [
            'pro' => $adsConfig,
            'seo' => $seo,
        ]);
    }

    /**
     * Generate VietQR Napas247 payment URL.
     */
    public function generateVietQr(Request $request): JsonResponse
    {
        $plan = $request->input('plan', 'monthly');
        $proConfig = config('ads.pro');

        $amount = $plan === 'yearly' ? $proConfig['price_yearly'] : $proConfig['price_monthly'];
        $orderCode = 'PRO'.strtoupper(substr(uniqid(), -6));
        $memo = "Nang cap Pro $orderCode";

        $bankCode = $proConfig['bank_code'] ?? 'MB';
        $accountNumber = $proConfig['account_number'] ?? '0988888888';
        $accountName = $proConfig['account_name'] ?? 'MICROTOOLS';

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
     * Verify Pro License Key.
     */
    public function verifyProCode(Request $request): JsonResponse
    {
        $code = trim((string) $request->input('license_code', ''));
        $validCodes = config('ads.pro.demo_pro_codes', []);

        if (in_array(strtoupper($code), array_map('strtoupper', $validCodes), true)) {
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
