<!DOCTYPE html>
<html lang="vi" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - MicroTools Hub</title>

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
                    }
                }
            }
        }
    </script>
    <script src="https://unpkg.com/lucide@latest"></script>
    @stack('styles')
</head>
<body class="h-full bg-slate-900 text-slate-100 font-sans antialiased flex overflow-hidden">

    <!-- Sidebar Navigation -->
    <aside class="w-64 bg-slate-950 border-r border-slate-800 flex flex-col justify-between shrink-0 h-full">
        <div>
            <!-- Brand -->
            <div class="h-16 flex items-center px-6 border-b border-slate-800/80 gap-3">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-indigo-600 to-pink-500 flex items-center justify-center font-black text-white text-base shadow-md shadow-indigo-500/20">
                    ⚡
                </div>
                <div>
                    <span class="font-bold text-sm text-white block leading-none">MicroTools</span>
                    <span class="text-[10px] text-indigo-400 font-semibold uppercase tracking-wider">Trang Quản Trị</span>
                </div>
            </div>

            <!-- Nav Links -->
            <nav class="p-4 space-y-1.5 text-xs font-medium">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    <span>Tổng Quan (Dashboard)</span>
                </a>

                <a href="{{ route('admin.tools.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.tools.*') ? 'bg-indigo-600 text-white font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                    <i data-lucide="wrench" class="w-4 h-4"></i>
                    <span>Quản Lý Công Cụ (12)</span>
                </a>

                <a href="{{ route('admin.adsense.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.adsense.*') ? 'bg-indigo-600 text-white font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                    <i data-lucide="layout" class="w-4 h-4"></i>
                    <span>Quảng Cáo Google AdSense</span>
                </a>

                <a href="{{ route('admin.vietqr.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.vietqr.*') ? 'bg-indigo-600 text-white font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                    <i data-lucide="qr-code" class="w-4 h-4"></i>
                    <span>VietQR, Đơn Hàng & Pro Key</span>
                </a>

                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-600 text-white font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                    <i data-lucide="settings" class="w-4 h-4"></i>
                    <span>Cấu Hình SEO & Hệ Thống</span>
                </a>
            </nav>
        </div>

        <!-- User & Public Site Link -->
        <div class="p-4 border-t border-slate-800/80 space-y-2">
            <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs text-slate-400 hover:text-white hover:bg-slate-900 transition">
                <span class="flex items-center gap-2">
                    <i data-lucide="external-link" class="w-4 h-4 text-emerald-400"></i>
                    <span>Xem Website Public</span>
                </span>
                <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i>
            </a>

            <div class="pt-2 flex items-center justify-between px-2">
                <div class="flex items-center gap-2 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 flex items-center justify-center font-bold text-xs">
                        AD
                    </div>
                    <div class="min-w-0">
                        <span class="text-xs font-bold text-white block truncate">{{ auth()->user()->name ?? 'Admin' }}</span>
                        <span class="text-[10px] text-slate-500 block truncate">{{ auth()->user()->email ?? '' }}</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" title="Đăng xuất" class="p-2 text-slate-400 hover:text-rose-400 transition">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Workspace -->
    <div class="flex-1 flex flex-col min-w-0 h-full overflow-y-auto bg-slate-900">
        
        <!-- Top bar -->
        <header class="h-16 bg-slate-950/60 backdrop-blur-md border-b border-slate-800/80 flex items-center justify-between px-6 shrink-0 sticky top-0 z-20">
            <div class="flex items-center gap-3">
                <h2 class="text-base font-bold text-white">@yield('title', 'Admin Dashboard')</h2>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.settings.clear_cache') }}" onclick="event.preventDefault(); document.getElementById('formClearCache').submit();" class="px-3 py-1.5 rounded-lg border border-slate-700 hover:bg-slate-800 text-xs font-semibold text-slate-300 flex items-center gap-1.5 transition">
                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                    <span>Xóa Cache</span>
                </a>
                <form id="formClearCache" action="{{ route('admin.settings.clear_cache') }}" method="POST" class="hidden">@csrf</form>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-semibold flex items-center justify-between mb-4">
                    <span class="flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        {{ session('success') }}
                    </span>
                    <button onclick="this.parentElement.remove()" class="text-emerald-400/60 hover:text-emerald-400"><i data-lucide="x" class="w-4 h-4"></i></button>
                </div>
            @endif

            @if(session('error'))
                <div class="p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs font-semibold flex items-center justify-between mb-4">
                    <span class="flex items-center gap-2">
                        <i data-lucide="alert-circle" class="w-4 h-4"></i>
                        {{ session('error') }}
                    </span>
                    <button onclick="this.parentElement.remove()" class="text-rose-400/60 hover:text-rose-400"><i data-lucide="x" class="w-4 h-4"></i></button>
                </div>
            @endif
        </div>

        <!-- Page Body Content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    <script>
        lucide.createIcons();

        function copyText(text, msg = 'Đã sao chép vào bộ nhớ tạm!') {
            navigator.clipboard.writeText(text).then(() => {
                alert(msg);
            });
        }
    </script>
    @stack('scripts')
</body>
</html>

