@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-indigo-600">Trang chủ</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>{{ $categoryInfo['name'] ?? 'Tài chính & Văn phòng' }}</span>
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
                {{ $tool['badge'] ?? 'Văn phòng' }}
            </span>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 font-semibold flex items-center gap-1">
                <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> 100% Client-Side Bảo Mật
            </span>
        </div>
        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 max-w-3xl">
            {{ $tool['short_desc'] }} Hỗ trợ nhận diện tự động cấu trúc bảng biểu từ PDF, tệp CSV, dữ liệu JSON hoặc bảng HTML để xuất ra bảng tính Excel XLSX chuẩn xác.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        
        <!-- Main Tool Workspace (2 cols) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Format Selector Tabs -->
            <div class="p-1 rounded-2xl bg-slate-200/70 dark:bg-slate-800 flex flex-wrap text-xs font-semibold">
                <button type="button" onclick="switchSourceTab('pdf')" id="tabBtn_pdf" class="flex-1 min-w-[120px] py-2.5 px-3 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs transition flex items-center justify-center gap-1.5">
                    <i data-lucide="file-text" class="w-3.5 h-3.5 text-rose-500"></i>
                    <span>PDF Bảng Biểu</span>
                </button>
                <button type="button" onclick="switchSourceTab('csv')" id="tabBtn_csv" class="flex-1 min-w-[120px] py-2.5 px-3 rounded-xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition flex items-center justify-center gap-1.5">
                    <i data-lucide="file-spreadsheet" class="w-3.5 h-3.5 text-emerald-500"></i>
                    <span>Tệp CSV / TSV</span>
                </button>
                <button type="button" onclick="switchSourceTab('json')" id="tabBtn_json" class="flex-1 min-w-[120px] py-2.5 px-3 rounded-xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition flex items-center justify-center gap-1.5">
                    <i data-lucide="code" class="w-3.5 h-3.5 text-amber-500"></i>
                    <span>Dữ Liệu JSON</span>
                </button>
                <button type="button" onclick="switchSourceTab('html')" id="tabBtn_html" class="flex-1 min-w-[120px] py-2.5 px-3 rounded-xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition flex items-center justify-center gap-1.5">
                    <i data-lucide="table" class="w-3.5 h-3.5 text-blue-500"></i>
                    <span>HTML Table</span>
                </button>
            </div>

            <!-- Input Panels Container -->
            <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-5">
                
                <!-- 1. PDF Mode Panel -->
                <div id="panel_pdf" class="space-y-4">
                    <div id="dropZonePdf" onclick="document.getElementById('pdfInput').click()" class="p-8 rounded-2xl border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-emerald-500 text-center cursor-pointer transition group">
                        <input type="file" id="pdfInput" accept=".pdf,application/pdf" class="hidden" onchange="handlePdfUpload(this.files)">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                            <i data-lucide="file-up" class="w-6 h-6"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Chọn tệp PDF có bảng số liệu cần chuyển sang Excel</h4>
                        <p class="text-xs text-slate-400 mt-1">Hệ thống sẽ bóc tách các dòng & cột số liệu và tạo bảng tính</p>
                    </div>
                </div>

                <!-- 2. CSV Mode Panel -->
                <div id="panel_csv" class="hidden space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300">Nhập hoặc tải tệp CSV/TSV</label>
                        <button type="button" onclick="document.getElementById('csvInputFile').click()" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1 font-medium">
                            <i data-lucide="upload" class="w-3.5 h-3.5"></i> Tải file .csv từ máy
                        </button>
                        <input type="file" id="csvInputFile" accept=".csv,.tsv,.txt" class="hidden" onchange="handleCsvUpload(this.files)">
                    </div>
                    <textarea id="csvTextInput" rows="6" placeholder="Mã NV, Họ và Tên, Chức Vụ, Lương Cơ Bản, Thưởng
NV001, Nguyễn Văn A, Trưởng Phòng, 25000000, 5000000
NV002, Trần Thị B, Chuyên Viên, 15000000, 2000000
NV003, Lê Văn C, Kế Toán Viên, 12000000, 1500000" class="w-full p-3.5 rounded-2xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white font-mono text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                    <div class="flex items-center gap-3 text-xs">
                        <span class="text-slate-500">Dấu phân cách:</span>
                        <select id="csvDelimiter" class="px-2.5 py-1.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs text-slate-900 dark:text-white">
                            <option value="auto">Tự động nhận diện (, hoặc ; hoặc tab)</option>
                            <option value=",">Dấu phẩy (,)</option>
                            <option value=";">Dấu chấm phẩy (;)</option>
                            <option value="&#9;">Tab (\t)</option>
                        </select>
                    </div>
                </div>

                <!-- 3. JSON Mode Panel -->
                <div id="panel_json" class="hidden space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300">Dán mảng đối tượng JSON (Array of Objects)</label>
                        <button type="button" onclick="loadSampleJson()" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Dán dữ liệu mẫu</button>
                    </div>
                    <textarea id="jsonTextInput" rows="6" placeholder='[
  {"id": 1, "product": "Bàn Phím Cơ", "price": 1200000, "stock": 45, "category": "Phụ kiện"},
  {"id": 2, "product": "Chuột Không Dây", "price": 450000, "stock": 80, "category": "Phụ kiện"},
  {"id": 3, "product": "Tai Nghe Gaming", "price": 890000, "stock": 25, "category": "Âm thanh"}
]' class="w-full p-3.5 rounded-2xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white font-mono text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                </div>

                <!-- 4. HTML Table Mode Panel -->
                <div id="panel_html" class="hidden space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300">Dán mã bảng HTML (&lt;table&gt;...&lt;/table&gt;)</label>
                        <button type="button" onclick="loadSampleHtml()" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Dán bảng mẫu</button>
                    </div>
                    <textarea id="htmlTextInput" rows="6" placeholder="<table>
  <thead>
    <tr><th>STT</th><th>Họ Tên</th><th>Phòng Ban</th><th>Điểm KPI</th></tr>
  </thead>
  <tbody>
    <tr><td>1</td><td>Phạm Quỳnh Chi</td><td>Marketing</td><td>95</td></tr>
    <tr><td>2</td><td>Đỗ Minh Đức</td><td>Công Nghệ</td><td>98</td></tr>
  </tbody>
</table>" class="w-full p-3.5 rounded-2xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white font-mono text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                </div>

                <!-- Process Button -->
                <button type="button" id="btnProcessExcel" onclick="processToExcel()" class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 hover:from-emerald-700 hover:to-teal-700 text-white font-bold text-sm shadow-lg shadow-emerald-500/25 transition flex items-center justify-center gap-2">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                    <span>Phân Tích Dữ Liệu & Xem Trước Bảng Tính</span>
                </button>
            </div>

            <!-- Preview & Download Section -->
            <div id="excelResultBox" class="hidden p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900 border border-emerald-300 dark:border-emerald-800 shadow-xl space-y-6">
                
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl shrink-0">
                            📊
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">Bảng Tính Đã Sẵn Sàng!</h3>
                            <p id="tableStatsInfo" class="text-xs text-slate-500 dark:text-slate-400">0 hàng • 0 cột</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button" onclick="downloadExcelXlsx()" class="py-2.5 px-5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold text-xs shadow-md shadow-emerald-500/20 transition flex items-center gap-1.5">
                            <i data-lucide="download" class="w-4 h-4"></i>
                            <span>Tải Tệp Excel (.xlsx)</span>
                        </button>
                        <button type="button" onclick="downloadCleanCsv()" class="py-2.5 px-3 rounded-xl border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold text-xs transition" title="Tải dạng CSV chuẩn">
                            .CSV
                        </button>
                    </div>
                </div>

                <!-- Interactive Data Table Preview -->
                <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800">
                    <table id="previewDataTable" class="w-full text-xs text-left text-slate-700 dark:text-slate-300 divide-y divide-slate-200 dark:divide-slate-800">
                        <thead class="bg-slate-100 dark:bg-slate-800/80 text-slate-800 dark:text-slate-200 font-bold uppercase tracking-wider text-[11px]">
                            <tr id="previewTableHead"></tr>
                        </thead>
                        <tbody id="previewTableBody" class="divide-y divide-slate-100 dark:divide-slate-800/50 bg-white dark:bg-slate-900 font-mono"></tbody>
                    </table>
                </div>

            </div>

        </div>

        <!-- Sidebar Tools & Ad Banners (1 col) -->
        <div class="space-y-6">
            <x-ad-banner slot="sidebar" />

            <!-- Related Tools -->
            <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                <h3 class="font-bold text-sm text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-lucide="sparkles" class="w-4 h-4 text-indigo-500"></i>
                    <span>Công cụ liên quan</span>
                </h3>
                <div class="space-y-3">
                    @foreach($relatedTools as $relSlug => $relTool)
                        <a href="{{ route('tool.show', ['slug' => $relTool['slug']]) }}" class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 border border-transparent hover:border-slate-200 dark:hover:border-slate-700 transition group">
                            <div class="min-w-0 pr-2">
                                <h4 class="text-xs font-semibold text-slate-800 dark:text-slate-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition truncate">
                                    {{ $relTool['title'] }}
                                </h4>
                                <span class="text-[10px] text-slate-400">{{ $relTool['badge'] ?? 'Văn phòng' }}</span>
                            </div>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5 text-slate-400 group-hover:translate-x-0.5 transition shrink-0"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <!-- SEO Content, FAQ & How-to -->
    <div class="border-t border-slate-200 dark:border-slate-800 pt-12 space-y-12">
        @if(!empty($tool['how_to']))
            <div>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                    <i data-lucide="help-circle" class="w-5 h-5 text-indigo-500"></i>
                    <span>Hướng Dẫn Chuyển Đổi Dữ Liệu Sang Excel</span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($tool['how_to'] as $index => $step)
                        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 relative">
                            <div class="w-7 h-7 rounded-lg bg-emerald-600 text-white font-bold text-xs flex items-center justify-center mb-3">
                                {{ $index + 1 }}
                            </div>
                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                                {{ $step }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if(!empty($tool['faq']))
            <div>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                    <i data-lucide="message-square" class="w-5 h-5 text-indigo-500"></i>
                    <span>Câu Hỏi Thường Gặp (FAQ)</span>
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($tool['faq'] as $faqItem)
                        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-2">{{ $faqItem['q'] }}</h3>
                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">{{ $faqItem['a'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

</div>

@push('scripts')
<!-- PDF.js for PDF reading -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<!-- SheetJS for pure client-side Excel XLSX generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    let currentSourceTab = 'pdf';
    let currentMatrixData = []; // 2D array: [ [col1, col2], [val1, val2] ]
    let currentUploadedFileName = 'bang_tinh';

    function switchSourceTab(tab) {
        currentSourceTab = tab;
        ['pdf', 'csv', 'json', 'html'].forEach(t => {
            const btn = document.getElementById(`tabBtn_${t}`);
            const panel = document.getElementById(`panel_${t}`);
            if (t === tab) {
                btn.className = 'flex-1 min-w-[120px] py-2.5 px-3 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs transition flex items-center justify-center gap-1.5 font-bold';
                panel.classList.remove('hidden');
            } else {
                btn.className = 'flex-1 min-w-[120px] py-2.5 px-3 rounded-xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition flex items-center justify-center gap-1.5';
                panel.classList.add('hidden');
            }
        });
    }

    async function handlePdfUpload(files) {
        if (!files || files.length === 0) return;
        const file = files[0];
        currentUploadedFileName = file.name.replace(/\.[^/.]+$/, "");
        
        try {
            const arrayBuffer = await file.arrayBuffer();
            const pdf = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;
            showToast(`Đang quét bảng số liệu trong ${pdf.numPages} trang PDF...`, 'info');

            let allRows = [];
            for (let i = 1; i <= pdf.numPages; i++) {
                const page = await pdf.getPage(i);
                const textContent = await page.getTextContent();
                
                // Group items by Y position
                let linesByY = {};
                textContent.items.forEach(item => {
                    const y = Math.round(item.transform[5] / 3) * 3; // Bucket by small variance
                    if (!linesByY[y]) linesByY[y] = [];
                    linesByY[y].push({ x: item.transform[4], text: item.str.trim() });
                });

                // Sort lines by Y descending (top to bottom)
                const sortedY = Object.keys(linesByY).sort((a, b) => b - a);
                sortedY.forEach(y => {
                    // Sort items in line by X ascending (left to right)
                    const rowItems = linesByY[y].sort((a, b) => a.x - b.x);
                    const rowValues = rowItems.map(it => it.text).filter(t => t.length > 0);
                    if (rowValues.length > 1) { // Likely a table row
                        allRows.push(rowValues);
                    } else if (rowValues.length === 1 && allRows.length === 0) {
                        // Header title
                        allRows.push([rowValues[0]]);
                    }
                });
            }

            if (allRows.length === 0) {
                showToast('Không tìm thấy bảng biểu rõ ràng trong PDF. Đang trích xuất theo dòng...', 'info');
                // Fallback to reading every line
                for (let i = 1; i <= Math.min(pdf.numPages, 10); i++) {
                    const page = await pdf.getPage(i);
                    const text = await page.getTextContent();
                    const line = text.items.map(it => it.str).join(' ');
                    if (line.trim()) allRows.push([`Trang ${i}`, line.trim()]);
                }
            }

            currentMatrixData = allRows;
            renderPreviewTable(currentMatrixData);
        } catch (err) {
            showToast('Lỗi khi phân tích tệp PDF.', 'error');
        }
    }

    function handleCsvUpload(files) {
        if (!files || files.length === 0) return;
        const file = files[0];
        currentUploadedFileName = file.name.replace(/\.[^/.]+$/, "");
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('csvTextInput').value = e.target.result;
            showToast(`Đã nạp tệp ${file.name}`, 'success');
        };
        reader.readAsText(file);
    }

    function loadSampleJson() {
        document.getElementById('jsonTextInput').value = JSON.stringify([
            { "Mã SP": "SP001", "Tên Sản Phẩm": "Bàn Phím Cơ Không Dây", "Đơn Giá": 1250000, "Số Lượng": 50, "Doanh Thu": 62500000 },
            { "Mã SP": "SP002", "Tên Sản Phẩm": "Chuột Ergonomic Bluetooth", "Đơn Giá": 550000, "Số Lượng": 120, "Doanh Thu": 66000000 },
            { "Mã SP": "SP003", "Tên Sản Phẩm": "Tai Nghe Chống Ồn ANC", "Đơn Giá": 2100000, "Số Lượng": 35, "Doanh Thu": 73500000 },
            { "Mã SP": "SP004", "Tên Sản Phẩm": "Màn Hình 27 Inch 2K IPS", "Đơn Giá": 5800000, "Số Lượng": 20, "Doanh Thu": 116000000 },
            { "Mã SP": "SP005", "Tên Sản Phẩm": "Giá Đỡ Laptop Nhôm Tản Nhiệt", "Đơn Giá": 320000, "Số Lượng": 85, "Doanh Thu": 27200000 }
        ], null, 2);
        showToast('Đã nạp dữ liệu JSON mẫu!', 'info');
    }

    function loadSampleHtml() {
        document.getElementById('htmlTextInput').value = `<table border="1">
  <thead>
    <tr>
      <th>Tháng</th>
      <th>Doanh Thu (VNĐ)</th>
      <th>Chi Phí (VNĐ)</th>
      <th>Lợi Nhuận Ròng</th>
      <th>Tăng Trưởng</th>
    </tr>
  </thead>
  <tbody>
    <tr><td>Tháng 1</td><td>150,000,000</td><td>80,000,000</td><td>70,000,000</td><td>+12%</td></tr>
    <tr><td>Tháng 2</td><td>180,000,000</td><td>95,000,000</td><td>85,000,000</td><td>+21%</td></tr>
    <tr><td>Tháng 3</td><td>210,000,000</td><td>110,000,000</td><td>100,000,000</td><td>+18%</td></tr>
  </tbody>
</table>`;
        showToast('Đã nạp bảng HTML mẫu!', 'info');
    }

    function processToExcel() {
        if (currentSourceTab === 'pdf') {
            if (currentMatrixData.length === 0) {
                showToast('Vui lòng tải tệp PDF lên trước!', 'error');
                return;
            }
            renderPreviewTable(currentMatrixData);
        } else if (currentSourceTab === 'csv') {
            const raw = document.getElementById('csvTextInput').value.trim();
            if (!raw) {
                showToast('Vui lòng dán hoặc tải nội dung CSV!', 'error');
                return;
            }
            const delimOpt = document.getElementById('csvDelimiter').value;
            let delim = ',';
            if (delimOpt === 'auto') {
                const firstLine = raw.split('\n')[0] || '';
                if (firstLine.includes('\t')) delim = '\t';
                else if (firstLine.includes(';')) delim = ';';
                else delim = ',';
            } else {
                delim = delimOpt;
            }

            const lines = raw.split('\n');
            currentMatrixData = lines.map(line => {
                // Regex to handle quoted CSV fields
                const row = [];
                let inQuote = false;
                let entry = '';
                for (let i = 0; i < line.length; i++) {
                    const c = line[i];
                    if (c === '"') {
                        inQuote = !inQuote;
                    } else if (c === delim && !inQuote) {
                        row.push(entry.trim().replace(/^"|"$/g, ''));
                        entry = '';
                    } else {
                        entry += c;
                    }
                }
                row.push(entry.trim().replace(/^"|"$/g, ''));
                return row;
            }).filter(r => r.length > 0 && r.some(c => c !== ''));

            renderPreviewTable(currentMatrixData);
        } else if (currentSourceTab === 'json') {
            const raw = document.getElementById('jsonTextInput').value.trim();
            if (!raw) {
                showToast('Vui lòng nhập dữ liệu JSON!', 'error');
                return;
            }
            try {
                let parsed = JSON.parse(raw);
                if (!Array.isArray(parsed)) {
                    if (typeof parsed === 'object') parsed = [parsed];
                    else throw new Error('Dữ liệu không phải là mảng object');
                }

                // Extract all unique headers
                const headers = [];
                parsed.forEach(item => {
                    if (typeof item === 'object' && item !== null) {
                        Object.keys(item).forEach(k => {
                            if (!headers.includes(k)) headers.push(k);
                        });
                    }
                });

                const rows = [headers];
                parsed.forEach(item => {
                    const row = headers.map(h => {
                        const val = item[h];
                        return (typeof val === 'object' && val !== null) ? JSON.stringify(val) : (val !== undefined ? String(val) : '');
                    });
                    rows.push(row);
                });

                currentMatrixData = rows;
                renderPreviewTable(currentMatrixData);
            } catch (err) {
                showToast('Cú pháp JSON không hợp lệ. Vui lòng kiểm tra lại!', 'error');
            }
        } else if (currentSourceTab === 'html') {
            const raw = document.getElementById('htmlTextInput').value.trim();
            if (!raw) {
                showToast('Vui lòng dán mã HTML Table!', 'error');
                return;
            }
            try {
                const parser = new DOMParser();
                const doc = parser.parseFromString(raw, 'text/html');
                const table = doc.querySelector('table');
                if (!table) {
                    showToast('Không tìm thấy thẻ <table> trong nội dung dán!', 'error');
                    return;
                }

                const rows = [];
                const trElements = table.querySelectorAll('tr');
                trElements.forEach(tr => {
                    const cells = tr.querySelectorAll('th, td');
                    const row = Array.from(cells).map(c => c.innerText.trim());
                    if (row.length > 0) rows.push(row);
                });

                currentMatrixData = rows;
                renderPreviewTable(currentMatrixData);
            } catch (err) {
                showToast('Lỗi khi đọc bảng HTML.', 'error');
            }
        }
    }

    function renderPreviewTable(data) {
        if (!data || data.length === 0) {
            showToast('Không có dữ liệu bảng để hiển thị.', 'info');
            return;
        }

        const headTr = document.getElementById('previewTableHead');
        const body = document.getElementById('previewTableBody');
        headTr.innerHTML = '';
        body.innerHTML = '';

        const maxCols = Math.max(...data.map(r => r.length));
        const headers = data[0];

        // Header Row
        for (let c = 0; c < maxCols; c++) {
            const th = document.createElement('th');
            th.className = 'py-3 px-4';
            th.innerText = headers[c] || `Cột ${c + 1}`;
            headTr.appendChild(th);
        }

        // Body Rows (Preview up to 50 rows)
        const previewRows = data.slice(1, 51);
        previewRows.forEach(row => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-slate-50 dark:hover:bg-slate-800/60 transition';
            for (let c = 0; c < maxCols; c++) {
                const td = document.createElement('td');
                td.className = 'py-2.5 px-4 truncate max-w-[200px] border-r border-slate-100 dark:border-slate-800/40 last:border-r-0';
                td.innerText = row[c] !== undefined ? row[c] : '';
                tr.appendChild(td);
            }
            body.appendChild(tr);
        });

        document.getElementById('tableStatsInfo').innerText = `Tổng số: ${data.length - 1} hàng dữ liệu • ${maxCols} cột`;
        document.getElementById('excelResultBox').classList.remove('hidden');
        document.getElementById('excelResultBox').scrollIntoView({ behavior: 'smooth', block: 'start' });
        showToast('Đã tạo bảng tính xem trước thành công!', 'success');
    }

    function downloadExcelXlsx() {
        if (!currentMatrixData || currentMatrixData.length === 0) {
            showToast('Chưa có dữ liệu để xuất Excel!', 'error');
            return;
        }

        try {
            // Build worksheet from 2D array with SheetJS
            const ws = XLSX.utils.aoa_to_sheet(currentMatrixData);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
            
            const filename = `${currentUploadedFileName || 'bang_tinh'}_ziitool.xlsx`;
            XLSX.writeFile(wb, filename);
            showToast('Đã tải xuống file Excel (.xlsx)!', 'success');
        } catch (err) {
            showToast('Lỗi khi xuất bảng tính Excel.', 'error');
        }
    }

    function downloadCleanCsv() {
        if (!currentMatrixData || currentMatrixData.length === 0) return;
        try {
            const ws = XLSX.utils.aoa_to_sheet(currentMatrixData);
            const csvData = XLSX.utils.sheet_to_csv(ws);
            const blob = new Blob(["\uFEFF" + csvData], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `${currentUploadedFileName || 'bang_tinh'}_ziitool.csv`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            showToast('Đã tải xuống file CSV!', 'success');
        } catch (err) {
            showToast('Lỗi khi tải file CSV.', 'error');
        }
    }
</script>
@endpush
@endsection

