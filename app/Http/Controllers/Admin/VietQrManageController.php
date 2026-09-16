<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProLicense;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class VietQrManageController extends Controller
{
    /**
     * Display VietQR configuration, Orders and Pro Licenses.
     */
    public function index(): View
    {
        $vietqr = [
            'bank_code' => Setting::get('vietqr_bank_code', 'MB'),
            'account_number' => Setting::get('vietqr_account_number', '0988888888'),
            'account_name' => Setting::get('vietqr_account_name', 'ZIITOOL'),
            'price_monthly' => Setting::get('price_monthly', '49000'),
            'price_yearly' => Setting::get('price_yearly', '399000'),
        ];

        $orders = Order::orderBy('created_at', 'desc')->paginate(10, ['*'], 'orders_page');
        $licenses = ProLicense::orderBy('created_at', 'desc')->paginate(10, ['*'], 'licenses_page');

        return view('admin.vietqr.index', [
            'vietqr' => $vietqr,
            'orders' => $orders,
            'licenses' => $licenses,
        ]);
    }

    /**
     * Update Bank Account information for VietQR.
     */
    public function updateBank(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bank_code' => ['required', 'string'],
            'account_number' => ['required', 'string'],
            'account_name' => ['required', 'string'],
        ]);

        Setting::set('vietqr_bank_code', $validated['bank_code'], 'vietqr');
        Setting::set('vietqr_account_number', $validated['account_number'], 'vietqr');
        Setting::set('vietqr_account_name', strtoupper($validated['account_name']), 'vietqr');

        return back()->with('success', 'Đã cập nhật thông tin tài khoản ngân hàng VietQR!');
    }

    /**
     * Update Pro plan pricing.
     */
    public function updatePrice(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'price_monthly' => ['required', 'numeric', 'min:1000'],
            'price_yearly' => ['required', 'numeric', 'min:1000'],
        ]);

        Setting::set('price_monthly', $validated['price_monthly'], 'vietqr');
        Setting::set('price_yearly', $validated['price_yearly'], 'vietqr');

        return back()->with('success', 'Đã cập nhật bảng giá gói Pro thành công!');
    }

    /**
     * Generate a new Pro License Key.
     */
    public function generateLicense(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'custom_code' => ['nullable', 'string', 'unique:pro_licenses,code'],
            'plan' => ['required', 'string', 'in:monthly,yearly,lifetime'],
            'customer_name' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $code = ! empty($validated['custom_code'])
            ? strtoupper(trim($validated['custom_code']))
            : 'PRO-'.strtoupper(Str::random(4)).'-'.strtoupper(Str::random(4));

        ProLicense::create([
            'code' => $code,
            'plan' => $validated['plan'],
            'is_active' => true,
            'customer_name' => $validated['customer_name'] ?? 'Khách Hàng Pro',
            'notes' => $validated['notes'] ?? '',
        ]);

        return back()->with('success', "Đã tạo thành công mã License Key: {$code}");
    }

    /**
     * Toggle active status of a License Key.
     */
    public function toggleLicense(Request $request, int $id): JsonResponse
    {
        $license = ProLicense::findOrFail($id);
        $license->is_active = ! $license->is_active;
        $license->save();

        $status = $license->is_active ? 'Kích hoạt' : 'Tạm khóa';

        return response()->json([
            'success' => true,
            'is_active' => $license->is_active,
            'message' => "{$status} mã bản quyền {$license->code} thành công!",
        ]);
    }

    /**
     * Update payment status of an Order.
     */
    public function updateOrderStatus(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,paid,cancelled'],
        ]);

        $order = Order::findOrFail($id);
        $order->status = $validated['status'];
        $order->save();

        return back()->with('success', "Đã cập nhật trạng thái đơn hàng {$order->order_code} thành {$order->status}!");
    }
}
