@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
    
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200 dark:border-indigo-800 text-indigo-700 dark:text-indigo-300 text-xs font-semibold mb-2">
                <i data-lucide="user-check" class="w-3.5 h-3.5"></i> Trung Tâm Tài Khoản
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">
                Tài Khoản & Quản Lý Bản Quyền
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Theo dõi trạng thái hội viên, mã License Pro và lịch sử thanh toán VietQR của bạn
            </p>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-semibold flex items-center gap-2 transition">
                <i data-lucide="log-out" class="w-4 h-4 text-slate-500"></i>
                <span>Đăng xuất</span>
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-900 text-emerald-800 dark:text-emerald-300 text-xs sm:text-sm flex items-center gap-3">
            <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900 text-rose-800 dark:text-rose-300 text-xs sm:text-sm flex items-center gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 text-rose-500 shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        
        <!-- Left Column: User Profile & Pro Subscription Status -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- User Info Card -->
            <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-600 via-purple-600 to-pink-500 text-white flex items-center justify-center font-bold text-xl shadow-md">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-base text-slate-900 dark:text-white">{{ $user->name }}</h3>
                        <p class="text-xs text-slate-500">{{ $user->email }}</p>
                        <div class="flex items-center gap-1.5 mt-1">
                            @if($user->is_admin)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300">
                                    Quản trị viên
                                </span>
                            @endif
                            @if($user->isPro())
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300 flex items-center gap-1">
                                    <span>⚡ PRO</span>
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                                    Thành viên Miễn phí
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-800 text-xs text-slate-500 space-y-2">
                    <div class="flex justify-between">
                        <span>Ngày tham gia:</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $user->created_at->format('d/m/Y') }}</span>
                    </div>
                    @if($user->is_admin)
                        <div class="pt-2">
                            <a href="{{ route('admin.dashboard') }}" class="w-full py-2 px-3 rounded-xl bg-slate-900 text-white dark:bg-white dark:text-slate-900 font-semibold text-xs flex items-center justify-center gap-1.5 hover:opacity-90 transition">
                                <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                                <span>Mở Trang Admin Quản Trị</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pro Status Card -->
            @if($user->isPro())
                <div class="p-6 rounded-3xl bg-gradient-to-br from-indigo-900 via-indigo-800 to-purple-900 text-white shadow-xl shadow-indigo-500/20 relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-amber-400 text-slate-900 uppercase tracking-wide">
                            ⭐ Đang Kích Hoạt
                        </span>
                        <span class="text-xs text-indigo-200">Gói Pro VIP</span>
                    </div>
                    <h3 class="text-xl font-black mb-1">Gói Pro Không Giới Hạn</h3>
                    <p class="text-xs text-indigo-200 mb-6">Tài khoản của bạn đã được mở khóa toàn bộ quyền lợi cao cấp và tắt 100% quảng cáo.</p>
                    
                    <div class="space-y-2 text-xs border-t border-indigo-700/60 pt-4">
                        <div class="flex justify-between">
                            <span class="text-indigo-300">Loại gói:</span>
                            <span class="font-semibold uppercase">{{ $user->pro_plan ?? 'Năm' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-indigo-300">Hạn sử dụng:</span>
                            <span class="font-semibold text-amber-300">
                                {{ $user->pro_expires_at ? $user->pro_expires_at->format('d/m/Y H:i') : 'Vĩnh viễn (Lifetime)' }}
                            </span>
                        </div>
                    </div>
                </div>
            @else
                <div class="p-6 rounded-3xl bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-950/30 dark:to-orange-950/20 border border-amber-200 dark:border-amber-800/60">
                    <div class="flex items-center gap-2 mb-2 text-amber-800 dark:text-amber-300 font-bold text-sm">
                        <i data-lucide="sparkles" class="w-4 h-4 text-amber-500"></i>
                        <span>Nâng Cấp Gói Pro</span>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-400 mb-4 leading-relaxed">
                        Tắt toàn bộ quảng cáo, tăng tốc xử lý và mở khóa API Key cho Developer chỉ từ 49k/tháng qua VietQR tự động.
                    </p>
                    <a href="{{ route('pricing') }}" class="w-full py-2.5 px-4 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold text-xs shadow-sm flex items-center justify-center gap-2 transition">
                        <span>Nâng cấp ngay bằng VietQR</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            @endif

            <!-- Redeem License Key Card -->
            <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                <h3 class="font-bold text-sm text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                    <i data-lucide="key" class="w-4 h-4 text-indigo-500"></i>
                    <span>Kích Hoạt Mã License</span>
                </h3>
                <p class="text-xs text-slate-500 mb-4">Nhập mã bản quyền hoặc mã voucher để gia hạn/kích hoạt Gói Pro vào tài khoản.</p>

                <form action="{{ route('account.redeem') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <input type="text" name="license_code" required
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white font-mono text-xs uppercase focus:ring-2 focus:ring-indigo-500 transition placeholder:normal-case"
                               placeholder="VD: PRO-SUPER-2026">
                    </div>
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-sm transition flex items-center justify-center gap-1.5">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        <span>Kích Hoạt Ngay</span>
                    </button>
                </form>
                <div class="mt-3 text-[11px] text-slate-400 text-center">
                    Mã dùng thử có sẵn: <code class="font-mono text-indigo-600 dark:text-indigo-400 font-bold">PRO-SUPER-2026</code>
                </div>
            </div>

        </div>

        <!-- Right Column: Licenses and Orders History -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Pro Licenses Owned -->
            <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-base text-slate-900 dark:text-white flex items-center gap-2">
                        <i data-lucide="award" class="w-5 h-5 text-indigo-500"></i>
                        <span>Mã Bản Quyền Của Bạn ({{ $licenses->count() }})</span>
                    </h3>
                </div>

                @if($licenses->isEmpty())
                    <div class="py-8 text-center text-slate-400 text-xs">
                        <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                        <span>Bạn chưa sở hữu mã bản quyền nào. Hãy nâng cấp Gói Pro hoặc nhập mã kích hoạt phía bên trái.</span>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                            <thead>
                                <tr class="border-b border-slate-200 dark:border-slate-800 text-[11px] uppercase font-bold text-slate-400">
                                    <th class="py-2.5">Mã License</th>
                                    <th class="py-2.5">Loại Gói</th>
                                    <th class="py-2.5">Hạn Sử Dụng</th>
                                    <th class="py-2.5">Trạng Thái</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach($licenses as $lic)
                                    <tr>
                                        <td class="py-3 font-mono font-bold text-indigo-600 dark:text-indigo-400 select-all">
                                            {{ $lic->code }}
                                        </td>
                                        <td class="py-3 uppercase font-semibold">
                                            {{ $lic->plan }}
                                        </td>
                                        <td class="py-3 text-slate-500">
                                            {{ $lic->expires_at ? $lic->expires_at->format('d/m/Y') : 'Vĩnh viễn' }}
                                        </td>
                                        <td class="py-3">
                                            @if($lic->is_active)
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                                                    Hợp lệ
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-500">
                                                    Hết hạn
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Order History Table -->
            <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-base text-slate-900 dark:text-white flex items-center gap-2">
                        <i data-lucide="receipt" class="w-5 h-5 text-indigo-500"></i>
                        <span>Lịch Sử Đơn Hàng VietQR ({{ $orders->count() }})</span>
                    </h3>
                </div>

                @if($orders->isEmpty())
                    <div class="py-8 text-center text-slate-400 text-xs">
                        <i data-lucide="shopping-bag" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                        <span>Chưa có giao dịch nào được ghi nhận cho tài khoản này.</span>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                            <thead>
                                <tr class="border-b border-slate-200 dark:border-slate-800 text-[11px] uppercase font-bold text-slate-400">
                                    <th class="py-2.5">Mã Đơn</th>
                                    <th class="py-2.5">Gói</th>
                                    <th class="py-2.5">Số Tiền</th>
                                    <th class="py-2.5">Ngân Hàng</th>
                                    <th class="py-2.5">Ngày Tạo</th>
                                    <th class="py-2.5">Trạng Thái</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach($orders as $ord)
                                    <tr>
                                        <td class="py-3 font-mono font-bold text-slate-900 dark:text-white">
                                            {{ $ord->order_code }}
                                        </td>
                                        <td class="py-3 uppercase font-semibold">
                                            {{ $ord->plan }}
                                        </td>
                                        <td class="py-3 font-bold text-slate-900 dark:text-white">
                                            {{ $ord->formatted_amount }}
                                        </td>
                                        <td class="py-3 text-slate-500">
                                            {{ $ord->bank_code }}
                                        </td>
                                        <td class="py-3 text-slate-500">
                                            {{ $ord->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="py-3">
                                            @if($ord->status === 'paid')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                                                    Đã Thanh Toán
                                                </span>
                                            @elseif($ord->status === 'pending')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                                                    Chờ Chuyển Khoản
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300">
                                                    Đã Hủy
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
