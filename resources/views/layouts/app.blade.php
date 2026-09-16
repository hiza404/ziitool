<!DOCTYPE html>
<html lang="vi" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>{{ $seo['title'] ?? 'MicroTools Hub - Web Tiện Ích Miễn Phí 100%' }}</title>
    <meta name="description" content="{{ $seo['description'] ?? 'Tập hợp các công cụ tiện ích trực tuyến tốt nhất: Nén ảnh, chuyển đổi WebP, JSON formatter, tính thuế TNCN, lãi kép, tạo mã QR.' }}">
    <meta name="keywords" content="{{ $seo['keywords'] ?? 'web tiện ích, micro tools, nén ảnh, json formatter' }}">
    <link rel="canonical" href="{{ $seo['canonical'] ?? url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $seo['canonical'] ?? url()->current() }}">
    <meta property="og:title" content="{{ $seo['title'] ?? 'MicroTools Hub' }}">
    <meta property="og:description" content="{{ $seo['description'] ?? '' }}">
    <meta property="og:site_name" content="MicroTools Hub">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo['title'] ?? 'MicroTools Hub' }}">
    <meta name="twitter:description" content="{{ $seo['description'] ?? '' }}">

    <!-- Schema.org JSON-LD Structured Data -->
    @if(!empty($seo['schema']))
        @foreach($seo['schema'] as $schemaItem)
            <script type="application/ld+json">
                {!! json_encode($schemaItem, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
            </script>
        @endforeach
    @endif

    <!-- Fonts & Tailwind CSS CDN -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Theme script to prevent flash of wrong theme -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Lucide Icons & Libraries -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    @stack('styles')
    <style>
        /* Custom scrollbars */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .dark ::-webkit-scrollbar-thumb { background: #334155; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .dark ::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
</head>
<body class="h-full bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 font-sans antialiased flex flex-col selection:bg-indigo-500 selection:text-white">

    @php
        $announcement = \App\Models\Setting::get('announcement_banner');
    @endphp
    @if(!empty($announcement))
        <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white text-xs font-semibold py-2 px-4 text-center tracking-wide flex items-center justify-center gap-2">
            <span>{{ $announcement }}</span>
        </div>
    @endif

    <!-- Header Navigation -->
    <header class="sticky top-0 z-40 w-full backdrop-blur-md bg-white/80 dark:bg-slate-900/80 border-b border-slate-200 dark:border-slate-800 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            
            <!-- Logo & Brand -->
            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-cyan-400 flex items-center justify-center text-white font-black text-lg shadow-md shadow-indigo-500/20 group-hover:scale-105 transition-transform">
                        ⚡
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-lg leading-tight tracking-tight text-slate-900 dark:text-white flex items-center gap-1.5">
                            MicroTools<span class="text-xs px-1.5 py-0.5 rounded bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300 font-semibold">Hub</span>
                        </span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-medium tracking-wide">Chi phí 0đ • Tự động 100%</span>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-1 text-sm font-medium text-slate-600 dark:text-slate-300">
                    <a href="{{ route('home') }}" class="px-3 py-1.5 rounded-lg hover:text-slate-900 hover:bg-slate-100 dark:hover:text-white dark:hover:bg-slate-800 transition">
                        Tất cả công cụ
                    </a>
                    <a href="{{ route('pricing') }}" class="px-3 py-1.5 rounded-lg hover:text-slate-900 hover:bg-slate-100 dark:hover:text-white dark:hover:bg-slate-800 transition flex items-center gap-1.5 text-amber-600 dark:text-amber-400 font-semibold">
                        <span>⚡ Gói Pro</span>
                        <span class="text-[10px] bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300 px-1.5 py-0.2 rounded-full">VietQR</span>
                    </a>
                    <a href="{{ route('api.docs') }}" class="px-3 py-1.5 rounded-lg hover:text-slate-900 hover:bg-slate-100 dark:hover:text-white dark:hover:bg-slate-800 transition">
                        REST API
                    </a>
                </nav>
            </div>

            <!-- Quick Search Bar & Action Buttons -->
            <div class="flex items-center gap-2 sm:gap-3">
                
                <!-- Quick Search Trigger -->
                <button onclick="openSearchModal()" type="button" class="flex items-center gap-2 px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-100/70 dark:bg-slate-900/60 text-slate-500 dark:text-slate-400 text-xs sm:text-sm hover:border-slate-300 dark:hover:border-slate-700 transition w-36 sm:w-56 justify-between">
                    <span class="flex items-center gap-1.5">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        <span class="hidden sm:inline">Tìm công cụ...</span>
                        <span class="sm:hidden">Tìm...</span>
                    </span>
                    <kbd class="hidden sm:inline-block px-1.5 py-0.5 text-[10px] font-mono bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded shadow-xs">Ctrl K</kbd>
                </button>

                <!-- Pro Status or Upgrade Button -->
                @if(session('is_pro_member'))
                    <span class="hidden sm:flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> Pro Member
                    </span>
                @else
                    <button onclick="openLicenseModal()" type="button" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white shadow-sm transition">
                        <i data-lucide="key" class="w-3.5 h-3.5"></i> Nhập Key Pro
                    </button>
                @endif

                <!-- Dark / Light Mode Toggle -->
                <button onclick="toggleTheme()" type="button" class="p-2 rounded-xl border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition" aria-label="Toggle theme">
                    <i data-lucide="sun" class="w-4 h-4 hidden dark:block"></i>
                    <i data-lucide="moon" class="w-4 h-4 block dark:hidden"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-20 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/60 py-12 text-slate-600 dark:text-slate-400 text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-7 h-7 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                            ⚡
                        </div>
                        <span class="font-bold text-slate-900 dark:text-white text-base">MicroTools Hub</span>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-500 dark:text-slate-400 max-w-md mb-4">
                        Nền tảng công cụ trực tuyến 100% Client-Side. Dữ liệu của bạn được tính toán và xử lý trực tiếp trên trình duyệt, không bao giờ gửi về máy chủ, đảm bảo tốc độ tối đa và quyền riêng tư tuyệt đối.
                    </p>
                    <div class="flex items-center gap-2 text-xs">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/40 font-medium">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> 100% Client-Side Private
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800/40 font-medium">
                            0đ Server Load
                        </span>
                    </div>
                </div>

                <div>
                    <h3 class="font-semibold text-slate-900 dark:text-slate-200 text-xs uppercase tracking-wider mb-3">Danh mục</h3>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('home', ['category' => 'image']) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">Xử lý Ảnh & Tệp</a></li>
                        <li><a href="{{ route('home', ['category' => 'dev']) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">Developer & Lập trình</a></li>
                        <li><a href="{{ route('home', ['category' => 'finance']) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">Tài chính & Văn phòng</a></li>
                        <li><a href="{{ route('home', ['category' => 'graphics']) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">Đồ họa & Mockup</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-slate-900 dark:text-slate-200 text-xs uppercase tracking-wider mb-3">Hệ thống</h3>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('pricing') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">Bảng giá Gói Pro (VietQR)</a></li>
                        <li><a href="{{ route('api.docs') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">Tài liệu REST API</a></li>
                        <li><a href="{{ route('sitemap') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400" target="_blank">Sitemap.xml</a></li>
                        <li><a href="{{ route('robots') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400" target="_blank">Robots.txt</a></li>
                        <li><a href="{{ route('admin.login') }}" class="hover:text-amber-500 flex items-center gap-1 font-semibold text-amber-600 dark:text-amber-400"><i data-lucide="shield" class="w-3 h-3"></i> Quản trị (Admin)</a></li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p>© {{ date('Y') }} MicroTools Hub. Phát triển cho cộng đồng lập trình & văn phòng.</p>
                <div class="flex items-center gap-4">
                    <span>Phiên bản v2.0 (PHP 8.4 / Laravel 12)</span>
                    <button onclick="openLicenseModal()" class="text-amber-500 hover:underline">Kích hoạt Bản quyền Pro</button>
                </div>
            </div>
        </div>
    </footer>

    <!-- Search Modal -->
    <div id="searchModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm p-4 overflow-y-auto flex items-start justify-center pt-20">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-xl shadow-2xl overflow-hidden transition-all">
            <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3">
                <i data-lucide="search" class="w-5 h-5 text-slate-400"></i>
                <input id="searchInput" type="text" placeholder="Tìm công cụ (vd: nén ảnh, json, thuế tncn, qr code...)" class="w-full bg-transparent border-none text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none text-base" oninput="filterSearchTools()">
                <button onclick="closeSearchModal()" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div id="searchResults" class="p-2 max-h-80 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800/50">
                <!-- Search Items dynamically filled by JS -->
            </div>
        </div>
    </div>

    <!-- Pro License Activation Modal -->
    <div id="licenseModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm p-4 overflow-y-auto flex items-center justify-center">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-md shadow-2xl p-6 relative">
            <button onclick="closeLicenseModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            
            <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-4">
                <i data-lucide="key" class="w-6 h-6"></i>
            </div>
            
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">Kích Hoạt Bản Quyền Pro</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">Nhập mã bản quyền nhận được sau khi thanh toán VietQR để tắt quảng cáo và mở khóa quyền lợi Pro.</p>

            <form onsubmit="submitLicenseCode(event)" class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Mã License Pro</label>
                    <input id="licenseCodeInput" type="text" placeholder="Ví dụ: PRO-SUPER-2026" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white font-mono uppercase text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none">
                </div>
                <div class="text-[11px] text-slate-400">
                    💡 Mã thử nghiệm: <span class="font-mono text-amber-500 font-semibold cursor-pointer" onclick="fillDemoKey('PRO-SUPER-2026')">PRO-SUPER-2026</span>
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="submit" id="btnSubmitLicense" class="flex-1 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold text-sm rounded-xl shadow transition">
                        Xác Nhận Kích Hoạt
                    </button>
                    <a href="{{ route('pricing') }}" class="px-4 py-2.5 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-medium text-sm rounded-xl text-center transition">
                        Mua Key
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toastContainer" class="fixed bottom-4 right-4 z-50 flex flex-col gap-2 max-w-sm pointer-events-none"></div>

    <!-- Global App Scripts -->
    <script>
        // Init Lucide Icons
        lucide.createIcons();

        // Theme Toggle
        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
            lucide.createIcons();
        }

        // Tools Data for Quick Search
        const allRegisteredTools = @json(array_values(config('tools.list', [])));

        function openSearchModal() {
            document.getElementById('searchModal').classList.remove('hidden');
            setTimeout(() => document.getElementById('searchInput').focus(), 50);
            filterSearchTools();
        }

        function closeSearchModal() {
            document.getElementById('searchModal').classList.add('hidden');
        }

        function filterSearchTools() {
            const query = (document.getElementById('searchInput').value || '').toLowerCase().trim();
            const container = document.getElementById('searchResults');
            
            const matches = allRegisteredTools.filter(t => 
                t.title.toLowerCase().includes(query) || 
                t.short_desc.toLowerCase().includes(query) || 
                (t.keywords && t.keywords.toLowerCase().includes(query))
            );

            if (matches.length === 0) {
                container.innerHTML = `<div class="p-6 text-center text-xs text-slate-400">Không tìm thấy công cụ nào phù hợp với từ khóa "${query}".</div>`;
                return;
            }

            container.innerHTML = matches.map(t => `
                <a href="/tool/${t.slug}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800/80 transition group">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-sm">
                        ⚡
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 flex items-center gap-2">
                            ${t.title}
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">${t.badge}</span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">${t.short_desc}</p>
                    </div>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400 group-hover:translate-x-0.5 transition"></i>
                </a>
            `).join('');

            lucide.createIcons();
        }

        // Shortcut Ctrl+K
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                openSearchModal();
            }
            if (e.key === 'Escape') {
                closeSearchModal();
                closeLicenseModal();
            }
        });

        // License Modal
        function openLicenseModal() {
            document.getElementById('licenseModal').classList.remove('hidden');
        }
        function closeLicenseModal() {
            document.getElementById('licenseModal').classList.add('hidden');
        }
        function fillDemoKey(key) {
            document.getElementById('licenseCodeInput').value = key;
        }

        async function submitLicenseCode(e) {
            e.preventDefault();
            const code = document.getElementById('licenseCodeInput').value;
            const btn = document.getElementById('btnSubmitLicense');
            btn.disabled = true;
            btn.innerText = 'Đang kiểm tra...';

            try {
                const res = await fetch('{{ route("payment.verify_license") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ license_code: code })
                });
                const data = await res.json();
                if (data.success) {
                    showToast(data.message, 'success');
                    closeLicenseModal();
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showToast(data.message, 'error');
                }
            } catch (err) {
                showToast('Lỗi kết nối máy chủ khi xác thực license.', 'error');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Xác Nhận Kích Hoạt';
            }
        }

        // Toast Helper
        function showToast(message, type = 'info') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            const bgClass = type === 'success' ? 'bg-emerald-600 text-white' : (type === 'error' ? 'bg-rose-600 text-white' : 'bg-slate-900 dark:bg-white text-white dark:text-slate-900');
            
            toast.className = `px-4 py-3 rounded-xl shadow-lg font-medium text-xs flex items-center gap-2 pointer-events-auto transition-all transform translate-y-2 opacity-0 duration-300 ${bgClass}`;
            toast.innerHTML = `<span>${message}</span>`;
            container.appendChild(toast);

            setTimeout(() => toast.classList.remove('translate-y-2', 'opacity-0'), 10);
            setTimeout(() => {
                toast.classList.add('translate-y-2', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3500);
        }

        // Copy Text Helper
        function copyText(text, successMessage = 'Đã sao chép vào bộ nhớ tạm!') {
            navigator.clipboard.writeText(text).then(() => {
                showToast(successMessage, 'success');
            }).catch(() => {
                showToast('Không thể sao chép!', 'error');
            });
        }
    </script>
    @stack('scripts')
</body>
</html>

