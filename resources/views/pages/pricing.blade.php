@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">

    <!-- Header Title -->
    <div class="text-center max-w-2xl mx-auto mb-12">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800/50 text-amber-700 dark:text-amber-300 text-xs font-semibold mb-3">
            <span>⚡ Tự Động Hóa 100% Qua VietQR</span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white mb-4">
            Nâng Cấp Gói Pro - Không Giới Hạn
        </h1>
        <p class="text-sm text-slate-600 dark:text-slate-400">
            Trải nghiệm toàn bộ 12 công cụ với tốc độ cao nhất, tắt 100% quảng cáo và mở khóa quyền truy cập Developer REST API.
        </p>
    </div>

    <!-- Pricing Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto mb-16">

        <!-- Free Plan -->
        <div class="p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Gói Miễn Phí (Free)</h3>
                    <span class="text-xs px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-semibold">Cơ bản</span>
                </div>
                <div class="flex items-baseline gap-1 mb-6">
                    <span class="text-4xl font-extrabold text-slate-900 dark:text-white">0đ</span>
                    <span class="text-xs text-slate-500">/ mãi mãi</span>
                </div>
                <ul class="space-y-3 text-xs text-slate-600 dark:text-slate-300 mb-8">
                    <li class="flex items-center gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                        <span>Sử dụng trọn bộ 12 công cụ tiện ích</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                        <span>Xử lý 100% Client-side trên trình duyệt</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                        <span>Bảo mật dữ liệu cá nhân tuyệt đối</span>
                    </li>
                    <li class="flex items-center gap-2 text-slate-400 dark:text-slate-500">
                        <i data-lucide="x" class="w-4 h-4 text-rose-400 shrink-0"></i>
                        <span>Có hiển thị banner quảng cáo tài trợ</span>
                    </li>
                    <li class="flex items-center gap-2 text-slate-400 dark:text-slate-500">
                        <i data-lucide="x" class="w-4 h-4 text-rose-400 shrink-0"></i>
                        <span>Không có API Token cho Developer</span>
                    </li>
                </ul>
            </div>
            <a href="{{ route('home') }}" class="w-full py-3 rounded-xl border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-center transition">
                Tiếp Tục Dùng Miễn Phí
            </a>
        </div>

        <!-- Pro Plan (VietQR Ready) -->
        <div class="relative p-8 rounded-3xl bg-gradient-to-b from-indigo-50/50 to-white dark:from-indigo-950/20 dark:to-slate-900 border-2 border-indigo-600 dark:border-indigo-500 flex flex-col justify-between shadow-xl shadow-indigo-500/10">
            <div class="absolute -top-3.5 right-6 px-3 py-1 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-[11px] font-bold uppercase tracking-wider shadow-md">
                Khuyên Dùng ⭐
            </div>
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Gói Pro Không Giới Hạn</h3>
                    <span class="text-xs px-2.5 py-1 rounded-full bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 font-bold">VietQR Tự Động</span>
                </div>
                <div class="flex items-baseline gap-2 mb-2">
                    <span class="text-4xl font-black text-indigo-600 dark:text-indigo-400" id="planPriceDisplay">{{ number_format($pro['price_monthly'], 0, ',', '.') }}đ</span>
                    <span class="text-xs text-slate-500" id="planPeriodDisplay">/ tháng</span>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-6">Mở khóa vĩnh viễn trên thiết bị với mã License Key.</p>

                <!-- Billing Cycle Toggle -->
                @php
                    $monthlyLabel = ($pro['price_monthly'] >= 1000) ? number_format($pro['price_monthly'] / 1000, 0, ',', '.') . 'k' : number_format($pro['price_monthly'], 0, ',', '.') . 'đ';
                    $yearlyLabel = ($pro['price_yearly'] >= 1000) ? number_format($pro['price_yearly'] / 1000, 0, ',', '.') . 'k' : number_format($pro['price_yearly'], 0, ',', '.') . 'đ';
                    $discountPct = ($pro['price_monthly'] > 0) ? round((1 - ($pro['price_yearly'] / ($pro['price_monthly'] * 12))) * 100) : 0;
                @endphp
                <div class="grid grid-cols-2 p-1 rounded-xl bg-slate-100 dark:bg-slate-800 text-xs font-semibold mb-6">
                    <button type="button" onclick="selectPlan('monthly')" id="btnMonthly" class="py-2 rounded-lg bg-white dark:bg-slate-700 shadow-xs text-slate-900 dark:text-white transition">
                        Theo Tháng ({{ $monthlyLabel }})
                    </button>
                    <button type="button" onclick="selectPlan('yearly')" id="btnYearly" class="py-2 rounded-lg text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition flex items-center justify-center gap-1">
                        <span>Theo Năm ({{ $yearlyLabel }})</span>
                        @if($discountPct > 0)
                            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold">-{{ $discountPct }}%</span>
                        @endif
                    </button>
                </div>

                <ul class="space-y-3 text-xs text-slate-700 dark:text-slate-200 mb-8">
                    <li class="flex items-center gap-2">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                        <span><strong>Tắt 100% quảng cáo</strong> trên toàn hệ thống</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                        <span>Xử lý nén & resize ảnh số lượng lớn không giới hạn</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                        <span>Xuất hình ảnh Mockup độ phân giải siêu nét 4K</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                        <span>Cấp <strong>API Key</strong> gọi REST API trực tiếp</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                        <span>Hỗ trợ kỹ thuật ưu tiên 24/7</span>
                    </li>
                </ul>
            </div>

            <button type="button" onclick="openVietQrPayment()" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-indigo-600 via-indigo-700 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold text-xs sm:text-sm shadow-lg shadow-indigo-500/25 transition flex items-center justify-center gap-2">
                <i data-lucide="qr-code" class="w-4 h-4"></i>
                <span>Thanh Toán Ngay Bằng VietQR</span>
            </button>
        </div>
    </div>

    <!-- Auth Required Modal Dialog -->
    <div id="authRequiredModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm p-4 overflow-y-auto flex items-center justify-center">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl w-full max-w-md shadow-2xl p-6 sm:p-8 relative text-center">
            <button onclick="closeAuthRequiredModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>

            <div class="w-14 h-14 mx-auto rounded-2xl bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300 flex items-center justify-center text-2xl shadow-sm mb-4">
                ⚡
            </div>

            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">
                Đăng Nhập Để Mua Gói Pro
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mb-6">
                Để đảm bảo bản quyền và hóa đơn thanh toán được lưu trữ an toàn vào tài khoản của bạn, vui lòng đăng nhập hoặc tạo tài khoản trước khi quét mã VietQR.
            </p>

            <div class="space-y-3">
                <a href="{{ route('login', ['redirect' => '/pricing']) }}" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold text-xs sm:text-sm shadow-md transition flex items-center justify-center gap-2">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    <span>Đăng Nhập Ngay</span>
                </a>

                <a href="{{ route('register', ['redirect' => '/pricing']) }}" class="w-full py-3 px-4 rounded-xl border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 font-semibold text-xs sm:text-sm transition flex items-center justify-center gap-2">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    <span>Đăng Ký Tài Khoản Mới (10 Giây)</span>
                </a>
            </div>
        </div>
    </div>

    <!-- VietQR Payment Modal Dialog -->
    <div id="vietQrModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm p-4 overflow-y-auto flex items-center justify-center">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl w-full max-w-lg shadow-2xl p-6 sm:p-8 relative">
            <button onclick="closeVietQrModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>

            <div class="text-center mb-6">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 text-xs font-semibold mb-2">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> Cổng Thanh Toán Chuẩn VietQR (Napas247)
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Quét Mã QR Để Kích Hoạt Pro</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Gói Pro sẽ được gắn trực tiếp vào tài khoản: <strong class="text-indigo-600 dark:text-indigo-400">{{ auth()->user()?->email }}</strong></p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-6 p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60 mb-6">
                <!-- QR Image -->
                <div class="w-48 h-48 bg-white p-2 rounded-xl shadow-md border border-slate-200 flex items-center justify-center shrink-0">
                    <img id="vietQrImg" src="" alt="VietQR Napas247" class="w-full h-full object-contain rounded-lg">
                </div>

                <!-- Payment Details -->
                <div class="flex-1 text-xs space-y-2.5 w-full">
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">Ngân hàng</span>
                        <span class="font-bold text-slate-900 dark:text-white" id="qrBankName">{{ $pro['bank_code'] }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">Số tài khoản</span>
                        <div class="flex items-center justify-between">
                            <span class="font-mono font-bold text-indigo-600 dark:text-indigo-400" id="qrAccountNo">{{ $pro['account_number'] }}</span>
                            <button type="button" onclick="copyText(document.getElementById('qrAccountNo').innerText)" class="text-[11px] text-slate-500 hover:text-indigo-600 underline">Copy</button>
                        </div>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">Chủ tài khoản</span>
                        <span class="font-semibold text-slate-800 dark:text-slate-200" id="qrAccountHolder">{{ $pro['account_name'] }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">Số tiền</span>
                        <span class="font-bold text-emerald-600 text-sm" id="qrAmountDisplay">{{ number_format($pro['price_monthly'], 0, ',', '.') }} VNĐ</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">Nội dung chuyển khoản</span>
                        <div class="flex items-center justify-between">
                            <span class="font-mono font-bold text-amber-600 dark:text-amber-400" id="qrMemoDisplay">PRO...</span>
                            <button type="button" onclick="copyText(document.getElementById('qrMemoDisplay').innerText)" class="text-[11px] text-slate-500 hover:text-amber-600 underline">Copy</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Confirmation & Activation -->
            <div class="space-y-3">
                <button type="button" id="btnConfirmPayment" onclick="confirmPaymentAndActivate()" class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm shadow-md transition flex items-center justify-center gap-2">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>Tôi Đã Chuyển Khoản Thành Công (Kích Hoạt Ngay)</span>
                </button>
                <p class="text-[11px] text-center text-slate-400">
                    Hệ thống sẽ cập nhật trạng thái gói Pro và lưu trữ mã License vào tài khoản của bạn.
                </p>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    let currentSelectedPlan = 'monthly';
    let currentOrderCode = '';
    const isUserLoggedIn = @json(auth()->check());
    const priceMonthly = @json($pro['price_monthly']);
    const priceYearly = @json($pro['price_yearly']);
    @php
        $jsFormattedMonthly = number_format($pro['price_monthly'], 0, ',', '.') . 'đ';
        $jsFormattedYearly = number_format($pro['price_yearly'], 0, ',', '.') . 'đ';
    @endphp
    const formattedPriceMonthly = @json($jsFormattedMonthly);
    const formattedPriceYearly = @json($jsFormattedYearly);

    function selectPlan(plan) {
        currentSelectedPlan = plan;
        const btnMonthly = document.getElementById('btnMonthly');
        const btnYearly = document.getElementById('btnYearly');
        const priceDisplay = document.getElementById('planPriceDisplay');
        const periodDisplay = document.getElementById('planPeriodDisplay');

        if (plan === 'yearly') {
            btnMonthly.className = 'py-2 rounded-lg text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition';
            btnYearly.className = 'py-2 rounded-lg bg-white dark:bg-slate-700 shadow-xs text-slate-900 dark:text-white transition flex items-center justify-center gap-1';
            priceDisplay.innerText = formattedPriceYearly;
            periodDisplay.innerText = '/ năm';
        } else {
            btnMonthly.className = 'py-2 rounded-lg bg-white dark:bg-slate-700 shadow-xs text-slate-900 dark:text-white transition';
            btnYearly.className = 'py-2 rounded-lg text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition flex items-center justify-center gap-1';
            priceDisplay.innerText = formattedPriceMonthly;
            periodDisplay.innerText = '/ tháng';
        }
    }

    async function openVietQrPayment() {
        if (!isUserLoggedIn) {
            document.getElementById('authRequiredModal').classList.remove('hidden');
            return;
        }

        try {
            const res = await fetch('{{ route("payment.vietqr") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ plan: currentSelectedPlan })
            });

            const data = await res.json();
            if (data.require_auth) {
                document.getElementById('authRequiredModal').classList.remove('hidden');
                return;
            }

            if (data.success) {
                currentOrderCode = data.order_code;
                document.getElementById('vietQrImg').src = data.qr_image_url;
                document.getElementById('qrBankName').innerText = data.bank_code;
                document.getElementById('qrAccountNo').innerText = data.account_number;
                document.getElementById('qrAccountHolder').innerText = data.account_name;
                document.getElementById('qrAmountDisplay').innerText = data.formatted_amount;
                document.getElementById('qrMemoDisplay').innerText = data.memo;

                document.getElementById('vietQrModal').classList.remove('hidden');
            }
        } catch (e) {
            showToast('Không thể tạo mã VietQR lúc này, xin vui lòng thử lại sau!', 'error');
        }
    }

    function closeVietQrModal() {
        document.getElementById('vietQrModal').classList.add('hidden');
    }

    function closeAuthRequiredModal() {
        document.getElementById('authRequiredModal').classList.add('hidden');
    }

    async function confirmPaymentAndActivate() {
        if (!currentOrderCode) {
            showToast('Không tìm thấy mã đơn hàng!', 'error');
            return;
        }

        const btn = document.getElementById('btnConfirmPayment');
        btn.disabled = true;
        btn.innerHTML = '<span class="animate-spin inline-block mr-2">⏳</span> Đang xác thực chuyển khoản...';

        try {
            const res = await fetch('{{ route("payment.confirm") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ order_code: currentOrderCode })
            });

            const data = await res.json();
            if (data.success) {
                closeVietQrModal();
                showToast(data.message, 'success');
                setTimeout(() => {
                    window.location.href = data.redirect_url || '{{ route("account") }}';
                }, 1000);
            } else {
                showToast(data.message || 'Xác thực thất bại!', 'error');
                btn.disabled = false;
                btn.innerHTML = '<i data-lucide="check" class="w-4 h-4"></i><span>Tôi Đã Chuyển Khoản Thành Công (Kích Hoạt Ngay)</span>';
                lucide.createIcons();
            }
        } catch (e) {
            showToast('Đã xảy ra lỗi khi gửi yêu cầu xác nhận.', 'error');
            btn.disabled = false;
            btn.innerHTML = '<i data-lucide="check" class="w-4 h-4"></i><span>Tôi Đã Chuyển Khoản Thành Công (Kích Hoạt Ngay)</span>';
            lucide.createIcons();
        }
    }
</script>
@endpush
@endsection

