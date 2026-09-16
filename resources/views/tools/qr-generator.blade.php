@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-indigo-600">Trang chủ</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>{{ $categoryInfo['name'] ?? 'Đồ họa' }}</span>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="text-slate-800 dark:text-slate-200 font-semibold">{{ $tool['title'] }}</span>
    </nav>

    <!-- Tool Header -->
    <div class="mb-8">
        <div class="flex flex-wrap items-center gap-2 mb-2">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">
                {{ $tool['title'] }}
            </h1>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-rose-100 dark:bg-rose-900/60 text-rose-700 dark:text-rose-300 font-semibold">
                Chèn Logo & Đổi Màu
            </span>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 font-semibold">
                Mã QR Tĩnh Vĩnh Viễn
            </span>
        </div>
        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 max-w-3xl">
            {{ $tool['short_desc'] }} Hỗ trợ tạo QR website, mật khẩu WiFi, danh bạ vCard và số tài khoản ngân hàng VietQR.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        
        <!-- Controls Column (2 cols) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- QR Content Type Tabs -->
            <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">1. Chọn Loại Nội Dung</h3>
                
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <button type="button" onclick="setQrType('url')" id="qrTab_url" class="p-3 rounded-xl border border-indigo-500 bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-300 text-xs font-bold flex flex-col items-center gap-1.5 transition">
                        <i data-lucide="link" class="w-4 h-4"></i> Link URL
                    </button>
                    <button type="button" onclick="setQrType('wifi')" id="qrTab_wifi" class="p-3 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-semibold flex flex-col items-center gap-1.5 hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                        <i data-lucide="wifi" class="w-4 h-4"></i> WiFi
                    </button>
                    <button type="button" onclick="setQrType('vcard')" id="qrTab_vcard" class="p-3 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-semibold flex flex-col items-center gap-1.5 hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                        <i data-lucide="user" class="w-4 h-4"></i> Danh Bạ
                    </button>
                    <button type="button" onclick="setQrType('text')" id="qrTab_text" class="p-3 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-semibold flex flex-col items-center gap-1.5 hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                        <i data-lucide="type" class="w-4 h-4"></i> Văn Bản
                    </button>
                </div>

                <!-- URL Inputs -->
                <div id="qrInput_url" class="space-y-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Đường dẫn Website (URL)</label>
                    <input type="url" id="qrText_url" value="https://example.com" oninput="renderCustomQr()" placeholder="https://example.com" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none font-mono">
                </div>

                <!-- WiFi Inputs -->
                <div id="qrInput_wifi" class="hidden space-y-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tên mạng WiFi (SSID)</label>
                        <input type="text" id="wifiSsid" placeholder="MyHome_5G" oninput="renderCustomQr()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Mật khẩu WiFi</label>
                        <input type="text" id="wifiPass" placeholder="12345678" oninput="renderCustomQr()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-mono">
                    </div>
                </div>

                <!-- vCard Inputs -->
                <div id="qrInput_vcard" class="hidden space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Họ và Tên</label>
                            <input type="text" id="vcardName" placeholder="Nguyễn Văn A" oninput="renderCustomQr()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Số điện thoại</label>
                            <input type="text" id="vcardPhone" placeholder="0912345678" oninput="renderCustomQr()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-mono">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Email</label>
                        <input type="email" id="vcardEmail" placeholder="name@company.com" oninput="renderCustomQr()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs">
                    </div>
                </div>

                <!-- Plain Text Inputs -->
                <div id="qrInput_text" class="hidden space-y-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Văn bản bất kỳ</label>
                    <textarea id="qrText_plain" oninput="renderCustomQr()" placeholder="Nhập nội dung tin nhắn, ghi chú..." class="w-full h-24 p-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs"></textarea>
                </div>
            </div>

            <!-- Custom Styling & Logo -->
            <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">2. Tùy Biến Màu Sắc & Logo</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">Màu mã QR (Foreground)</label>
                        <div class="flex items-center gap-3">
                            <input type="color" id="qrFgColor" value="#1e1b4b" onchange="renderCustomQr()" class="w-10 h-10 rounded-lg cursor-pointer border-0 bg-transparent p-0">
                            <span id="fgColorHex" class="font-mono text-xs font-semibold text-slate-600 dark:text-slate-300">#1e1b4b</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">Màu nền (Background)</label>
                        <div class="flex items-center gap-3">
                            <input type="color" id="qrBgColor" value="#ffffff" onchange="renderCustomQr()" class="w-10 h-10 rounded-lg cursor-pointer border-0 bg-transparent p-0">
                            <span id="bgColorHex" class="font-mono text-xs font-semibold text-slate-600 dark:text-slate-300">#ffffff</span>
                        </div>
                    </div>
                </div>

                <!-- Logo Upload -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">Chèn Logo ở giữa mã QR (Tùy chọn)</label>
                    <div class="flex items-center gap-3">
                        <input type="file" id="logoInput" accept="image/*" class="hidden" onchange="handleLogoUpload(this.files[0])">
                        <button type="button" onclick="document.getElementById('logoInput').click()" class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold flex items-center gap-2">
                            <i data-lucide="image" class="w-4 h-4"></i> Chọn file Logo
                        </button>
                        <button type="button" id="btnRemoveLogo" onclick="removeLogo()" class="hidden text-xs text-rose-500 hover:underline">
                            Xóa logo
                        </button>
                    </div>
                </div>
            </div>

            <x-ad-banner slot="in_tool" />

        </div>

        <!-- QR Code Live Output Column (1 col) -->
        <div class="space-y-6">
            <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-center sticky top-24">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-4">Xem Trước Mã QR</span>

                <!-- QR Canvas Container -->
                <div class="w-64 h-64 mx-auto p-4 rounded-2xl bg-white shadow-lg border border-slate-100 flex items-center justify-center mb-6">
                    <canvas id="qrCanvas" class="max-w-full max-h-full"></canvas>
                </div>

                <!-- Download Buttons -->
                <div class="space-y-2">
                    <button type="button" onclick="downloadQr('png')" class="w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
                        <i data-lucide="download" class="w-4 h-4"></i>
                        <span>Tải Ảnh QR (PNG Chất Lượng Cao)</span>
                    </button>
                    <button type="button" onclick="copyQrImage()" class="w-full py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-semibold transition">
                        Sao chép ảnh QR
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- SEO Content: How To & FAQ -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-slate-200 dark:border-slate-800 pt-10">
        <div>
            <h2 class="text-base font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <i data-lucide="book-open" class="w-4 h-4 text-indigo-600"></i> Hướng Dẫn Sử Dụng
            </h2>
            <ol class="space-y-3 text-xs text-slate-600 dark:text-slate-400 list-decimal list-inside leading-relaxed">
                @foreach($tool['how_to'] ?? [] as $step)
                    <li>{{ $step }}</li>
                @endforeach
            </ol>
        </div>

        <div>
            <h2 class="text-base font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <i data-lucide="help-circle" class="w-4 h-4 text-indigo-600"></i> Câu Hỏi Thường Gặp
            </h2>
            <div class="space-y-3">
                @foreach($tool['faq'] ?? [] as $faq)
                    <div class="p-3.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                        <h3 class="font-bold text-xs text-slate-800 dark:text-slate-200 mb-1">{{ $faq['q'] }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $faq['a'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

<!-- Hidden container for qrcode.js generation -->
<div id="hiddenQr" class="hidden"></div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    let activeQrType = 'url';
    let centerLogoImg = null;

    function setQrType(type) {
        activeQrType = type;
        ['url', 'wifi', 'vcard', 'text'].forEach(t => {
            document.getElementById(`qrInput_${t}`).classList.add('hidden');
            document.getElementById(`qrTab_${t}`).className = 'p-3 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-semibold flex flex-col items-center gap-1.5 hover:bg-slate-50 dark:hover:bg-slate-800 transition';
        });

        document.getElementById(`qrInput_${type}`).classList.remove('hidden');
        document.getElementById(`qrTab_${type}`).className = 'p-3 rounded-xl border border-indigo-500 bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-300 text-xs font-bold flex flex-col items-center gap-1.5 transition';

        renderCustomQr();
    }

    function getQrPayload() {
        if (activeQrType === 'url') {
            return document.getElementById('qrText_url').value || 'https://example.com';
        } else if (activeQrType === 'wifi') {
            const ssid = document.getElementById('wifiSsid').value || 'FreeWiFi';
            const pass = document.getElementById('wifiPass').value || '';
            return `WIFI:T:WPA;S:${ssid};P:${pass};;`;
        } else if (activeQrType === 'vcard') {
            const name = document.getElementById('vcardName').value || 'Nguyễn Văn A';
            const phone = document.getElementById('vcardPhone').value || '';
            const email = document.getElementById('vcardEmail').value || '';
            return `BEGIN:VCARD\nVERSION:3.0\nN:${name}\nFN:${name}\nTEL:${phone}\nEMAIL:${email}\nEND:VCARD`;
        } else {
            return document.getElementById('qrText_plain').value || 'ZiiTool';
        }
    }

    function handleLogoUpload(file) {
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            const img = new Image();
            img.onload = () => {
                centerLogoImg = img;
                document.getElementById('btnRemoveLogo').classList.remove('hidden');
                renderCustomQr();
                showToast('Đã chèn logo vào giữa mã QR!', 'success');
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    function removeLogo() {
        centerLogoImg = null;
        document.getElementById('logoInput').value = '';
        document.getElementById('btnRemoveLogo').classList.add('hidden');
        renderCustomQr();
    }

    function renderCustomQr() {
        const payload = getQrPayload();
        const fgColor = document.getElementById('qrFgColor').value;
        const bgColor = document.getElementById('qrBgColor').value;
        document.getElementById('fgColorHex').innerText = fgColor;
        document.getElementById('bgColorHex').innerText = bgColor;

        const hiddenEl = document.getElementById('hiddenQr');
        hiddenEl.innerHTML = '';

        // Generate base QR with high error correction (H) to support center logo
        new QRCode(hiddenEl, {
            text: payload,
            width: 320,
            height: 320,
            colorDark: fgColor,
            colorLight: bgColor,
            correctLevel: QRCode.CorrectLevel.H
        });

        setTimeout(() => {
            const rawCanvas = hiddenEl.querySelector('canvas');
            if (!rawCanvas) return;

            const targetCanvas = document.getElementById('qrCanvas');
            targetCanvas.width = 320;
            targetCanvas.height = 320;
            const ctx = targetCanvas.getContext('2d');
            ctx.drawImage(rawCanvas, 0, 0);

            // Draw center logo if uploaded
            if (centerLogoImg) {
                const logoSize = 64;
                const x = (320 - logoSize) / 2;
                const y = (320 - logoSize) / 2;

                // Draw background white circle/rect for logo
                ctx.fillStyle = bgColor;
                ctx.beginPath();
                ctx.roundRect(x - 4, y - 4, logoSize + 8, logoSize + 8, 12);
                ctx.fill();

                ctx.drawImage(centerLogoImg, x, y, logoSize, logoSize);
            }
        }, 80);
    }

    function downloadQr(format) {
        const canvas = document.getElementById('qrCanvas');
        const a = document.createElement('a');
        a.href = canvas.toDataURL('image/png');
        a.download = `QR-Code-${Date.now()}.png`;
        a.click();
        showToast('Đã tải xuống mã QR!', 'success');
    }

    async function copyQrImage() {
        const canvas = document.getElementById('qrCanvas');
        canvas.toBlob((blob) => {
            try {
                navigator.clipboard.write([new ClipboardItem({ 'image/png': blob })]);
                showToast('Đã sao chép ảnh QR vào bộ nhớ tạm!', 'success');
            } catch (e) {
                showToast('Trình duyệt chưa hỗ trợ sao chép ảnh trực tiếp.', 'error');
            }
        });
    }

    window.addEventListener('DOMContentLoaded', () => {
        setTimeout(renderCustomQr, 200);
    });
</script>
@endpush
@endsection

