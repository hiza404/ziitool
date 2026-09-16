<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdSenseController extends Controller
{
    /**
     * Display AdSense and Monetization Management.
     */
    public function index(): View
    {
        $settings = [
            'ads_enabled' => Setting::get('ads_enabled', '1') === '1',
            'ads_demo_mode' => Setting::get('ads_demo_mode', '1') === '1',
            'adsense_client_id' => Setting::get('adsense_client_id', 'ca-pub-9988776655443322'),
            'ads_slot_top' => Setting::get('ads_slot_top', '1234567890'),
            'ads_slot_in_tool' => Setting::get('ads_slot_in_tool', '2345678901'),
            'ads_slot_sidebar' => Setting::get('ads_slot_sidebar', '3456789012'),
            'ads_slot_sticky' => Setting::get('ads_slot_sticky', '4567890123'),
            'announcement_banner' => Setting::get('announcement_banner', ''),
        ];

        return view('admin.adsense.index', [
            'settings' => $settings,
        ]);
    }

    /**
     * Update AdSense settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'adsense_client_id' => ['nullable', 'string'],
            'ads_slot_top' => ['nullable', 'string'],
            'ads_slot_in_tool' => ['nullable', 'string'],
            'ads_slot_sidebar' => ['nullable', 'string'],
            'ads_slot_sticky' => ['nullable', 'string'],
            'announcement_banner' => ['nullable', 'string'],
        ]);

        Setting::set('ads_enabled', $request->has('ads_enabled') ? '1' : '0', 'adsense');
        Setting::set('ads_demo_mode', $request->has('ads_demo_mode') ? '1' : '0', 'adsense');
        Setting::set('adsense_client_id', $validated['adsense_client_id'] ?? '', 'adsense');
        Setting::set('ads_slot_top', $validated['ads_slot_top'] ?? '', 'adsense');
        Setting::set('ads_slot_in_tool', $validated['ads_slot_in_tool'] ?? '', 'adsense');
        Setting::set('ads_slot_sidebar', $validated['ads_slot_sidebar'] ?? '', 'adsense');
        Setting::set('ads_slot_sticky', $validated['ads_slot_sticky'] ?? '', 'adsense');
        Setting::set('announcement_banner', $validated['announcement_banner'] ?? '', 'general');

        return back()->with('success', 'Đã cập nhật cấu hình Google AdSense thành công!');
    }
}
