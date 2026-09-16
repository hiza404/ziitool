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
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-cyan-100 dark:bg-cyan-900/60 text-cyan-700 dark:text-cyan-300 font-semibold">
                Tối Ưu Tốc Độ Web
            </span>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 font-semibold">
                CSS3 & Media Queries
            </span>
        </div>
        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 max-w-3xl">
            {{ $tool['short_desc'] }} Tối ưu hóa file CSS cho website của bạn, loại bỏ comment và khoảng trắng thừa, đo lường dung lượng tiết kiệm.
        </p>
    </div>

    <!-- Action Toolbar -->
    <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-3 mb-6">
        <div class="flex flex-wrap items-center gap-2">
            <button type="button" onclick="beautifyCss()" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                <span>Beautify CSS (Làm đẹp)</span>
            </button>
            <button type="button" onclick="minifyCss()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                <i data-lucide="minimize-2" class="w-3.5 h-3.5"></i>
                <span>Minify CSS (Nén siêu sạch)</span>
            </button>
            <button type="button" onclick="loadSampleCss()" class="px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs text-slate-600 dark:text-slate-300 font-medium transition">
                CSS mẫu
            </button>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" onclick="copyCssOutput()" class="px-3 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition flex items-center gap-1.5">
                <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                <span>Sao Chép</span>
            </button>
            <button type="button" onclick="downloadCssFile()" class="px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs text-slate-600 dark:text-slate-300 font-medium transition flex items-center gap-1.5">
                <i data-lucide="download" class="w-3.5 h-3.5"></i>
                <span>Tải .css</span>
            </button>
            <button type="button" onclick="clearCss()" class="p-2 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-400 hover:text-rose-500 transition">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <!-- Dual Editor Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-12">
        <div class="space-y-2">
            <div class="flex items-center justify-between text-xs text-slate-500">
                <span class="font-bold text-slate-700 dark:text-slate-300">CSS Gốc</span>
                <span id="cssInStats" class="font-mono text-[11px]">0 bytes</span>
            </div>
            <textarea id="cssInput" oninput="updateCssStats()" placeholder="Dán mã CSS vào đây..." class="w-full h-96 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 font-mono text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none resize-y"></textarea>
        </div>

        <div class="space-y-2">
            <div class="flex items-center justify-between text-xs text-slate-500">
                <span class="font-bold text-cyan-600 dark:text-cyan-400">CSS Kết Quả</span>
                <span id="cssOutStats" class="font-mono text-[11px]">0 bytes</span>
            </div>
            <textarea id="cssOutput" readonly placeholder="Kết quả CSS định dạng sẽ xuất hiện tại đây..." class="w-full h-96 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/60 text-cyan-600 dark:text-cyan-400 font-mono text-xs focus:outline-none resize-y"></textarea>
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
    function updateCssStats() {
        const val = document.getElementById('cssInput').value;
        document.getElementById('cssInStats').innerText = `${val.length} ký tự`;
    }

    function beautifyCss() {
        const input = document.getElementById('cssInput').value.trim();
        if (!input) {
            showToast('Vui lòng nhập mã CSS!', 'error');
            return;
        }

        // Clean extra spaces
        let css = input.replace(/\s+/g, ' ');
        // Format braces and properties
        css = css.replace(/\{\s*/g, ' {\n  ');
        css = css.replace(/;\s*/g, ';\n  ');
        css = css.replace(/\s*\}\s*/g, '\n}\n\n');
        css = css.replace(/:\s*/g, ': ');

        // Clean trailing spaces before closing braces
        css = css.replace(/  \n\}/g, '}');

        document.getElementById('cssOutput').value = css.trim();
        document.getElementById('cssOutStats').innerText = `${css.trim().length} ký tự (Beautified)`;
        showToast('Đã làm đẹp CSS thành công!', 'success');
    }

    function minifyCss() {
        const input = document.getElementById('cssInput').value.trim();
        if (!input) return;

        // Strip comments
        let css = input.replace(/\/\*[\s\S]*?\*\//g, '');
        // Collapse whitespaces
        css = css.replace(/\s+/g, ' ');
        // Strip spaces around symbols
        css = css.replace(/\s*([{}:;,>~+])\s*/g, '$1');
        // Remove trailing semicolons inside braces
        css = css.replace(/;}/g, '}');

        const minified = css.trim();
        document.getElementById('cssOutput').value = minified;

        const saved = Math.max(0, Math.round(((input.length - minified.length) / input.length) * 100));
        document.getElementById('cssOutStats').innerText = `${minified.length} ký tự (-${saved}% dung lượng)`;
        showToast(`Nén CSS thành công! Tiết kiệm ${saved}% dung lượng`, 'success');
    }

    function loadSampleCss() {
        const sample = `/* MicroTools App Stylesheet */
.btn-primary {
  background-color: #4f46e5;
  color: #ffffff;
  padding: 10px 20px;
  border-radius: 8px;
  font-weight: 600;
  transition: all 0.2s ease-in-out;
}

.btn-primary:hover {
  background-color: #4338ca;
  box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
}

@media (max-width: 768px) {
  .btn-primary {
    width: 100%;
    text-align: center;
  }
}`;
        document.getElementById('cssInput').value = sample;
        updateCssStats();
        beautifyCss();
    }

    function copyCssOutput() {
        const out = document.getElementById('cssOutput').value;
        if (!out) {
            showToast('Chưa có kết quả để sao chép!', 'error');
            return;
        }
        copyText(out, 'Đã sao chép mã CSS!');
    }

    function downloadCssFile() {
        const out = document.getElementById('cssOutput').value;
        if (!out) return;
        const blob = new Blob([out], { type: 'text/css' });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = `styles-${Date.now()}.css`;
        a.click();
        showToast('Đã tải xuống file .css!', 'success');
    }

    function clearCss() {
        document.getElementById('cssInput').value = '';
        document.getElementById('cssOutput').value = '';
        document.getElementById('cssInStats').innerText = '0 bytes';
        document.getElementById('cssOutStats').innerText = '0 bytes';
        showToast('Đã xóa dữ liệu!', 'info');
    }
</script>
@endpush
@endsection

