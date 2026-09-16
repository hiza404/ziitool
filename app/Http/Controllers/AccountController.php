<?php

namespace App\Http\Controllers;

use App\Models\ProLicense;
use App\Models\User;
use App\Services\SeoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AccountController extends Controller
{
    /**
     * Display the user account dashboard.
     */
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $orders = $user->orders()->latest()->get();
        $licenses = $user->proLicenses()->latest()->get();

        $seo = SeoService::getMetadata([
            'title' => 'Tài Khoản Của Tôi - Quản Lý Bản Quyền Pro',
            'description' => 'Quản lý thông tin tài khoản, gói Pro hội viên, mã bản quyền và lịch sử thanh toán VietQR.',
        ]);

        return view('pages.account', [
            'user' => $user,
            'orders' => $orders,
            'licenses' => $licenses,
            'seo' => $seo,
        ]);
    }

    /**
     * Redeem a Pro License Key into the authenticated user account.
     */
    public function redeemLicense(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'license_code' => ['required', 'string', 'max:50'],
        ]);

        /** @var User $user */
        $user = Auth::user();
        $code = strtoupper(trim($validated['license_code']));

        // 1. Check in database first
        $license = ProLicense::where('code', $code)->where('is_active', true)->first();

        if ($license) {
            $license->user_id = $user->id;
            $license->used_at = now();
            $license->customer_name = $user->name;
            $license->customer_email = $user->email;
            $license->save();

            $user->is_pro = true;
            $user->pro_plan = $license->plan ?: 'yearly';
            $user->pro_expires_at = $license->expires_at ?: now()->addYear();
            $user->save();

            session(['is_pro_member' => true]);

            return back()->with('success', "Kích hoạt mã {$code} thành công! Tài khoản của bạn đã được nâng cấp lên Gói Pro.");
        }

        // 2. Check static demo codes fallback
        $validCodes = config('ads.pro.demo_pro_codes', []);
        if (in_array($code, array_map('strtoupper', $validCodes), true)) {
            $newLicense = ProLicense::create([
                'user_id' => $user->id,
                'code' => $code,
                'plan' => 'yearly',
                'is_active' => true,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'used_at' => now(),
                'expires_at' => now()->addYear(),
                'notes' => 'Kích hoạt từ mã demo hệ thống',
            ]);

            $user->is_pro = true;
            $user->pro_plan = 'yearly';
            $user->pro_expires_at = now()->addYear();
            $user->save();

            session(['is_pro_member' => true]);

            return back()->with('success', "Kích hoạt mã dùng thử {$code} thành công! Bạn đã được kích hoạt 1 năm sử dụng Gói Pro.");
        }

        return back()->with('error', 'Mã bản quyền không hợp lệ hoặc đã được sử dụng. Vui lòng kiểm tra lại!');
    }
}
