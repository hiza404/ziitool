@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-indigo-600">Trang chủ</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>{{ $categoryInfo['name'] ?? 'Developer' }}</span>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="text-slate-800 dark:text-slate-200 font-semibold">{{ $tool['title'] }}</span>
    </nav>

    <!-- Tool Header -->
    <div class="mb-8">
        <div class="flex flex-wrap items-center gap-2 mb-2">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">
                {{ $tool['title'] }}
            </h1>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 font-semibold">
                Kiểm Tra Cú Pháp Tự Động
            </span>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 font-semibold">
                100% Client-Side
            </span>
        </div>
        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 max-w-3xl">
            {{ $tool['short_desc'] }} Soát lỗi cú pháp JSON tức thì, thụt lề chuẩn và nén dữ liệu cho API.
        </p>
    </div>

    <!-- Action Toolbar -->
    <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-3 mb-6">
        <div class="flex flex-wrap items-center gap-2">
            <button type="button" onclick="formatJson()" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                <span>Beautify (Làm đẹp)</span>
            </button>
            <button type="button" onclick="minifyJson()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                <i data-lucide="minimize-2" class="w-3.5 h-3.5"></i>
                <span>Minify (Nén 1 dòng)</span>
            </button>
            <select id="indentOption" class="px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-300">
                <option value="2">Thụt lề 2 spaces</option>
                <option value="4">Thụt lề 4 spaces</option>
                <option value="tab">Thụt lề Tab</option>
            </select>
            <button type="button" onclick="loadSampleJson()" class="px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs text-slate-600 dark:text-slate-300 font-medium transition">
                Dữ liệu mẫu
            </button>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" onclick="copyOutput()" class="px-3 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition flex items-center gap-1.5">
                <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                <span>Copy Kết Quả</span>
            </button>
            <button type="button" onclick="downloadJsonFile()" class="px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs text-slate-600 dark:text-slate-300 font-medium transition flex items-center gap-1.5">
                <i data-lucide="download" class="w-3.5 h-3.5"></i>
                <span>Tải .json</span>
            </button>
            <button type="button" onclick="clearJson()" class="p-2 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-400 hover:text-rose-500 transition">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <!-- Status Message Alert (Hidden by default) -->
    <div id="jsonStatusBox" class="hidden p-3.5 rounded-xl mb-6 text-xs font-mono flex items-center gap-2"></div>

    <!-- Dual Editor Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-12">
        <!-- Input Editor -->
        <div class="space-y-2">
            <div class="flex items-center justify-between text-xs text-slate-500">
                <span class="font-bold text-slate-700 dark:text-slate-300">JSON Đầu Vào (Input)</span>
                <span id="inputStats" class="font-mono text-[11px]">0 ký tự</span>
            </div>
            <textarea id="jsonInput" oninput="updateInputStats()" placeholder="Dán chuỗi JSON thô vào đây..." class="w-full h-96 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 font-mono text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none resize-y"></textarea>
        </div>

        <!-- Output Editor -->
        <div class="space-y-2">
            <div class="flex items-center justify-between text-xs text-slate-500">
                <span class="font-bold text-emerald-600 dark:text-emerald-400">Kết Quả Đã Xử Lý (Output)</span>
                <span id="outputStats" class="font-mono text-[11px]">0 ký tự</span>
            </div>
            <textarea id="jsonOutput" readonly placeholder="Kết quả định dạng sẽ hiển thị tại đây..." class="w-full h-96 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/60 text-emerald-600 dark:text-emerald-400 font-mono text-xs focus:outline-none resize-y"></textarea>
        </div>
    </div>

    <!-- In-Tool Ad Banner -->
    <x-ad-banner slot="in_tool" class="mb-12" />

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

@push('scripts')
<script>
    function updateInputStats() {
        const val = document.getElementById('jsonInput').value;
        document.getElementById('inputStats').innerText = `${val.length} ký tự`;
    }

    function formatJson() {
        const input = document.getElementById('jsonInput').value.trim();
        const statusBox = document.getElementById('jsonStatusBox');
        if (!input) {
            showToast('Vui lòng nhập chuỗi JSON!', 'error');
            return;
        }

        try {
            const parsed = JSON.parse(input);
            const indentChoice = document.getElementById('indentOption').value;
            const indent = indentChoice === 'tab' ? '\t' : parseInt(indentChoice);
            const formatted = JSON.stringify(parsed, null, indent);

            document.getElementById('jsonOutput').value = formatted;
            document.getElementById('outputStats').innerText = `${formatted.length} ký tự`;

            statusBox.className = 'p-3.5 rounded-xl mb-6 text-xs font-mono flex items-center gap-2 bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800';
            statusBox.innerHTML = `<span>✓ Cú pháp JSON hợp lệ 100%! Đã định dạng thành công.</span>`;
            statusBox.classList.remove('hidden');
            showToast('Format JSON thành công!', 'success');
        } catch (err) {
            statusBox.className = 'p-3.5 rounded-xl mb-6 text-xs font-mono flex items-center gap-2 bg-rose-50 text-rose-700 dark:bg-rose-950/50 dark:text-rose-300 border border-rose-200 dark:border-rose-800';
            statusBox.innerHTML = `<span>✕ Lỗi cú pháp JSON: ${err.message}</span>`;
            statusBox.classList.remove('hidden');
            showToast('JSON chứa lỗi cú pháp!', 'error');
        }
    }

    function minifyJson() {
        const input = document.getElementById('jsonInput').value.trim();
        const statusBox = document.getElementById('jsonStatusBox');
        if (!input) return;

        try {
            const parsed = JSON.parse(input);
            const minified = JSON.stringify(parsed);
            document.getElementById('jsonOutput').value = minified;
            document.getElementById('outputStats').innerText = `${minified.length} ký tự (Nén 1 dòng)`;

            statusBox.className = 'p-3.5 rounded-xl mb-6 text-xs font-mono flex items-center gap-2 bg-indigo-50 text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800';
            statusBox.innerHTML = `<span>✓ Minify thành công! Chuỗi đã nén tối đa 1 dòng.</span>`;
            statusBox.classList.remove('hidden');
            showToast('Nén JSON thành công!', 'success');
        } catch (err) {
            statusBox.className = 'p-3.5 rounded-xl mb-6 text-xs font-mono flex items-center gap-2 bg-rose-50 text-rose-700 border border-rose-200';
            statusBox.innerHTML = `<span>✕ Lỗi cú pháp JSON: ${err.message}</span>`;
            statusBox.classList.remove('hidden');
        }
    }

    function loadSampleJson() {
        const sample = {
            "appName": "ZiiTool",
            "version": "2.0.0",
            "features": [
                "100% Client-side Processing",
                "VietQR Pro Activation",
                "High CTR AdSense Ready"
            ],
            "settings": {
                "serverCost": 0,
                "isPrivate": true,
                "toolsCount": 12
            }
        };
        document.getElementById('jsonInput').value = JSON.stringify(sample);
        updateInputStats();
        formatJson();
    }

    function copyOutput() {
        const out = document.getElementById('jsonOutput').value;
        if (!out) {
            showToast('Chưa có kết quả để sao chép!', 'error');
            return;
        }
        copyText(out, 'Đã sao chép JSON đã xử lý!');
    }

    function downloadJsonFile() {
        const out = document.getElementById('jsonOutput').value;
        if (!out) return;
        const blob = new Blob([out], { type: 'application/json' });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = `formatted-${Date.now()}.json`;
        a.click();
        showToast('Đã tải xuống file .json!', 'success');
    }

    function clearJson() {
        document.getElementById('jsonInput').value = '';
        document.getElementById('jsonOutput').value = '';
        document.getElementById('inputStats').innerText = '0 ký tự';
        document.getElementById('outputStats').innerText = '0 ký tự';
        document.getElementById('jsonStatusBox').classList.add('hidden');
        showToast('Đã xóa dữ liệu!', 'info');
    }
</script>
@endpush
@endsection

