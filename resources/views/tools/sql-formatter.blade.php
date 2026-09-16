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
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300 font-semibold">
                Chuẩn Hóa ANSI SQL
            </span>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 font-semibold">
                MySQL • Postgres • SQLite
            </span>
        </div>
        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 max-w-3xl">
            {{ $tool['short_desc'] }} Tự động ngắt dòng và căn lề các mệnh đề SQL phức tạp, giúp câu lệnh sáng sủa và dễ bảo trì.
        </p>
    </div>

    <!-- Action Toolbar -->
    <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-3 mb-6">
        <div class="flex flex-wrap items-center gap-2">
            <button type="button" onclick="formatSql()" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                <span>Format SQL (Làm đẹp)</span>
            </button>
            <button type="button" onclick="minifySql()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                <i data-lucide="minimize-2" class="w-3.5 h-3.5"></i>
                <span>Minify (Nén 1 dòng)</span>
            </button>
            <button type="button" onclick="loadSampleSql()" class="px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs text-slate-600 dark:text-slate-300 font-medium transition">
                Câu lệnh mẫu
            </button>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" onclick="copySqlOutput()" class="px-3 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition flex items-center gap-1.5">
                <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                <span>Sao Chép</span>
            </button>
            <button type="button" onclick="clearSql()" class="p-2 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-400 hover:text-rose-500 transition">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <!-- Dual Editor Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-12">
        <div class="space-y-2">
            <div class="flex items-center justify-between text-xs text-slate-500">
                <span class="font-bold text-slate-700 dark:text-slate-300">SQL Đầu Vào</span>
                <span id="sqlInStats" class="font-mono text-[11px]">0 dòng</span>
            </div>
            <textarea id="sqlInput" oninput="updateSqlStats()" placeholder="Dán câu truy vấn SQL vào đây..." class="w-full h-96 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 font-mono text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none resize-y"></textarea>
        </div>

        <div class="space-y-2">
            <div class="flex items-center justify-between text-xs text-slate-500">
                <span class="font-bold text-indigo-600 dark:text-indigo-400">SQL Đã Chuẩn Hóa</span>
                <span id="sqlOutStats" class="font-mono text-[11px]">0 dòng</span>
            </div>
            <textarea id="sqlOutput" readonly placeholder="Kết quả SQL chuẩn hóa sẽ hiển thị tại đây..." class="w-full h-96 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/60 text-indigo-600 dark:text-indigo-400 font-mono text-xs focus:outline-none resize-y"></textarea>
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
    function updateSqlStats() {
        const val = document.getElementById('sqlInput').value;
        const lines = val ? val.split('\n').length : 0;
        document.getElementById('sqlInStats').innerText = `${lines} dòng • ${val.length} ký tự`;
    }

    function formatSql() {
        const input = document.getElementById('sqlInput').value.trim();
        if (!input) {
            showToast('Vui lòng nhập câu lệnh SQL!', 'error');
            return;
        }

        // Clean extra whitespaces
        let sql = input.replace(/\s+/g, ' ');

        // Major clauses to put on new line with uppercase
        const majorKeywords = [
            'SELECT', 'FROM', 'WHERE', 'GROUP BY', 'HAVING', 'ORDER BY', 
            'LIMIT', 'OFFSET', 'LEFT JOIN', 'RIGHT JOIN', 'INNER JOIN', 
            'OUTER JOIN', 'CROSS JOIN', 'JOIN', 'UNION ALL', 'UNION',
            'INSERT INTO', 'VALUES', 'UPDATE', 'SET', 'DELETE FROM'
        ];

        // Minor clauses indented
        const minorKeywords = ['AND', 'OR', 'ON'];

        majorKeywords.forEach(kw => {
            const regex = new RegExp('\\b' + kw + '\\b', 'gi');
            sql = sql.replace(regex, '\n' + kw);
        });

        minorKeywords.forEach(kw => {
            const regex = new RegExp('\\b' + kw + '\\b', 'gi');
            sql = sql.replace(regex, '\n  ' + kw);
        });

        // Format comma in SELECT
        sql = sql.replace(/,\s*/g, ',\n  ');

        // Capitalize common SQL functions & keywords
        const keywordsToUpper = ['AS', 'IN', 'NOT IN', 'IS NULL', 'IS NOT NULL', 'LIKE', 'BETWEEN', 'COUNT', 'SUM', 'AVG', 'MAX', 'MIN', 'COALESCE', 'DISTINCT', 'CASE', 'WHEN', 'THEN', 'ELSE', 'END'];
        keywordsToUpper.forEach(kw => {
            const regex = new RegExp('\\b' + kw + '\\b', 'gi');
            sql = sql.replace(regex, kw);
        });

        const formatted = sql.trim();
        document.getElementById('sqlOutput').value = formatted;
        const lines = formatted.split('\n').length;
        document.getElementById('sqlOutStats').innerText = `${lines} dòng • ${formatted.length} ký tự`;
        showToast('Đã format SQL thành công!', 'success');
    }

    function minifySql() {
        const input = document.getElementById('sqlInput').value.trim();
        if (!input) return;
        const minified = input.replace(/\s+/g, ' ').replace(/\s*([,;()=])\s*/g, '$1');
        document.getElementById('sqlOutput').value = minified;
        document.getElementById('sqlOutStats').innerText = `1 dòng • ${minified.length} ký tự`;
        showToast('Đã nén SQL 1 dòng!', 'success');
    }

    function loadSampleSql() {
        const sample = `select u.id, u.name, u.email, count(o.id) as total_orders, sum(o.amount) as total_spent from users u left join orders o on u.id = o.user_id where u.active = 1 and o.status in ('completed', 'shipped') group by u.id, u.name, u.email having count(o.id) > 5 order by total_spent desc limit 20;`;
        document.getElementById('sqlInput').value = sample;
        updateSqlStats();
        formatSql();
    }

    function copySqlOutput() {
        const out = document.getElementById('sqlOutput').value;
        if (!out) {
            showToast('Chưa có kết quả để sao chép!', 'error');
            return;
        }
        copyText(out, 'Đã sao chép câu lệnh SQL!');
    }

    function clearSql() {
        document.getElementById('sqlInput').value = '';
        document.getElementById('sqlOutput').value = '';
        document.getElementById('sqlInStats').innerText = '0 dòng';
        document.getElementById('sqlOutStats').innerText = '0 dòng';
        showToast('Đã xóa dữ liệu!', 'info');
    }
</script>
@endpush
@endsection
