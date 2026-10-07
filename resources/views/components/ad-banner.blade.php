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
                    <span>{{ __('Thông tin tài trợ / Sponsored') }}</span>
                </span>
                <span class="text-emerald-600 dark:text-emerald-400 font-medium">{{ __('100% Miễn phí') }}</span>
            </div>

            @if($slot === 'top_leaderboard')
                <div class="py-3 flex flex-col md:flex-row items-center justify-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-indigo-600/10 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-lg">
                        ⚡
                    </div>
                    <div class="text-left">
                        <div class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ __('ZiiTool - Bộ công cụ vi mô trực tuyến siêu tốc') }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ __('100% Client-Side. Không cần đăng nhập, không giới hạn lượt dùng, bảo mật tuyệt đối.') }}</div>
                    </div>
                    <a href="{{ route('home') }}" class="mt-2 md:mt-0 px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-lg transition-colors shadow-sm">
                        {{ __('Khám phá công cụ') }} →
                    </a>
                </div>
            @elseif($slot === 'bottom_sticky')
                <div id="stickyAdBanner" class="py-2 px-3 flex items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-2.5 text-left">
                        <span class="px-2 py-0.5 rounded bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 font-bold text-[10px]">SPONSORED</span>
                        <span class="text-slate-700 dark:text-slate-300 font-medium">{{ __('Trải nghiệm kho trò chơi giải trí miễn phí không cần cài đặt tại') }} <a href="https://ziigames.online" target="_blank" rel="noopener noreferrer" class="font-bold text-indigo-600 dark:text-indigo-400 underline">ziigames.online</a></span>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="https://ziigames.online" target="_blank" rel="noopener noreferrer" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold transition">
                            {{ __('Khám phá') }} →
                        </a>
                        <button onclick="this.closest('.ad-container').remove()" type="button" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200" title="Đóng">✕</button>
                    </div>
                </div>
            @elseif($slot === 'sidebar')
                <div class="py-4 px-2">
                    <div class="w-12 h-12 mx-auto mb-2 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center text-xl shadow-md">
                        ⭐
                    </div>
                    <div class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ __('Hoàn Toàn Miễn Phí') }}</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1 mb-3">{{ __('Không cần tài khoản, không thu phí, sử dụng ngay lập tức trên trình duyệt.') }}</div>
                    <button onclick="copyText(window.location.href, '{{ __('Đã sao chép link công cụ!') }}')" class="inline-block w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow transition">
                        {{ __('Chia sẻ với bạn bè') }}
                    </button>
                </div>
            @else
                <div class="py-3 flex items-center justify-between gap-4 px-2">
                    <div class="text-left">
                        <span class="inline-block px-2 py-0.5 text-[10px] font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300 rounded mr-2">{{ __('Miễn phí 100%') }}</span>
                        <span class="text-xs text-slate-600 dark:text-slate-300">{{ __('Công cụ trực tuyến tiện lợi cho văn phòng và lập trình viên.') }}</span>
                    </div>
                    <a href="{{ route('home') }}" class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                        {{ __('Xem tất cả') }} →
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

