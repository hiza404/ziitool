<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Display general & SEO system settings.
     */
    public function index(): View
    {
        $settings = [
            'site_name' => Setting::get('site_name', 'MicroTools Hub'),
            'site_tagline' => Setting::get('site_tagline', 'Công Cụ Tiện Ích Trực Tuyến Nhanh Chóng & Miễn Phí'),
            'meta_description' => Setting::get('meta_description', 'Trọn bộ công cụ tiện ích trực tuyến tốt nhất: Chuyển đổi và nén ảnh WebP/PNG, Format JSON & SQL, tính thuế TNCN, tính lãi kép, tạo mã QR và Mockup thiết bị.'),
            'contact_email' => Setting::get('contact_email', 'admin@microtools.com'),
        ];

        return view('admin.settings.index', [
            'settings' => $settings,
        ]);
    }

    /**
     * Update general settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:100'],
            'site_tagline' => ['nullable', 'string', 'max:200'],
            'meta_description' => ['nullable', 'string', 'max:350'],
            'contact_email' => ['nullable', 'email'],
        ]);

        foreach ($validated as $key => $val) {
            Setting::set($key, $val, 'general');
        }

        return back()->with('success', 'Đã lưu cấu hình hệ thống & SEO thành công!');
    }

    /**
     * Clear application cache, views, and routes.
     */
    public function clearCache(): RedirectResponse
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        return back()->with('success', 'Đã dọn dẹp sạch toàn bộ bộ nhớ đệm (Cache, Views, Routes)!');
    }
}
