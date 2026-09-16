<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProLicense;
use App\Models\Setting;
use App\Models\User;
use App\Services\SeoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
        if (! auth()->check()) {
            return response()->json([
                'success' => false,
                'require_auth' => true,
                'login_url' => route('login', ['redirect' => '/pricing']),
                'message' => 'Vui lòng đăng nhập tài khoản trước khi thanh toán để bảo lưu quyền lợi gói Pro vào tài khoản của bạn.',
            ], 401);
        }

        $user = auth()->user();
        $plan = $request->input('plan', 'monthly');

        $monthlyPrice = (int) Setting::get('price_monthly', config('ads.pro.price_monthly', 49000));
        $yearlyPrice = (int) Setting::get('price_yearly', config('ads.pro.price_yearly', 399000));
        $amount = $plan === 'yearly' ? $yearlyPrice : $monthlyPrice;

        $orderCode = 'PRO'.strtoupper(substr(uniqid(), -6));
        $memo = "Nang cap Pro $orderCode";

        $bankCode = Setting::get('vietqr_bank_code', config('ads.pro.bank_code', 'MB'));
        $accountNumber = Setting::get('vietqr_account_number', config('ads.pro.account_number', '0988888888'));
        $accountName = Setting::get('vietqr_account_name', config('ads.pro.account_name', 'MICROTOOLS HUB'));

        // Lưu đơn hàng gắn với user_id
        Order::create([
            'user_id' => $user->id,
            'order_code' => $orderCode,
            'plan' => $plan,
            'amount' => $amount,
            'bank_code' => $bankCode,
            'status' => 'pending',
            'customer_name' => $user->name,
            'customer_phone' => $user->email,
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
     * Confirm simulated order payment and activate Pro directly for user account.
     */
    public function confirmOrder(Request $request): JsonResponse
    {
        if (! auth()->check()) {
            return response()->json([
                'success' => false,
                'require_auth' => true,
                'message' => 'Vui lòng đăng nhập tài khoản.',
            ], 401);
        }

        /** @var User $user */
        $user = auth()->user();
        $orderCode = trim((string) $request->input('order_code', ''));

        $order = Order::where('order_code', $orderCode)
            ->where('user_id', $user->id)
            ->first();

        if (! $order) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin đơn hàng.',
            ], 404);
        }

        $order->status = 'paid';
        $order->save();

        $plan = $order->plan;
        $expiresAt = $plan === 'yearly' ? now()->addYear() : now()->addMonth();

        // Tạo mã License Pro cho tài khoản
        $licenseCode = 'PRO-'.strtoupper(Str::random(10));
        $license = ProLicense::create([
            'user_id' => $user->id,
            'code' => $licenseCode,
            'plan' => $plan,
            'is_active' => true,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'used_at' => now(),
            'expires_at' => $expiresAt,
            'notes' => "Đơn hàng thanh toán VietQR {$orderCode}",
        ]);

        $user->is_pro = true;
        $user->pro_plan = $plan;
        $user->pro_expires_at = $expiresAt;
        $user->save();

        session(['is_pro_member' => true, 'pro_license' => $licenseCode]);

        return response()->json([
            'success' => true,
            'message' => 'Chúc mừng! Đơn hàng đã được xác nhận thanh toán thành công.',
            'license_code' => $licenseCode,
            'plan' => $plan,
            'redirect_url' => route('account'),
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

            if (auth()->check()) {
                /** @var User $user */
                $user = auth()->user();
                $license->user_id = $user->id;
                $user->is_pro = true;
                $user->pro_plan = $license->plan ?: 'yearly';
                $user->pro_expires_at = $license->expires_at ?: now()->addYear();
                $user->save();
            }

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
            if (auth()->check()) {
                /** @var User $user */
                $user = auth()->user();
                $user->is_pro = true;
                $user->pro_plan = 'yearly';
                $user->pro_expires_at = now()->addYear();
                $user->save();
            }

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
