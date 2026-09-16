@props(['slot' => 'in_tool', 'class' => '', 'size' => 'auto'])

@php
    $adsEnabled = \App\Models\Setting::get('ads_enabled', '1') === '1';
    $demoMode = \App\Models\Setting::get('ads_demo_mode', '1') === '1';
    $clientId = \App\Models\Setting::get('adsense_client_id', config('ads.client_id'));
    $slotId = \App\Models\Setting::get('ads_slot_' . $slot, config('ads.slots.' . $slot, '1234567890'));
    $isPro = session('is_pro_member', false) || (auth()->check() && auth()->user()->isPro());
    $priceMonthly = (int) \App\Models\Setting::get('price_monthly', config('ads.pro.price_monthly', 49000));
    $monthlyLabel = ($priceMonthly >= 1000) ? number_format($priceMonthly / 1000, 0, ',', '.') . 'k' : number_format($priceMonthly, 0, ',', '.') . 'đ';
@endphp

@if($adsEnabled && !$isPro)
<div class="ad-container my-4 text-center overflow-hidden transition-all duration-300 {{ $class }}" data-ad-slot="{{ $slot }}">
    @if($demoMode)
        <div class="relative group mx-auto p-4 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 bg-gradient-to-r from-slate-50 via-slate-100 to-slate-50 dark:from-slate-800/40 dark:via-slate-800/80 dark:to-slate-800/40 transition-all hover:border-indigo-400 dark:hover:border-indigo-500">
            <div class="flex items-center justify-between text-xs text-slate-400 dark:text-slate-500 mb-2 px-1">
                <span class="flex items-center gap-1">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Quảng cáo được tài trợ / Sponsored</span>
                </span>
                <a href="{{ route('pricing') }}" class="hover:text-indigo-500 underline transition-colors">Tắt quảng cáo này</a>
            </div>

            @if($slot === 'top_leaderboard')
                <div class="py-3 flex flex-col md:flex-row items-center justify-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-indigo-600/10 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-lg">
                        ⚡
                    </div>
                    <div class="text-left">
                        <div class="text-sm font-semibold text-slate-800 dark:text-slate-200">VPS Cloud Tốc Độ Cao - Chi Phí Cực Thấp</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">Khởi tạo máy chủ trong 30 giây. Miễn phí băng thông không giới hạn.</div>
                    </div>
                    <a href="{{ route('pricing') }}" class="mt-2 md:mt-0 px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-lg transition-colors shadow-sm">
                        Tìm hiểu ngay →
                    </a>
                </div>
            @elseif($slot === 'sidebar')
                <div class="py-4 px-2">
                    <div class="w-12 h-12 mx-auto mb-2 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 text-white flex items-center justify-center text-xl shadow-md">
                        ⭐
                    </div>
                    <div class="text-sm font-bold text-slate-800 dark:text-slate-100">Gói Pro Không Giới Hạn</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1 mb-3">Tắt 100% quảng cáo, tải ảnh dung lượng lớn, API cho developer.</div>
                    <a href="{{ route('pricing') }}" class="inline-block w-full py-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white text-xs font-semibold rounded-lg shadow transition">
                        Nâng Cấp {{ $monthlyLabel }}/tháng
                    </a>
                </div>
            @else
                <div class="py-3 flex items-center justify-between gap-4 px-2">
                    <div class="text-left">
                        <span class="inline-block px-2 py-0.5 text-[10px] font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300 rounded mr-2">Google AdSense Demo</span>
                        <span class="text-xs text-slate-600 dark:text-slate-300">Vị trí quảng cáo tối ưu CTR trong công cụ.</span>
                    </div>
                    <a href="{{ route('pricing') }}" class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                        Đăng ký Pro →
                    </a>
                </div>
            @endif
        </div>
    @else
        <!-- Google AdSense Live Script -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="{{ $clientId }}"
             data-ad-slot="{{ $slotId }}"
             data-ad-format="{{ $size }}"
             data-full-width-responsive="true"></ins>
        <script>
             (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    @endif
</div>
@endif

