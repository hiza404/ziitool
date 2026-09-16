<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProLicense;
use App\Models\Setting;
use App\Models\ToolOverride;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Admin Dashboard.
     */
    public function index(): View
    {
        $allTools = config('tools.list', []);
        $totalToolsCount = count($allTools);

        $overrides = ToolOverride::all()->keyBy('slug');
        $activeToolsCount = 0;
        foreach ($allTools as $slug => $tool) {
            $isActive = isset($overrides[$slug]) ? $overrides[$slug]->is_active : true;
            if ($isActive) {
                $activeToolsCount++;
            }
        }

        $totalRevenue = Order::where('status', 'paid')->sum('amount');
        $totalOrdersCount = Order::count();
        $paidOrdersCount = Order::where('status', 'paid')->count();
        $activeLicensesCount = ProLicense::where('is_active', true)->count();

        $adsEnabled = Setting::get('ads_enabled', '1') === '1';
        $adsDemoMode = Setting::get('ads_demo_mode', '1') === '1';
        $adsenseClientId = Setting::get('adsense_client_id', 'ca-pub-9988776655443322');

        $latestOrders = Order::orderBy('created_at', 'desc')->take(6)->get();
        $latestLicenses = ProLicense::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', [
            'totalToolsCount' => $totalToolsCount,
            'activeToolsCount' => $activeToolsCount,
            'totalRevenue' => $totalRevenue,
            'formattedRevenue' => number_format($totalRevenue, 0, ',', '.').' đ',
            'totalOrdersCount' => $totalOrdersCount,
            'paidOrdersCount' => $paidOrdersCount,
            'activeLicensesCount' => $activeLicensesCount,
            'adsEnabled' => $adsEnabled,
            'adsDemoMode' => $adsDemoMode,
            'adsenseClientId' => $adsenseClientId,
            'latestOrders' => $latestOrders,
            'latestLicenses' => $latestLicenses,
        ]);
    }
}
