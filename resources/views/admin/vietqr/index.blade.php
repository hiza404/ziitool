@extends('admin.layouts.admin')

@section('title', 'VietQR, Đơn Hàng & Mã Bản Quyền Pro')

@section('content')
<div class="space-y-8">

    <!-- Top Forms: Bank Config & Price Config -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Bank Account Configuration -->
        <div class="p-6 rounded-3xl bg-slate-950 border border-slate-800 space-y-4">
            <h3 class="text-sm font-bold text-white flex items-center gap-2">
                <i data-lucide="landmark" class="w-4 h-4 text-indigo-400"></i> Tài Khoản Ngân Hàng VietQR (Napas247)
            </h3>
            <p class="text-xs text-slate-400">Khi người dùng thanh toán gói Pro, hệ thống sẽ tạo mã VietQR theo số tài khoản này.</p>

            <form method="POST" action="{{ route('admin.vietqr.bank') }}" class="space-y-3 pt-1">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Mã Ngân Hàng (Bank Code)</label>
                    <select name="bank_code" class="w-full px-3.5 py-2 rounded-xl border border-slate-700 bg-slate-900 text-white text-xs font-semibold focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <option value="MB" {{ $vietqr['bank_code'] === 'MB' ? 'selected' : '' }}>MBBank (Quân Đội)</option>
                        <option value="VCB" {{ $vietqr['bank_code'] === 'VCB' ? 'selected' : '' }}>Vietcombank (Ngoại Thương)</option>
                        <option value="TCB" {{ $vietqr['bank_code'] === 'TCB' ? 'selected' : '' }}>Techcombank</option>
                        <option value="ACB" {{ $vietqr['bank_code'] === 'ACB' ? 'selected' : '' }}>ACB (Á Châu)</option>
                        <option value="VPB" {{ $vietqr['bank_code'] === 'VPB' ? 'selected' : '' }}>VPBank</option>
                        <option value="TPB" {{ $vietqr['bank_code'] === 'TPB' ? 'selected' : '' }}>TPBank (Tiên Phong)</option>
                        <option value="BIDV" {{ $vietqr['bank_code'] === 'BIDV' ? 'selected' : '' }}>BIDV</option>
                        <option value="VIB" {{ $vietqr['bank_code'] === 'VIB' ? 'selected' : '' }}>VIB</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Số Tài Khoản</label>
                    <input type="text" name="account_number" value="{{ $vietqr['account_number'] }}" required class="w-full px-3.5 py-2 rounded-xl border border-slate-700 bg-slate-900 text-white font-mono text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Tên Chủ Tài Khoản (Không dấu in hoa)</label>
                    <input type="text" name="account_name" value="{{ $vietqr['account_name'] }}" required class="w-full px-3.5 py-2 rounded-xl border border-slate-700 bg-slate-900 text-white uppercase text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>

                <button type="submit" class="w-full py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition">
                    Cập Nhật Tài Khoản VietQR
                </button>
            </form>
        </div>

        <!-- Pro Pricing Configuration -->
        <div class="p-6 rounded-3xl bg-slate-950 border border-slate-800 space-y-4">
            <h3 class="text-sm font-bold text-white flex items-center gap-2">
                <i data-lucide="tag" class="w-4 h-4 text-emerald-400"></i> Bảng Giá Gói Pro
            </h3>
            <p class="text-xs text-slate-400">Giá bán hiển thị ngoài trang bảng giá và số tiền yêu cầu thanh toán trên mã QR.</p>

            <form method="POST" action="{{ route('admin.vietqr.price') }}" class="space-y-3 pt-1">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Giá Gói Tháng (VNĐ)</label>
                    <div class="relative flex items-center">
                        <input type="number" name="price_monthly" value="{{ $vietqr['price_monthly'] }}" step="1000" min="1000" required class="w-full px-3.5 py-2 rounded-xl border border-slate-700 bg-slate-900 text-white font-mono text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <span class="absolute right-3 text-xs text-slate-500">đ/tháng</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Giá Gói Năm (VNĐ)</label>
                    <div class="relative flex items-center">
                        <input type="number" name="price_yearly" value="{{ $vietqr['price_yearly'] }}" step="1000" min="1000" required class="w-full px-3.5 py-2 rounded-xl border border-slate-700 bg-slate-900 text-white font-mono text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <span class="absolute right-3 text-xs text-slate-500">đ/năm</span>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition">
                        Lưu Bảng Giá Mới
                    </button>
                </div>
            </form>
        </div>

    </div>

    <!-- Pro License Keys Management -->
    <div class="p-6 rounded-3xl bg-slate-950 border border-slate-800 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <i data-lucide="key" class="w-4 h-4 text-amber-400"></i> Quản Lý Mã Bản Quyền (License Keys)
                </h3>
                <p class="text-xs text-slate-400">Cấp mã Pro cho khách hàng sau khi nhận được tiền chuyển khoản.</p>
            </div>
            <button onclick="document.getElementById('createLicenseBox').classList.toggle('hidden')" class="px-3.5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs transition flex items-center gap-1.5 self-start">
                <i data-lucide="plus" class="w-4 h-4"></i> Cấp Mã Mới
            </button>
        </div>

        <!-- Quick Create License Form (Toggled) -->
        <div id="createLicenseBox" class="hidden p-4 rounded-2xl bg-slate-900 border border-slate-800">
            <form method="POST" action="{{ route('admin.vietqr.license') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-300 mb-1">Mã Tự Đặt (Để trống sẽ tự sinh)</label>
                    <input type="text" name="custom_code" placeholder="PRO-VIP-XXXX" class="w-full px-3 py-2 rounded-xl border border-slate-700 bg-slate-800 text-white font-mono uppercase">
                </div>
                <div>
                    <label class="block font-semibold text-slate-300 mb-1">Gói Bản Quyền</label>
                    <select name="plan" class="w-full px-3 py-2 rounded-xl border border-slate-700 bg-slate-800 text-white font-semibold">
                        <option value="monthly">1 Tháng</option>
                        <option value="yearly" selected>1 Năm</option>
                        <option value="lifetime">Trọn Đời</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-300 mb-1">Tên Khách Hàng</label>
                    <input type="text" name="customer_name" placeholder="Nguyễn Văn A" class="w-full px-3 py-2 rounded-xl border border-slate-700 bg-slate-800 text-white">
                </div>
                <button type="submit" class="py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold">
                    Tạo Key Ngay
                </button>
            </form>
        </div>

        <!-- Licenses Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-900 border-b border-slate-800 text-slate-400 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Mã License</th>
                        <th class="py-3 px-4">Gói</th>
                        <th class="py-3 px-4">Khách Hàng</th>
                        <th class="py-3 px-4">Ngày Tạo</th>
                        <th class="py-3 px-4">Trạng Thái</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @foreach($licenses as $lic)
                        <tr class="hover:bg-slate-900/40">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-amber-400 select-all">{{ $lic->code }}</span>
                                    <button type="button" onclick="copyText('{{ $lic->code }}')" class="p-1 text-slate-500 hover:text-white" title="Sao chép"><i data-lucide="copy" class="w-3.5 h-3.5"></i></button>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-900 text-slate-300 uppercase">{{ $lic->plan }}</span>
                            </td>
                            <td class="py-3 px-4 text-slate-300">
                                {{ $lic->customer_name ?: 'Khách hàng' }}
                            </td>
                            <td class="py-3 px-4 text-slate-400">
                                {{ $lic->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3 px-4">
                                <button type="button" onclick="toggleLicenseActive({{ $lic->id }}, this)" class="px-2.5 py-1 rounded-full text-[10px] font-bold transition {{ $lic->is_active ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-400 border border-rose-500/30' }}">
                                    {{ $lic->is_active ? 'Đang Hoạt Động' : 'Đã Khóa' }}
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pt-2">
            {{ $licenses->links() }}
        </div>
    </div>

    <!-- VietQR Orders Management -->
    <div class="p-6 rounded-3xl bg-slate-950 border border-slate-800 space-y-4">
        <h3 class="text-sm font-bold text-white flex items-center gap-2">
            <i data-lucide="list-ordered" class="w-4 h-4 text-emerald-400"></i> Lịch Sử Đơn Hàng VietQR
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-900 border-b border-slate-800 text-slate-400 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Mã Đơn (Memo)</th>
                        <th class="py-3 px-4">Gói</th>
                        <th class="py-3 px-4">Số Tiền</th>
                        <th class="py-3 px-4">Khách Hàng</th>
                        <th class="py-3 px-4">Thời Gian</th>
                        <th class="py-3 px-4">Trạng Thái</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($orders as $ord)
                        <tr class="hover:bg-slate-900/40">
                            <td class="py-3 px-4 font-mono font-bold text-white">
                                {{ $ord->order_code }}
                            </td>
                            <td class="py-3 px-4 uppercase text-slate-300">
                                {{ $ord->plan }}
                            </td>
                            <td class="py-3 px-4 font-mono font-bold text-emerald-400">
                                {{ $ord->formatted_amount }}
                            </td>
                            <td class="py-3 px-4 text-slate-300">
                                {{ $ord->customer_name ?: 'Khách hàng' }}
                            </td>
                            <td class="py-3 px-4 text-slate-400">
                                {{ $ord->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3 px-4">
                                <form method="POST" action="{{ route('admin.vietqr.order.status', $ord->id) }}" class="inline-block">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="px-2 py-1 rounded-lg border border-slate-700 bg-slate-900 text-xs font-semibold {{ $ord->status === 'paid' ? 'text-emerald-400' : ($ord->status === 'pending' ? 'text-amber-400' : 'text-rose-400') }}">
                                        <option value="pending" {{ $ord->status === 'pending' ? 'selected' : '' }}>Chờ quét mã</option>
                                        <option value="paid" {{ $ord->status === 'paid' ? 'selected' : '' }}>Đã thanh toán ✓</option>
                                        <option value="cancelled" {{ $ord->status === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-500">Chưa có giao dịch đơn hàng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pt-2">
            {{ $orders->links() }}
        </div>
    </div>

</div>

@push('scripts')
<script>
    async function toggleLicenseActive(id, btn) {
        try {
            const res = await fetch(`/admin/vietqr/license/${id}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            const data = await res.json();
            if (data.success) {
                btn.innerText = data.is_active ? 'Đang Hoạt Động' : 'Đã Khóa';
                btn.className = data.is_active 
                    ? 'px-2.5 py-1 rounded-full text-[10px] font-bold transition bg-emerald-500/20 text-emerald-400 border border-emerald-500/30'
                    : 'px-2.5 py-1 rounded-full text-[10px] font-bold transition bg-rose-500/20 text-rose-400 border border-rose-500/30';
            }
        } catch (e) {
            alert('Lỗi kết nối máy chủ!');
        }
    }
</script>
@endpush
@endsection
