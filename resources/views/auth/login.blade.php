@extends('layouts.app')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        
        <!-- Brand Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-cyan-400 text-white shadow-lg shadow-indigo-500/25 mb-4">
                <span class="text-2xl font-black">⚡</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                Đăng Nhập Tài Khoản
            </h1>
            <p class="mt-2 text-xs sm:text-sm text-slate-600 dark:text-slate-400">
                Đăng nhập để quản lý mã bản quyền Pro và tiếp tục sử dụng dịch vụ
            </p>
        </div>

        <!-- Login Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xl shadow-slate-200/50 dark:shadow-none">
            
            @if(session('info'))
                <div class="mb-5 p-3.5 rounded-xl bg-blue-50 dark:bg-blue-950/50 border border-blue-200 dark:border-blue-900 text-blue-700 dark:text-blue-300 text-xs flex items-center gap-2">
                    <i data-lucide="info" class="w-4 h-4 shrink-0"></i>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900 text-rose-700 dark:text-rose-300 text-xs flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900 text-rose-700 dark:text-rose-300 text-xs">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(!empty($redirect) && str_contains($redirect, 'pricing'))
                <div class="mb-5 p-3.5 rounded-xl bg-amber-50 dark:bg-amber-950/50 border border-amber-200 dark:border-amber-900 text-amber-800 dark:text-amber-300 text-xs flex items-center gap-2">
                    <i data-lucide="sparkles" class="w-4 h-4 shrink-0 text-amber-500"></i>
                    <span>Bạn cần đăng nhập để tiến hành mua và nhận mã kích hoạt <strong>Gói Pro</strong>.</span>
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
                @csrf
                @if(!empty($redirect))
                    <input type="hidden" name="redirect" value="{{ $redirect }}">
                @endif

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Địa chỉ Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-slate-900 dark:text-white text-xs sm:text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                               placeholder="name@example.com">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Mật khẩu
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </div>
                        <input type="password" id="password" name="password" required
                               class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-slate-900 dark:text-white text-xs sm:text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                               placeholder="••••••••">
                        <button type="button" onclick="togglePasswordVisibility('password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            <i data-lucide="eye" class="w-4 h-4" id="passwordToggleIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-slate-700 dark:bg-slate-800">
                        <span class="text-slate-600 dark:text-slate-400">Ghi nhớ đăng nhập</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-indigo-500/25 transition flex items-center justify-center gap-2">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    <span>Đăng Nhập</span>
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-800 text-center">
                <p class="text-xs text-slate-600 dark:text-slate-400">
                    Chưa có tài khoản?
                    <a href="{{ route('register', ['redirect' => $redirect ?? '']) }}" class="font-bold text-indigo-600 dark:text-indigo-400 hover:underline ml-1">
                        Đăng ký tài khoản mới →
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePasswordVisibility(id) {
        const input = document.getElementById(id);
        if (input.type === 'password') {
            input.type = 'text';
        } else {
            input.type = 'password';
        }
    }
</script>
@endpush
@endsection

