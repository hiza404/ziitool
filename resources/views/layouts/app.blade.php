<!DOCTYPE html>
<html lang="vi" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon & Mobile Touch Icons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <meta name="theme-color" content="#4f46e5">

    <!-- SEO Meta Tags & Crawlers -->
    <title>{{ $seo['title'] ?? 'ZiiTool - Web Tiện Ích Miễn Phí 100%' }}</title>
    <meta name="description" content="{{ $seo['description'] ?? 'Tập hợp các công cụ tiện ích trực tuyến tốt nhất: Nén ảnh, chuyển đổi WebP, JSON formatter, tính thuế TNCN, lãi kép, tạo mã QR.' }}">
    <meta name="keywords" content="{{ $seo['keywords'] ?? 'web tiện ích, micro tools, ziitool, snaptik, tải video tiktok, nén ảnh, json formatter' }}">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="bingbot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <link rel="canonical" href="{{ $seo['canonical'] ?? url()->current() }}">

    <!-- Multilingual Alternate Links for Search Engines -->
    <link rel="alternate" hreflang="vi" href="{{ url()->current() }}?lang=vi">
    <link rel="alternate" hreflang="en" href="{{ url()->current() }}?lang=en">
    <link rel="alternate" hreflang="x-default" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $seo['canonical'] ?? url()->current() }}">
    <meta property="og:title" content="{{ $seo['title'] ?? 'ZiiTool' }}">
    <meta property="og:description" content="{{ $seo['description'] ?? '' }}">
    <meta property="og:site_name" content="ZiiTool">
    <meta property="og:locale" content="{{ app()->getLocale() === 'en' ? 'en_US' : 'vi_VN' }}">
    <meta property="og:image" content="{{ $seo['image'] ?? asset('favicon.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ $seo['title'] ?? 'ZiiTool' }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo['title'] ?? 'ZiiTool' }}">
    <meta name="twitter:description" content="{{ $seo['description'] ?? '' }}">
    <meta name="twitter:image" content="{{ $seo['image'] ?? asset('favicon.png') }}">

    <!-- Google AdSense Live Script -->
    @php
        $adsEnabled = \App\Models\Setting::get('ads_enabled', config('ads.enabled', '1')) == '1';
        $adsenseClientId = \App\Models\Setting::get('adsense_client_id', config('ads.client_id'));
        $demoMode = \App\Models\Setting::get('ads_demo_mode', config('ads.demo_mode', '1')) == '1';
    @endphp
    @if($adsEnabled && !$demoMode && !empty($adsenseClientId))
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ $adsenseClientId }}" crossorigin="anonymous"></script>
    @endif

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
                            {{ \App\Models\Setting::get('site_name', 'ZiiTool') }}<span class="text-[10px] px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 font-bold">{{ __('100% Miễn Phí') }}</span>
                        </span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-medium tracking-wide hidden sm:block">{{ \App\Models\Setting::get('site_tagline') ?: __('Miễn phí 100% • Không cần đăng nhập') }}</span>
                    </div>
                </a>

                <!-- Desktop Navigation Links (Tablet Landscape & Desktop) -->
                <nav class="hidden md:flex items-center gap-1 text-sm font-medium text-slate-600 dark:text-slate-300">
                    <a href="{{ route('home') }}" class="px-3 py-1.5 rounded-lg hover:text-slate-900 hover:bg-slate-100 dark:hover:text-white dark:hover:bg-slate-800 transition">
                        {{ __('Tất cả công cụ') }}
                    </a>
                    <a href="{{ route('api.docs') }}" class="px-3 py-1.5 rounded-lg hover:text-slate-900 hover:bg-slate-100 dark:hover:text-white dark:hover:bg-slate-800 transition">
                        {{ __('REST API') }}
                    </a>
                    <a href="https://ziigames.online" target="_blank" rel="noopener noreferrer" class="px-3 py-1.5 rounded-lg hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 transition flex items-center gap-1.5 font-semibold text-indigo-600 dark:text-indigo-400">
                        <i data-lucide="gamepad-2" class="w-4 h-4"></i>
                        <span>ziigames.online</span>
                    </a>
                </nav>
            </div>

            <!-- Quick Search Bar & Action Buttons -->
            <div class="flex items-center gap-1.5 sm:gap-2.5">
                
                <!-- Quick Search Trigger -->
                <button onclick="openSearchModal()" type="button" class="flex items-center gap-2 px-2.5 py-1.5 sm:px-3 sm:py-1.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-100/70 dark:bg-slate-900/60 text-slate-500 dark:text-slate-400 text-xs sm:text-sm hover:border-slate-300 dark:hover:border-slate-700 transition w-auto sm:w-48 justify-between" title="{{ __('Tìm công cụ...') }}">
                    <span class="flex items-center gap-1.5">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        <span class="hidden sm:inline">{{ __('Tìm công cụ...') }}</span>
                    </span>
                    <kbd class="hidden sm:inline-block px-1.5 py-0.5 text-[10px] font-mono bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded shadow-xs">Ctrl K</kbd>
                </button>

                <!-- Language Switcher Pill (VI / EN) -->
                <div class="flex items-center p-0.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-100/80 dark:bg-slate-900/80 text-xs font-semibold">
                    <a href="{{ route('lang.switch', ['locale' => 'vi']) }}" 
                       class="px-2 py-1 rounded-lg transition {{ app()->getLocale() === 'vi' ? 'bg-white dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 shadow-xs' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}"
                       title="Tiếng Việt">
                        🇻🇳 VI
                    </a>
                    <a href="{{ route('lang.switch', ['locale' => 'en']) }}" 
                       class="px-2 py-1 rounded-lg transition {{ app()->getLocale() === 'en' ? 'bg-white dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 shadow-xs' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}"
                       title="English">
                        🇬🇧 EN
                    </a>
                </div>

                <!-- Dark / Light Mode Toggle -->
                <button onclick="toggleTheme()" type="button" class="p-2 rounded-xl border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition" aria-label="Toggle theme">
                    <i data-lucide="sun" class="w-4 h-4 hidden dark:block"></i>
                    <i data-lucide="moon" class="w-4 h-4 block dark:hidden"></i>
                </button>

                <!-- Auth / Logout -->
                @auth
                    <div class="hidden sm:flex items-center gap-2 pl-1 border-l border-slate-200 dark:border-slate-800">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300 max-w-[120px] truncate" title="{{ Auth::user()->name }}">
                            {{ Auth::user()->name }}
                        </span>
                        <form action="{{ route('logout') }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn đăng xuất?');">
                            @csrf
                            <input type="hidden" name="redirect" value="{{ request()->getRequestUri() }}">
                            <button type="submit" class="p-1.5 rounded-lg text-slate-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 dark:hover:text-rose-400 transition" title="{{ __('Đăng xuất') }}">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login', ['redirect' => request()->getRequestUri()]) }}" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition shadow-sm">
                        <i data-lucide="log-in" class="w-3.5 h-3.5"></i>
                        <span>{{ __('Đăng nhập') }}</span>
                    </a>
                @endauth

                <!-- Mobile & Tablet Menu Button (md:hidden) -->
                <button onclick="toggleMobileMenu()" type="button" class="md:hidden p-2 rounded-xl border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition" aria-label="Toggle Navigation Menu">
                    <i data-lucide="menu" id="iconMenuBars" class="w-4 h-4"></i>
                    <i data-lucide="x" id="iconMenuClose" class="w-4 h-4 hidden"></i>
                </button>
            </div>
        </div>

        <!-- Mobile & Tablet Collapsible Navigation Drawer (md:hidden) -->
        <div id="mobileDrawer" class="hidden md:hidden border-t border-slate-200 dark:border-slate-800 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md px-4 py-3 space-y-1 shadow-lg">
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-sm font-bold">
                    ⚡
                </div>
                <span>{{ __('Tất cả công cụ') }}</span>
            </a>
            <a href="{{ route('api.docs') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <div class="w-8 h-8 rounded-lg bg-cyan-100 dark:bg-cyan-950/60 text-cyan-600 dark:text-cyan-400 flex items-center justify-center">
                    <i data-lucide="code" class="w-4 h-4"></i>
                </div>
                <span>{{ __('REST API') }}</span>
            </a>
            <a href="https://ziigames.online" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 transition">
                <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                    <i data-lucide="gamepad-2" class="w-4 h-4"></i>
                </div>
                <span>ziigames.online ↗</span>
            </a>

            @auth
                <div class="pt-2 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between px-3 py-2">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-200">{{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn đăng xuất?');">
                        @csrf
                        <input type="hidden" name="redirect" value="{{ request()->getRequestUri() }}">
                        <button type="submit" class="text-xs text-rose-600 dark:text-rose-400 font-semibold flex items-center gap-1">
                            <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                            <span>{{ __('Đăng xuất') }}</span>
                        </button>
                    </form>
                </div>
            @else
                <div class="pt-2 border-t border-slate-200 dark:border-slate-800">
                    <a href="{{ route('login', ['redirect' => request()->getRequestUri()]) }}" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition">
                        <i data-lucide="log-in" class="w-4 h-4"></i>
                        <span>{{ __('Đăng nhập / Đăng ký') }}</span>
                    </a>
                </div>
            @endauth
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
                        <span class="font-bold text-slate-900 dark:text-white text-base">{{ \App\Models\Setting::get('site_name', 'ZiiTool') }}</span>
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
                    <h3 class="font-semibold text-slate-900 dark:text-slate-200 text-xs uppercase tracking-wider mb-3">{{ __('Hệ thống') }}</h3>
                    <ul class="space-y-2 text-xs">
                        <li class="flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400 font-medium">
                            <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> {{ __('100% Miễn phí & Không cần đăng nhập') }}
                        </li>
                        <li><a href="{{ route('api.docs') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">{{ __('Tài liệu REST API') }}</a></li>
                        <li><a href="{{ route('sitemap') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400" target="_blank">Sitemap.xml</a></li>
                        <li>
                            <a href="https://ziigames.online" target="_blank" rel="noopener noreferrer" class="hover:text-indigo-600 dark:hover:text-indigo-400 flex items-center gap-1.5 font-semibold text-indigo-600 dark:text-indigo-400">
                                <i data-lucide="gamepad-2" class="w-3.5 h-3.5"></i> ziigames.online ↗
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p>© {{ date('Y') }} {{ \App\Models\Setting::get('site_name', 'ZiiTool') }}. {{ __('Phát triển cho cộng đồng lập trình & văn phòng.') }}</p>
                <div class="flex items-center gap-4">
                    <a href="https://ziigames.online" target="_blank" rel="noopener noreferrer" class="hover:text-indigo-600 dark:hover:text-indigo-400 flex items-center gap-1 font-medium transition">
                        <i data-lucide="gamepad-2" class="w-3.5 h-3.5 text-indigo-500"></i> ziigames.online
                    </a>
                    <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-medium">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> {{ __('Miễn phí vĩnh viễn') }}
                    </span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bottom Sticky Sponsored Ad Banner -->
    <x-ad-banner slot="bottom_sticky" class="fixed bottom-0 left-0 right-0 z-30 !my-0 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-t border-slate-200 dark:border-slate-800 shadow-2xl" />

    <!-- Search Modal -->
    <div id="searchModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm p-4 overflow-y-auto flex items-start justify-center pt-20">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-xl shadow-2xl overflow-hidden transition-all">
            <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3">
                <i data-lucide="search" class="w-5 h-5 text-slate-400"></i>
                <input id="searchInput" type="text" placeholder="{{ __('Tìm công cụ (vd: nén ảnh, json, thuế tncn, qr code...)') }}" class="w-full bg-transparent border-none text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none text-base" oninput="filterSearchTools()">
                <button onclick="closeSearchModal()" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div id="searchResults" class="p-2 max-h-80 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800/50">
                <!-- Search Items dynamically filled by JS -->
            </div>
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

        // Mobile & Tablet Menu Toggle
        function toggleMobileMenu() {
            const drawer = document.getElementById('mobileDrawer');
            const bars = document.getElementById('iconMenuBars');
            const close = document.getElementById('iconMenuClose');
            if (drawer) {
                const isHidden = drawer.classList.contains('hidden');
                drawer.classList.toggle('hidden');
                if (bars && close) {
                    bars.classList.toggle('hidden', isHidden);
                    close.classList.toggle('hidden', !isHidden);
                }
            }
        }

        // Tools Data for Quick Search
        @php
            $searchTools = config('tools.list', []);
            $searchOverrides = \App\Models\ToolOverride::all()->keyBy('slug');
            foreach ($searchTools as $sSlug => &$sTool) {
                if (isset($searchOverrides[$sSlug])) {
                    $sTool['is_active'] = $searchOverrides[$sSlug]->is_active;
                    if (! empty($searchOverrides[$sSlug]->custom_title)) {
                        $sTool['title'] = $searchOverrides[$sSlug]->custom_title;
                    }
                    if (! empty($searchOverrides[$sSlug]->custom_badge)) {
                        $sTool['badge'] = $searchOverrides[$sSlug]->custom_badge;
                    }
                    if (! empty($searchOverrides[$sSlug]->custom_desc)) {
                        $sTool['short_desc'] = $searchOverrides[$sSlug]->custom_desc;
                    }
                } else {
                    $sTool['is_active'] = true;
                }
            }
            unset($sTool);
            $searchTools = array_values(array_filter($searchTools, fn ($t) => ($t['is_active'] ?? true) === true));
        @endphp
        const allRegisteredTools = @json($searchTools);

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
                container.innerHTML = `<div class="p-6 text-center text-xs text-slate-400">{{ __('Không tìm thấy công cụ nào phù hợp.') }}</div>`;
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
            }
        });

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

        // User Menu Dropdown Helper
        function toggleUserDropdown() {
            const dropdown = document.getElementById('userMenuDropdown');
            if (dropdown) {
                dropdown.classList.toggle('hidden');
            }
        }

        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('userMenuWrapper');
            const dropdown = document.getElementById('userMenuDropdown');
            if (wrapper && dropdown && !wrapper.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>

