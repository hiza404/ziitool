<!DOCTYPE html>
<html lang="vi" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập Quản Trị - ZiiTool</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="h-full bg-slate-950 text-slate-100 font-sans flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        <!-- Logo Brand -->
        <div class="text-center mb-8">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 to-pink-500 flex items-center justify-center font-black text-white text-2xl mx-auto shadow-lg shadow-indigo-500/30 mb-3">
                ⚡
            </div>
            <h1 class="text-xl font-bold text-white tracking-tight">ZiiTool Quản Trị</h1>
            <p class="text-xs text-slate-400">Đăng nhập vào hệ thống quản trị website</p>
        </div>

        <!-- Login Card -->
        <div class="p-8 rounded-3xl bg-slate-900 border border-slate-800 shadow-2xl space-y-6">

            @if(session('error'))
                <div class="p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs font-semibold flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-semibold flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Email Quản Trị</label>
                    <div class="relative flex items-center">
                        <i data-lucide="mail" class="w-4 h-4 text-slate-500 absolute left-3.5 pointer-events-none"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="admin@example.com" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-700 bg-slate-800 text-white text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Mật Khẩu</label>
                    <div class="relative flex items-center">
                        <i data-lucide="lock" class="w-4 h-4 text-slate-500 absolute left-3.5 pointer-events-none"></i>
                        <input type="password" name="password" required placeholder="••••••••" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-700 bg-slate-800 text-white text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 text-slate-400 cursor-pointer">
                        <input type="checkbox" name="remember" checked class="rounded text-indigo-600 focus:ring-indigo-500 bg-slate-800 border-slate-700">
                        <span>Ghi nhớ phiên đăng nhập</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-lg shadow-indigo-500/20 transition flex items-center justify-center gap-2">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    <span>Đăng Nhập Quản Trị</span>
                </button>
            </form>

            <div class="text-center pt-2">
                <a href="{{ route('home') }}" class="text-xs text-slate-500 hover:text-white transition flex items-center justify-center gap-1">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Quay lại trang chủ người dùng</span>
                </a>
            </div>

        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>

