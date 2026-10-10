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
                <span
                    class="text-xs px-2.5 py-0.5 rounded-full bg-purple-100 dark:bg-purple-900/60 text-purple-700 dark:text-purple-300 font-semibold">
                    {{ $tool['badge'] ?? 'Xuất Ảnh 2K Không Watermark' }}
                </span>
                <span
                    class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 font-semibold">
                    Nền Gradient Đẹp Mắt
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 max-w-3xl">
                {{ $tool['short_desc'] }} Biến ảnh chụp màn hình đơn điệu thành ấn phẩm đồ họa chuyên nghiệp dùng cho
                landing page, portfolio và mạng xã hội.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">

            <!-- Controls Column (1 col) -->
            <div class="space-y-6">

                <!-- Upload Area -->
                <div onclick="document.getElementById('mockupFileInput').click()"
                    class="p-6 rounded-2xl border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-purple-500 bg-white dark:bg-slate-900 text-center cursor-pointer transition group">
                    <input type="file" id="mockupFileInput" accept="image/*" class="hidden"
                        onchange="loadMockupImage(this.files[0])">
                    <div
                        class="w-12 h-12 mx-auto mb-2 rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center group-hover:scale-110 transition">
                        <i data-lucide="image-plus" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200">Chọn Ảnh Chụp Màn Hình</h3>
                    <p class="text-[11px] text-slate-400">PNG, JPG, WebP</p>
                </div>

                <!-- Device Selector -->
                <div
                    class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">1. Khung Thiết Bị</h3>

                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" onclick="setDevice('browser')" id="devBtn_browser"
                            class="p-3 rounded-xl border border-purple-500 bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-300 text-xs font-bold flex flex-col items-center gap-1">
                            <i data-lucide="globe" class="w-4 h-4"></i> Safari Window
                        </button>
                        <button type="button" onclick="setDevice('macbook')" id="devBtn_macbook"
                            class="p-3 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-semibold flex flex-col items-center gap-1 hover:bg-slate-50 dark:hover:bg-slate-800">
                            <i data-lucide="laptop" class="w-4 h-4"></i> MacBook Pro
                        </button>
                        <button type="button" onclick="setDevice('iphone')" id="devBtn_iphone"
                            class="p-3 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-semibold flex flex-col items-center gap-1 hover:bg-slate-50 dark:hover:bg-slate-800">
                            <i data-lucide="smartphone" class="w-4 h-4"></i> iPhone 15 Pro
                        </button>
                        <button type="button" onclick="setDevice('ipad')" id="devBtn_ipad"
                            class="p-3 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-semibold flex flex-col items-center gap-1 hover:bg-slate-50 dark:hover:bg-slate-800">
                            <i data-lucide="tablet" class="w-4 h-4"></i> iPad Pro
                        </button>
                    </div>
                </div>

                <!-- Background & Shadow Options -->
                <div
                    class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">2. Màu Nền Gradient</h3>

                    <div class="grid grid-cols-5 gap-2">
                        <button type="button" onclick="setBgGradient('purple')" title="Cosmic Purple"
                            class="h-8 rounded-lg bg-gradient-to-tr from-indigo-600 to-purple-500 ring-2 ring-purple-600"></button>
                        <button type="button" onclick="setBgGradient('sunset')" title="Sunset Glow"
                            class="h-8 rounded-lg bg-gradient-to-tr from-amber-500 to-rose-500"></button>
                        <button type="button" onclick="setBgGradient('ocean')" title="Ocean Blue"
                            class="h-8 rounded-lg bg-gradient-to-tr from-cyan-500 to-blue-600"></button>
                        <button type="button" onclick="setBgGradient('dark')" title="Midnight Minimal"
                            class="h-8 rounded-lg bg-gradient-to-tr from-slate-900 to-slate-800"></button>
                        <button type="button" onclick="setBgGradient('transparent')" title="Trong Suốt (PNG)"
                            class="h-8 rounded-lg border border-slate-300 dark:border-slate-700 flex items-center justify-center text-[10px] font-bold text-slate-500">PNG</button>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            <span>Khoảng đệm (Padding)</span>
                            <span id="padLabel">60px</span>
                        </div>
                        <input type="range" id="mockupPad" min="20" max="120" value="60"
                            oninput="document.getElementById('padLabel').innerText = this.value + 'px'; renderMockupCanvas()"
                            class="w-full accent-purple-600">
                    </div>
                </div>

                <!-- Download Button -->
                <button type="button" onclick="downloadMockupImage()"
                    class="w-full py-3.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs sm:text-sm shadow-md transition flex items-center justify-center gap-2">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    <span>Tải Ảnh Mockup (2K HD)</span>
                </button>

                <x-ad-banner placement="sidebar" />

            </div>

            <!-- Live Mockup Canvas Preview (2 cols) -->
            <div class="lg:col-span-2 space-y-6">
                <div
                    class="p-4 sm:p-8 rounded-3xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center overflow-hidden min-h-[500px]">
                    <canvas id="mockupCanvas" class="max-w-full h-auto rounded-xl shadow-2xl"></canvas>
                </div>

                <x-ad-banner placement="in_tool" />
            </div>

        </div>

        <!-- SEO Content: How To & FAQ -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-slate-200 dark:border-slate-800 pt-10">
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-lucide="book-open" class="w-4 h-4 text-indigo-600"></i> Hướng Dẫn Sử Dụng
                </h2>
                <ol class="space-y-3 text-xs text-slate-600 dark:text-slate-400 list-decimal list-inside leading-relaxed">
                    @foreach ($tool['how_to'] ?? [] as $step)
                        <li>{{ $step }}</li>
                    @endforeach
                </ol>
            </div>

            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-lucide="help-circle" class="w-4 h-4 text-indigo-600"></i> Câu Hỏi Thường Gặp
                </h2>
                <div class="space-y-3">
                    @foreach ($tool['faq'] ?? [] as $faq)
                        <div
                            class="p-3.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                            <h3 class="font-bold text-xs text-slate-800 dark:text-slate-200 mb-1">{{ $faq['q'] }}</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $faq['a'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            let activeDevice = 'browser';
            let activeGradient = 'purple';
            let uploadedScreenshot = null;

            function setDevice(dev) {
                activeDevice = dev;
                ['browser', 'macbook', 'iphone', 'ipad'].forEach(d => {
                    document.getElementById(`devBtn_${d}`).className =
                        'p-3 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-semibold flex flex-col items-center gap-1 hover:bg-slate-50 dark:hover:bg-slate-800';
                });
                document.getElementById(`devBtn_${dev}`).className =
                    'p-3 rounded-xl border border-purple-500 bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-300 text-xs font-bold flex flex-col items-center gap-1';
                renderMockupCanvas();
            }

            function setBgGradient(grad) {
                activeGradient = grad;
                renderMockupCanvas();
            }

            function loadMockupImage(file) {
                if (!file || !file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = new Image();
                    img.onload = () => {
                        uploadedScreenshot = img;
                        renderMockupCanvas();
                        showToast('Đã nạp ảnh màn hình vào mockup!', 'success');
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }

            function renderMockupCanvas() {
                const canvas = document.getElementById('mockupCanvas');
                const ctx = canvas.getContext('2d');
                const pad = parseInt(document.getElementById('mockupPad').value) || 60;

                // Base dimensions (HD 1600x1000)
                canvas.width = 1600;
                canvas.height = 1000;

                // 1. Draw Background
                if (activeGradient === 'purple') {
                    const grad = ctx.createLinearGradient(0, 0, canvas.width, canvas.height);
                    grad.addColorStop(0, '#4f46e5');
                    grad.addColorStop(0.5, '#7c3aed');
                    grad.addColorStop(1, '#db2777');
                    ctx.fillStyle = grad;
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                } else if (activeGradient === 'sunset') {
                    const grad = ctx.createLinearGradient(0, 0, canvas.width, canvas.height);
                    grad.addColorStop(0, '#f59e0b');
                    grad.addColorStop(1, '#f43f5e');
                    ctx.fillStyle = grad;
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                } else if (activeGradient === 'ocean') {
                    const grad = ctx.createLinearGradient(0, 0, canvas.width, canvas.height);
                    grad.addColorStop(0, '#06b6d4');
                    grad.addColorStop(1, '#2563eb');
                    ctx.fillStyle = grad;
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                } else if (activeGradient === 'dark') {
                    const grad = ctx.createLinearGradient(0, 0, canvas.width, canvas.height);
                    grad.addColorStop(0, '#0f172a');
                    grad.addColorStop(1, '#1e293b');
                    ctx.fillStyle = grad;
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                } else {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                }

                // 2. Device Render
                const frameW = canvas.width - (pad * 2);
                const frameH = canvas.height - (pad * 2);
                const frameX = pad;
                const frameY = pad;

                ctx.save();
                // Drop shadow for the device
                ctx.shadowColor = 'rgba(0, 0, 0, 0.45)';
                ctx.shadowBlur = 40;
                ctx.shadowOffsetY = 20;

                if (activeDevice === 'browser') {
                    // Safari Browser Frame
                    const headerH = 48;
                    ctx.fillStyle = '#ffffff';
                    ctx.beginPath();
                    ctx.roundRect(frameX, frameY, frameW, frameH, 16);
                    ctx.fill();

                    // Header bar
                    ctx.shadowColor = 'transparent';
                    ctx.fillStyle = '#f1f5f9';
                    ctx.beginPath();
                    ctx.roundRect(frameX, frameY, frameW, headerH, [16, 16, 0, 0]);
                    ctx.fill();

                    // Traffic dots
                    ctx.fillStyle = '#ef4444';
                    ctx.beginPath();
                    ctx.arc(frameX + 24, frameY + 24, 7, 0, Math.PI * 2);
                    ctx.fill();
                    ctx.fillStyle = '#f59e0b';
                    ctx.beginPath();
                    ctx.arc(frameX + 44, frameY + 24, 7, 0, Math.PI * 2);
                    ctx.fill();
                    ctx.fillStyle = '#10b981';
                    ctx.beginPath();
                    ctx.arc(frameX + 64, frameY + 24, 7, 0, Math.PI * 2);
                    ctx.fill();

                    // Address bar
                    ctx.fillStyle = '#ffffff';
                    ctx.beginPath();
                    ctx.roundRect(frameX + 100, frameY + 12, frameW - 200, 24, 6);
                    ctx.fill();

                    // Draw image inside viewport
                    const viewX = frameX;
                    const viewY = frameY + headerH;
                    const viewW = frameW;
                    const viewH = frameH - headerH;

                    if (uploadedScreenshot) {
                        ctx.drawImage(uploadedScreenshot, viewX, viewY, viewW, viewH);
                    } else {
                        drawPlaceholderContent(ctx, viewX, viewY, viewW, viewH);
                    }
                } else if (activeDevice === 'iphone') {
                    // iPhone frame
                    const phoneW = 420;
                    const phoneH = 860;
                    const phoneX = (canvas.width - phoneW) / 2;
                    const phoneY = (canvas.height - phoneH) / 2;

                    // Outer Titanium bezel
                    ctx.fillStyle = '#1e293b';
                    ctx.beginPath();
                    ctx.roundRect(phoneX, phoneY, phoneW, phoneH, 50);
                    ctx.fill();

                    // Screen area
                    const screenX = phoneX + 12;
                    const screenY = phoneY + 12;
                    const screenW = phoneW - 24;
                    const screenH = phoneH - 24;

                    ctx.shadowColor = 'transparent';
                    ctx.fillStyle = '#0f172a';
                    ctx.beginPath();
                    ctx.roundRect(screenX, screenY, screenW, screenH, 40);
                    ctx.fill();

                    // Draw Image inside screen
                    ctx.save();
                    ctx.beginPath();
                    ctx.roundRect(screenX, screenY, screenW, screenH, 40);
                    ctx.clip();
                    if (uploadedScreenshot) {
                        ctx.drawImage(uploadedScreenshot, screenX, screenY, screenW, screenH);
                    } else {
                        drawPlaceholderContent(ctx, screenX, screenY, screenW, screenH);
                    }
                    ctx.restore();

                    // Dynamic Island
                    ctx.fillStyle = '#000000';
                    ctx.beginPath();
                    ctx.roundRect(phoneX + (phoneW - 110) / 2, screenY + 14, 110, 26, 13);
                    ctx.fill();
                } else {
                    // MacBook / iPad Default Canvas Render
                    ctx.fillStyle = '#0f172a';
                    ctx.beginPath();
                    ctx.roundRect(frameX, frameY, frameW, frameH, 20);
                    ctx.fill();

                    ctx.shadowColor = 'transparent';
                    const screenX = frameX + 16;
                    const screenY = frameY + 16;
                    const screenW = frameW - 32;
                    const screenH = frameH - 32;

                    if (uploadedScreenshot) {
                        ctx.drawImage(uploadedScreenshot, screenX, screenY, screenW, screenH);
                    } else {
                        drawPlaceholderContent(ctx, screenX, screenY, screenW, screenH);
                    }
                }
                ctx.restore();
            }

            function drawPlaceholderContent(ctx, x, y, w, h) {
                ctx.fillStyle = '#1e293b';
                ctx.fillRect(x, y, w, h);
                ctx.fillStyle = '#94a3b8';
                ctx.font = 'bold 24px "Plus Jakarta Sans", sans-serif';
                ctx.textAlign = 'center';
                ctx.fillText('Chọn hoặc kéo thả ảnh chụp màn hình vào đây', x + w / 2, y + h / 2);
            }

            function downloadMockupImage() {
                const canvas = document.getElementById('mockupCanvas');
                const a = document.createElement('a');
                a.href = canvas.toDataURL('image/png');
                a.download = `Mockup-${activeDevice}-${Date.now()}.png`;
                a.click();
                showToast('Đã tải ảnh Mockup 2K thành công!', 'success');
            }

            window.addEventListener('DOMContentLoaded', () => {
                renderMockupCanvas();
            });
        </script>
    @endpush
@endsection
