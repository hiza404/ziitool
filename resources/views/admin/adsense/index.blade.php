@extends('admin.layouts.admin')

@section('title', 'Quản Lý Quảng Cáo Google AdSense')

@section('content')
<div class="max-w-4xl space-y-6">

    <div>
        <h3 class="text-base font-bold text-white mb-1">Cấu Hình Doanh Thu Google AdSense & Banner</h3>
        <p class="text-xs text-slate-400">Thay đổi trực tiếp Client ID và Slot ID mà không cần sửa code. Thay đổi sẽ có hiệu lực tức thì ngoài trang chủ.</p>
    </div>

    <form method="POST" action="{{ route('admin.adsense.update') }}" class="space-y-6">
        @csrf

        <!-- Master Toggles Card -->
        <div class="p-6 rounded-3xl bg-slate-950 border border-slate-800 space-y-5">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">1. Chế Độ Hoạt Động</h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                
                <!-- Toggle Ads Enabled -->
                <div class="p-4 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-between">
                    <div>
                        <span class="font-bold text-xs text-white block mb-0.5">Bật Quảng Cáo Hệ Thống</span>
                        <span class="text-[11px] text-slate-400">Hiển thị banner tại 4 vị trí vàng</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="ads_enabled" value="1" {{ $settings['ads_enabled'] ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                </div>

                <!-- Toggle Demo Mode -->
                <div class="p-4 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-between">
                    <div>
                        <span class="font-bold text-xs text-white block mb-0.5">Chế Độ Demo Banner</span>
                        <span class="text-[11px] text-slate-400">Tự hiển thị banner đẹp khi chưa có ID AdSense</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="ads_demo_mode" value="1" {{ $settings['ads_demo_mode'] ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                    </label>
                </div>

            </div>
        </div>

        <!-- Google AdSense IDs -->
        <div class="p-6 rounded-3xl bg-slate-950 border border-slate-800 space-y-5">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">2. Mã Định Danh Google AdSense</h4>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Publisher Client ID (Mã tài khoản AdSense)</label>
                <div class="relative flex items-center">
                    <input type="text" name="adsense_client_id" value="{{ $settings['adsense_client_id'] }}" placeholder="ca-pub-9988776655443322" class="w-full px-4 py-2.5 rounded-xl border border-slate-700 bg-slate-900 text-white font-mono text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <span class="text-[10px] text-slate-500 block mt-1">Bắt đầu bằng <code class="text-indigo-400">ca-pub-</code> nhận được trong Google AdSense Dashboard.</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Slot ID - Top Leaderboard (Trang chủ)</label>
                    <input type="text" name="ads_slot_top" value="{{ $settings['ads_slot_top'] }}" placeholder="1234567890" class="w-full px-3.5 py-2 rounded-xl border border-slate-700 bg-slate-900 text-white font-mono text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Slot ID - In-Tool (Dưới công cụ)</label>
                    <input type="text" name="ads_slot_in_tool" value="{{ $settings['ads_slot_in_tool'] }}" placeholder="2345678901" class="w-full px-3.5 py-2 rounded-xl border border-slate-700 bg-slate-900 text-white font-mono text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Slot ID - Sidebar Cố Định</label>
                    <input type="text" name="ads_slot_sidebar" value="{{ $settings['ads_slot_sidebar'] }}" placeholder="3456789012" class="w-full px-3.5 py-2 rounded-xl border border-slate-700 bg-slate-900 text-white font-mono text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Slot ID - Sticky Bottom (Chân trang)</label>
                    <input type="text" name="ads_slot_sticky" value="{{ $settings['ads_slot_sticky'] }}" placeholder="4567890123" class="w-full px-3.5 py-2 rounded-xl border border-slate-700 bg-slate-900 text-white font-mono text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
            </div>
        </div>

        <!-- Announcement Banner -->
        <div class="p-6 rounded-3xl bg-slate-950 border border-slate-800 space-y-4">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">3. Thanh Thông Báo Khuyến Mãi (Toàn Trang)</h4>
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Nội dung thông báo (Hiển thị đầu trang web)</label>
                <input type="text" name="announcement_banner" value="{{ $settings['announcement_banner'] }}" placeholder="Ví dụ: Giảm giá 50% Gói Pro nhân dịp khai trương..." class="w-full px-4 py-2.5 rounded-xl border border-slate-700 bg-slate-900 text-white text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                <span class="text-[10px] text-slate-500 block mt-1">Để trống nếu không muốn hiển thị thanh thông báo.</span>
            </div>
        </div>

        <button type="submit" class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i>
            <span>Lưu Cấu Hình Quảng Cáo</span>
        </button>
    </form>

</div>
@endsection

