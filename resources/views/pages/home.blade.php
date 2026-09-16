@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    <!-- Top Leaderboard Ad Banner -->
    <x-ad-banner slot="top_leaderboard" class="mb-8" />

    <!-- Hero Section -->
    <div class="text-center max-w-3xl mx-auto mb-12">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200 dark:border-indigo-800/50 text-indigo-700 dark:text-indigo-300 text-xs font-semibold mb-4">
            <span class="w-2 h-2 rounded-full bg-indigo-500 animate-ping"></span>
            <span>Nền tảng Micro-Tools v2.0 • Hoàn toàn Miễn phí</span>
        </div>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight sm:leading-tight mb-4">
            Công Cụ Tiện Ích Trực Tuyến <br class="hidden sm:inline">
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
                Siêu Tốc, Bảo Mật & 0đ Chi Phí
            </span>
        </h1>
        <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400 leading-relaxed mb-8">
            Xử lý toàn bộ trên trình duyệt của bạn (Client-Side). Tệp tin không bao giờ rời khỏi thiết bị, không cần đăng ký tài khoản, tự động hóa 100%.
        </p>

        <!-- Search Bar Input on Hero -->
        <div class="relative max-w-xl mx-auto mb-6">
            <div class="relative flex items-center">
                <i data-lucide="search" class="w-5 h-5 text-slate-400 absolute left-4 pointer-events-none"></i>
                <input type="text" id="heroSearchInput" oninput="filterToolsOnPage(this.value)" placeholder="Gõ tên công cụ cần tìm (vd: nén ảnh, json, thuế tncn, mã qr...)" class="w-full pl-12 pr-10 py-3.5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-lg shadow-indigo-500/5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                <button type="button" onclick="clearHeroSearch()" id="btnClearSearch" class="hidden absolute right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <!-- Quick Tag Badges -->
        <div class="flex flex-wrap items-center justify-center gap-2 text-xs text-slate-500 dark:text-slate-400">
            <span class="font-medium text-slate-400 dark:text-slate-500">Tìm kiếm nhanh:</span>
            <button onclick="setSearchFilter('nén ảnh')" class="px-2.5 py-1 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-indigo-400 transition">Nén ảnh</button>
            <button onclick="setSearchFilter('json')" class="px-2.5 py-1 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-indigo-400 transition">JSON</button>
            <button onclick="setSearchFilter('thuế tncn')" class="px-2.5 py-1 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-indigo-400 transition">Thuế TNCN</button>
            <button onclick="setSearchFilter('mã qr')" class="px-2.5 py-1 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-indigo-400 transition">Mã QR</button>
            <button onclick="setSearchFilter('lãi kép')" class="px-2.5 py-1 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-indigo-400 transition">Lãi kép</button>
        </div>
    </div>

    <!-- Category Filter Tabs -->
    <div class="flex items-center justify-center gap-2 overflow-x-auto pb-4 mb-8 no-scrollbar">
        <a href="{{ route('home') }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition whitespace-nowrap {{ empty($selectedCategory) ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
            Tất cả ({{ count($allTools) }})
        </a>
        @foreach($categories as $catKey => $cat)
            @php
                $catCount = count(array_filter($allTools, fn($t) => $t['category'] === $catKey));
            @endphp
            <a href="{{ route('home', ['category' => $catKey]) }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition whitespace-nowrap {{ ($selectedCategory === $catKey) ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                {{ $cat['name'] }} ({{ $catCount }})
            </a>
        @endforeach
    </div>

    <!-- Tools Grid -->
    <div id="toolsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 mb-16">
        @foreach($tools as $tool)
            <a href="{{ route('tool.show', ['slug' => $tool['slug']]) }}" class="tool-card group relative p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 hover:border-indigo-500/50 dark:hover:border-indigo-500/50 hover:shadow-xl hover:shadow-indigo-500/5 transition-all flex flex-col justify-between" data-title="{{ strtolower($tool['title']) }}" data-desc="{{ strtolower($tool['short_desc']) }}" data-keywords="{{ strtolower($tool['keywords'] ?? '') }}">
                <div>
                    <!-- Card Top: Icon & Badge -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-sm">
                            <i data-lucide="{{ $tool['icon'] ?? 'wrench' }}" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 group-hover:bg-indigo-50 group-hover:text-indigo-600 dark:group-hover:bg-indigo-900/40 dark:group-hover:text-indigo-300 transition-colors">
                            {{ $tool['badge'] ?? 'Tiện ích' }}
                        </span>
                    </div>

                    <!-- Title & Description -->
                    <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors mb-2">
                        {{ $tool['title'] }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-2 mb-4">
                        {{ $tool['short_desc'] }}
                    </p>
                </div>

                <!-- Card Bottom: Link Action -->
                <div class="pt-3 border-t border-slate-100 dark:border-slate-800/60 flex items-center justify-between text-xs font-semibold text-indigo-600 dark:text-indigo-400">
                    <span>Sử dụng ngay</span>
                    <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>
        @endforeach
    </div>

    <!-- In-page Mid Ad Banner -->
    <x-ad-banner slot="in_tool" class="mb-16" />

    <!-- Highlights & Feature Badges -->
    <div class="py-12 border-y border-slate-200 dark:border-slate-800 my-16">
        <div class="max-w-4xl mx-auto text-center mb-10">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">
                Vì Sao MicroTools Đạt Hiệu Suất Tối Đa?
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                Kiến trúc hiện đại kết hợp sức mạnh phần cứng máy tính người dùng và hạ tầng Laravel siêu nhẹ.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold mb-4">
                    🔒
                </div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-2">Bảo Mật Quyền Riêng Tư 100%</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                    Ảnh, dữ liệu JSON, câu lệnh SQL được xử lý cục bộ trên RAM trình duyệt của bạn qua HTML5 Canvas và Web Crypto. Không lưu trữ tệp lên máy chủ.
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold mb-4">
                    ⚡
                </div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-2">Tốc Độ Tức Thì, Không Độ Trễ</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                    Không mất thời gian upload file lên đám mây rồi chờ tải về. Tác vụ nén ảnh, mã hóa hoặc format code hoàn thành ngay trong nháy mắt.
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold mb-4">
                    💸
                </div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-2">Chi Phí Vận Hành 0đ</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                    Không tiêu tốn tài nguyên CPU/RAM máy chủ cho tác vụ nặng. Mô hình dễ dàng mở rộng phục vụ hàng triệu người dùng mà không phát sinh chi phí server.
                </p>
            </div>
        </div>
    </div>

    <!-- SEO FAQ Section -->
    <div class="max-w-3xl mx-auto my-12">
        <h2 class="text-xl font-bold text-slate-900 dark:text-white text-center mb-6">
            Câu Hỏi Thường Gặp (FAQ)
        </h2>
        <div class="space-y-4">
            <div class="p-5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-2">Các công cụ tại ZiiTool có hoàn toàn miễn phí không?</h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                    Có! Toàn bộ 12 công cụ trên hệ thống đều miễn phí sử dụng 100%, không giới hạn số lần thực hiện. Bạn cũng có thể đăng ký gói Pro để tắt hoàn toàn banner quảng cáo và nhận mã API token cho developer.
                </p>
            </div>
            <div class="p-5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-2">Làm thế nào để tích hợp API vào phần mềm của tôi?</h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                    Truy cập trang <a href="{{ route('api.docs') }}" class="text-indigo-600 dark:text-indigo-400 font-semibold underline">Tài Liệu REST API</a> để xem ví dụ mẫu bằng cURL, JavaScript và PHP để gọi các endpoint tính thuế, tính lãi kép, mã băm và tạo mã QR.
                </p>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    function filterToolsOnPage(query) {
        const q = (query || '').toLowerCase().trim();
        const cards = document.querySelectorAll('.tool-card');
        const clearBtn = document.getElementById('btnClearSearch');

        if (q.length > 0) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }

        cards.forEach(card => {
            const title = card.getAttribute('data-title') || '';
            const desc = card.getAttribute('data-desc') || '';
            const kw = card.getAttribute('data-keywords') || '';

            if (title.includes(q) || desc.includes(q) || kw.includes(q)) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
    }

    function clearHeroSearch() {
        const input = document.getElementById('heroSearchInput');
        input.value = '';
        filterToolsOnPage('');
        input.focus();
    }

    function setSearchFilter(text) {
        const input = document.getElementById('heroSearchInput');
        input.value = text;
        filterToolsOnPage(text);
        input.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
</script>
@endpush
@endsection

