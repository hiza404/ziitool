@extends('admin.layouts.admin')

@section('title', 'Bảng Điều Khiển Tổng Quan')

@section('content')
<div class="space-y-6">

    <!-- Top Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <!-- Revenue -->
        <div class="p-5 rounded-2xl bg-slate-950 border border-slate-800 flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-400 block mb-1">Doanh Thu VietQR</span>
                <span class="text-xl font-black text-emerald-400 font-mono">{{ $formattedRevenue }}</span>
                <span class="text-[10px] text-slate-500 block mt-1">{{ $paidOrdersCount }} đơn đã thanh toán</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                <i data-lucide="wallet" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Pro Licenses -->
        <div class="p-5 rounded-2xl bg-slate-950 border border-slate-800 flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-400 block mb-1">Mã Pro Hoạt Động</span>
                <span class="text-xl font-black text-amber-400 font-mono">{{ $activeLicensesCount }}</span>
                <span class="text-[10px] text-slate-500 block mt-1">Đang mở khóa Pro</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center">
                <i data-lucide="key" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Active Tools -->
        <div class="p-5 rounded-2xl bg-slate-950 border border-slate-800 flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-400 block mb-1">Công Cụ Hoạt Động</span>
                <span class="text-xl font-black text-indigo-400 font-mono">{{ $activeToolsCount }} / {{ $totalToolsCount }}</span>
                <span class="text-[10px] text-slate-500 block mt-1">Trạng thái ổn định 100%</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center">
                <i data-lucide="cpu" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- AdSense Status -->
        <div class="p-5 rounded-2xl bg-slate-950 border border-slate-800 flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-400 block mb-1">Trạng Thái AdSense</span>
                <div class="flex items-center gap-2 mt-1">
                    @if($adsEnabled)
                        <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Đang Bật</span>
                        <span class="text-[10px] text-slate-500">{{ $adsDemoMode ? '(Demo)' : '(Live)' }}</span>
                    @else
                        <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-rose-500/20 text-rose-400 border border-rose-500/30">Tạm Tắt</span>
                    @endif
                </div>
                <span class="text-[10px] text-slate-500 block mt-1 truncate max-w-[140px]">{{ $adsenseClientId }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center">
                <i data-lucide="layout" class="w-6 h-6"></i>
            </div>
        </div>

    </div>

    <!-- Quick Actions Banner -->
    <div class="p-6 rounded-3xl bg-gradient-to-r from-indigo-900/40 via-purple-900/20 to-slate-950 border border-indigo-500/30 flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="text-base font-bold text-white mb-1">⚡ Quản Trị Hệ Thống Tự Động Hóa 100%</h3>
            <p class="text-xs text-slate-400">Điều chỉnh cấu hình ngân hàng VietQR, bật tắt công cụ hoặc thay đổi Publisher ID Google AdSense trực tiếp.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.tools.index') }}" class="px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition flex items-center gap-1.5">
                <i data-lucide="wrench" class="w-3.5 h-3.5"></i> Quản Lý Công Cụ
            </a>
            <a href="{{ route('admin.vietqr.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold transition flex items-center gap-1.5">
                <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i> Cấp Mã Pro
            </a>
        </div>
    </div>

    <!-- 2 Columns: Orders & Licenses -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Recent Orders -->
        <div class="p-6 rounded-3xl bg-slate-950 border border-slate-800 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <i data-lucide="shopping-cart" class="w-4 h-4 text-emerald-400"></i> Đơn Hàng VietQR Mới Nhất
                </h3>
                <a href="{{ route('admin.vietqr.index') }}" class="text-xs text-indigo-400 hover:underline">Xem tất cả →</a>
            </div>

            <div class="divide-y divide-slate-800/80">
                @forelse($latestOrders as $order)
                    <div class="py-3 flex items-center justify-between text-xs">
                        <div>
                            <span class="font-mono font-bold text-white block">{{ $order->order_code }}</span>
                            <span class="text-[11px] text-slate-400">{{ $order->customer_name ?: 'Khách hàng' }} • {{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="text-right">
                            <span class="font-mono font-bold text-emerald-400 block">{{ $order->formatted_amount }}</span>
                            @if($order->status === 'paid')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-400">Đã thanh toán</span>
                            @elseif($order->status === 'pending')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-400">Chờ quét mã</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/20 text-rose-400">Đã hủy</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-6 text-center text-xs text-slate-500">Chưa có đơn hàng nào.</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Licenses -->
        <div class="p-6 rounded-3xl bg-slate-950 border border-slate-800 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <i data-lucide="key" class="w-4 h-4 text-amber-400"></i> Mã Bản Quyền Pro
                </h3>
                <a href="{{ route('admin.vietqr.index') }}" class="text-xs text-indigo-400 hover:underline">Quản lý key →</a>
            </div>

            <div class="divide-y divide-slate-800/80">
                @forelse($latestLicenses as $license)
                    <div class="py-3 flex items-center justify-between text-xs">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-bold text-amber-400">{{ $license->code }}</span>
                                <button onclick="copyText('{{ $license->code }}')" class="text-slate-500 hover:text-white" title="Copy"><i data-lucide="copy" class="w-3.5 h-3.5"></i></button>
                            </div>
                            <span class="text-[11px] text-slate-400">{{ $license->customer_name }} • Gói {{ ucfirst($license->plan) }}</span>
                        </div>
                        <div>
                            @if($license->is_active)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-400">Hoạt động</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/20 text-rose-400">Đã khóa</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-6 text-center text-xs text-slate-500">Chưa có mã nào được cấp.</div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection

