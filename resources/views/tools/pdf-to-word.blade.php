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
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 font-semibold">
                {{ $tool['badge'] ?? 'Hot Nhất' }}
            </span>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 font-semibold flex items-center gap-1">
                <i data-lucide="image" class="w-3.5 h-3.5"></i> Giữ Nguyên Ảnh & Avatar
            </span>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300 font-semibold flex items-center gap-1">
                <i data-lucide="columns" class="w-3.5 h-3.5"></i> Tự Động Bố Cục 2 Cột CV
            </span>
        </div>
        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 max-w-3xl">
            {{ $tool['short_desc'] }} Tự động nhận diện ảnh đại diện, bố cục đa cột chuẩn CV (TopCV, Canva) và chuyển thành tệp Microsoft Word (.docx) có thể chỉnh sửa 100%.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        
        <!-- Main Tool Workspace (2 cols) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Engine Selection Tabs -->
            <div class="flex p-1 bg-slate-100 dark:bg-slate-800/80 rounded-2xl border border-slate-200 dark:border-slate-700 text-xs font-semibold">
                <button type="button" id="tabEngineClient" onclick="setConversionEngine('client')" class="flex-1 py-2.5 px-4 rounded-xl bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-300 shadow-sm transition flex items-center justify-center gap-2">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                    <span>Engine Trình Duyệt (Bố Cục CV & Nhúng Ảnh)</span>
                </button>
                <button type="button" id="tabEngineServer" onclick="setConversionEngine('server')" class="flex-1 py-2.5 px-4 rounded-xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition flex items-center justify-center gap-2">
                    <i data-lucide="cpu" class="w-4 h-4"></i>
                    <span>Engine Máy Chủ (High-Fidelity)</span>
                </button>
            </div>

            <!-- Drag Drop Upload Area -->
            <div id="dropZone" onclick="document.getElementById('fileInput').click()" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)" class="relative p-8 sm:p-12 rounded-3xl border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-500 bg-white dark:bg-slate-900 text-center cursor-pointer transition-all group">
                <input type="file" id="fileInput" accept=".pdf,application/pdf" class="hidden" onchange="handleFileSelect(this.files)">
                
                <div class="w-16 h-16 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                    <i data-lucide="file-up" class="w-8 h-8"></i>
                </div>
                
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-200 mb-1">
                    Kéo thả tệp PDF vào đây hoặc <span class="text-indigo-600 dark:text-indigo-400 underline">chọn từ máy tính</span>
                </h3>
                <p class="text-xs text-slate-400 dark:text-slate-500">
                    Hỗ trợ tệp CV, Sơ yếu lý lịch, Tài liệu văn phòng, Báo cáo • Giữ nguyên ảnh & màu sắc
                </p>
            </div>

            <!-- File Info & Conversion Options (Hidden by default) -->
            <div id="settingsBox" class="hidden p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
                <!-- Selected File Details -->
                <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/60">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-900/40 text-rose-600 dark:text-rose-400 flex items-center justify-center font-bold text-xs shrink-0">
                            PDF
                        </div>
                        <div class="min-w-0">
                            <h4 id="pdfFileName" class="text-sm font-bold text-slate-900 dark:text-white truncate">document.pdf</h4>
                            <p id="pdfFileInfo" class="text-xs text-slate-500 dark:text-slate-400">Đang đọc tài liệu...</p>
                        </div>
                    </div>
                    <button type="button" onclick="resetFile()" class="p-2 text-slate-400 hover:text-rose-500 dark:hover:text-rose-400 transition" title="Chọn file khác">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>

                <!-- Conversion Options -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Phạm vi trang chuyển đổi</label>
                        <select id="pageRangeSelect" onchange="toggleCustomPages(this.value)" class="w-full px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="all">Toàn bộ trang (Khuyên dùng)</option>
                            <option value="custom">Chỉ định dải trang</option>
                        </select>
                        <input id="customPagesInput" type="text" placeholder="Ví dụ: 1-5 hoặc 1,3,5" class="hidden mt-2 w-full px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-mono focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <div id="clientOptionsCol">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Tối ưu định dạng & Hình ảnh</label>
                        <div class="space-y-2 text-xs">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="optExtractImages" checked class="rounded text-indigo-600 focus:ring-indigo-500">
                                <span class="text-slate-700 dark:text-slate-300 font-medium">Trích xuất & nhúng ảnh đại diện/avatar</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="optAutoTwoColumn" checked class="rounded text-indigo-600 focus:ring-indigo-500">
                                <span class="text-slate-700 dark:text-slate-300 font-medium">Tự động nhận diện CV 2 cột (TopCV, Canva)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="optSidebarStyle" checked class="rounded text-indigo-600 focus:ring-indigo-500">
                                <span class="text-slate-700 dark:text-slate-300">Tạo nền màu sidebar & tiêu đề sang trọng</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Convert Action Button -->
                <button type="button" id="btnConvert" onclick="startPdfConversion()" class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-indigo-600 via-indigo-700 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold text-sm shadow-lg shadow-indigo-500/25 transition flex items-center justify-center gap-2">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    <span id="btnConvertText">Bắt Đầu Chuyển Đổi Sang Word (.DOCX)</span>
                </button>

                <!-- Conversion Progress Bar -->
                <div id="progressBox" class="hidden space-y-2 pt-2">
                    <div class="flex justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span id="progressStatus">Đang chuẩn bị bóc tách văn bản & hình ảnh...</span>
                        <span id="progressPercent" class="font-bold text-indigo-600 dark:text-indigo-400">0%</span>
                    </div>
                    <div class="w-full h-2.5 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                        <div id="progressBar" class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 transition-all duration-200" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- Conversion Result Box (Hidden until converted) -->
            <div id="resultBox" class="hidden p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900 border border-emerald-200 dark:border-emerald-800/50 shadow-xl space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl shrink-0 font-bold">
                            ✓
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">Chuyển Đổi Thành Công!</h3>
                            <p id="resultSummary" class="text-xs text-slate-500 dark:text-slate-400">Đã giữ trọn vẹn bố cục và hình ảnh trong file Word (.docx)</p>
                        </div>
                    </div>

                    <!-- Action Downloads -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <button type="button" onclick="downloadDocxFile()" class="py-2.5 px-5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold text-xs shadow-md shadow-emerald-500/20 transition flex items-center gap-1.5">
                            <i data-lucide="download" class="w-4 h-4"></i>
                            <span>Tải File Word (.docx)</span>
                        </button>
                        <button type="button" onclick="downloadTxtFile()" class="py-2.5 px-3 rounded-xl border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold text-xs transition" title="Tải văn bản thuần (.txt)">
                            .TXT
                        </button>
                    </div>
                </div>

                <!-- Extracted Images Gallery -->
                <div id="extractedImagesBox" class="hidden space-y-3 p-4 rounded-2xl bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-100 dark:border-indigo-900/40">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i data-lucide="image" class="w-4 h-4 text-indigo-600 dark:text-indigo-400"></i>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Hình ảnh & Avatar trích xuất từ PDF:</span>
                            <span id="imgCountBadge" class="text-[10px] px-2 py-0.5 rounded-full bg-indigo-200 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 font-bold">1 ảnh</span>
                        </div>
                        <button type="button" id="btnDownloadAllImages" onclick="downloadAllExtractedImagesZip()" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-semibold flex items-center gap-1">
                            <i data-lucide="download-cloud" class="w-3.5 h-3.5"></i> Tải Tất Cả Ảnh (.ZIP)
                        </button>
                    </div>
                    <div id="imageGalleryGrid" class="flex flex-wrap gap-3 pt-1">
                        <!-- Image cards injected here -->
                    </div>
                </div>

                <!-- Extracted Document Preview -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i> Xem trước bố cục văn bản
                        </span>
                        <button type="button" onclick="copyExtractedText()" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1 font-medium">
                            <i data-lucide="copy" class="w-3.5 h-3.5"></i> Sao chép văn bản
                        </button>
                    </div>
                    <div id="previewContent" class="max-h-[500px] overflow-y-auto p-4 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 text-xs sm:text-sm leading-relaxed space-y-4">
                        <!-- Preview paragraphs injected here -->
                    </div>
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
        <!-- How to Use -->
        @if(!empty($tool['how_to']))
            <div>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                    <i data-lucide="help-circle" class="w-5 h-5 text-indigo-500"></i>
                    <span>Hướng Dẫn Chuyển PDF Sang Word Chuẩn Đẹp & Giữ Ảnh</span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($tool['how_to'] as $index => $step)
                        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 relative">
                            <div class="w-7 h-7 rounded-lg bg-indigo-600 text-white font-bold text-xs flex items-center justify-center mb-3">
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

        <!-- FAQ -->
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
<!-- PDF.js library for fast client-side parsing -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    let selectedEngine = 'client'; // 'client' or 'server'
    let currentPdfDoc = null;
    let currentPdfFile = null;
    let extractedPagesData = [];
    let allExtractedImages = [];
    let generatedDocxBlob = null;
    let fullExtractedText = '';

    function setConversionEngine(engine) {
        selectedEngine = engine;
        const btnClient = document.getElementById('tabEngineClient');
        const btnServer = document.getElementById('tabEngineServer');
        const clientOptions = document.getElementById('clientOptionsCol');
        const btnText = document.getElementById('btnConvertText');

        if (engine === 'client') {
            btnClient.className = 'flex-1 py-2.5 px-4 rounded-xl bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-300 shadow-sm transition flex items-center justify-center gap-2';
            btnServer.className = 'flex-1 py-2.5 px-4 rounded-xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition flex items-center justify-center gap-2';
            clientOptions.classList.remove('opacity-40', 'pointer-events-none');
            btnText.innerText = 'Bắt Đầu Chuyển Đổi Sang Word (.DOCX)';
        } else {
            btnServer.className = 'flex-1 py-2.5 px-4 rounded-xl bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-300 shadow-sm transition flex items-center justify-center gap-2';
            btnClient.className = 'flex-1 py-2.5 px-4 rounded-xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition flex items-center justify-center gap-2';
            clientOptions.classList.add('opacity-40', 'pointer-events-none');
            btnText.innerText = 'Chuyển Đổi Bằng Engine Máy Chủ (Server High-Fidelity)';
        }
    }

    function handleDragOver(e) {
        e.preventDefault();
        document.getElementById('dropZone').classList.add('border-indigo-500', 'bg-indigo-50/20');
    }

    function handleDragLeave(e) {
        e.preventDefault();
        document.getElementById('dropZone').classList.remove('border-indigo-500', 'bg-indigo-50/20');
    }

    function handleDrop(e) {
        e.preventDefault();
        document.getElementById('dropZone').classList.remove('border-indigo-500', 'bg-indigo-50/20');
        if (e.dataTransfer.files.length > 0) {
            handleFileSelect(e.dataTransfer.files);
        }
    }

    async function handleFileSelect(files) {
        if (!files || files.length === 0) return;
        const file = files[0];
        if (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')) {
            showToast('Vui lòng chọn tệp định dạng PDF!', 'error');
            return;
        }

        currentPdfFile = file;
        document.getElementById('pdfFileName').innerText = file.name;
        document.getElementById('pdfFileInfo').innerText = `Kích thước: ${(file.size / (1024 * 1024)).toFixed(2)} MB • Đang quét trang...`;
        document.getElementById('settingsBox').classList.remove('hidden');
        document.getElementById('resultBox').classList.add('hidden');

        const arrayBuffer = await file.arrayBuffer();
        try {
            currentPdfDoc = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;
            document.getElementById('pdfFileInfo').innerText = `Kích thước: ${(file.size / (1024 * 1024)).toFixed(2)} MB • Tổng số trang: ${currentPdfDoc.numPages}`;
            showToast(`Đã nhận diện tệp PDF (${currentPdfDoc.numPages} trang)`, 'success');
        } catch (err) {
            showToast('Không thể đọc tệp PDF. Tệp có thể bị đặt mật khẩu bảo vệ.', 'error');
            resetFile();
        }
    }

    function toggleCustomPages(val) {
        const customInput = document.getElementById('customPagesInput');
        if (val === 'custom') {
            customInput.classList.remove('hidden');
            customInput.focus();
        } else {
            customInput.classList.add('hidden');
        }
    }

    function resetFile() {
        currentPdfDoc = null;
        currentPdfFile = null;
        extractedPagesData = [];
        allExtractedImages = [];
        generatedDocxBlob = null;
        fullExtractedText = '';
        document.getElementById('fileInput').value = '';
        document.getElementById('settingsBox').classList.add('hidden');
        document.getElementById('resultBox').classList.add('hidden');
        document.getElementById('progressBox').classList.add('hidden');
    }

    async function startPdfConversion() {
        if (!currentPdfFile) {
            showToast('Vui lòng chọn tệp PDF trước!', 'error');
            return;
        }

        if (selectedEngine === 'server') {
            return startServerConversion();
        }

        return startClientConversion();
    }

    /**
     * Server Engine Conversion (LibreOffice Headless)
     */
    async function startServerConversion() {
        const btn = document.getElementById('btnConvert');
        btn.disabled = true;
        btn.innerHTML = '<span class="animate-spin inline-block mr-2">⏳</span> Đang chuyển đổi trên máy chủ...';

        const progressBox = document.getElementById('progressBox');
        const progressStatus = document.getElementById('progressStatus');
        const progressPercent = document.getElementById('progressPercent');
        const progressBar = document.getElementById('progressBar');
        progressBox.classList.remove('hidden');

        progressStatus.innerText = 'Đang tải tệp lên máy chủ & phân tích bố cục...';
        progressPercent.innerText = '30%';
        progressBar.style.width = '30%';

        const formData = new FormData();
        formData.append('pdf_file', currentPdfFile);
        formData.append('_token', '{{ csrf_token() }}');

        try {
            progressStatus.innerText = 'Máy chủ đang chạy Engine High-Fidelity...';
            progressPercent.innerText = '65%';
            progressBar.style.width = '65%';

            const response = await fetch("{{ route('tool.pdf-to-word.server-convert') }}", {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                const errData = await response.json();
                throw new Error(errData.error || 'Chuyển đổi thất bại');
            }

            generatedDocxBlob = await response.blob();
            progressBar.style.width = '100%';
            progressPercent.innerText = '100%';
            progressStatus.innerText = 'Hoàn tất xuất sắc!';

            document.getElementById('resultSummary').innerText = `Đã chuyển đổi hoàn hảo bằng Engine Máy Chủ High-Fidelity.`;
            document.getElementById('resultBox').classList.remove('hidden');
            document.getElementById('extractedImagesBox').classList.add('hidden');
            document.getElementById('previewContent').innerHTML = `<p class="text-slate-600 dark:text-slate-400 italic">Tệp Word đã được tạo thành công với bố cục vector nguyên bản. Vui lòng bấm nút Tải File Word bên trên để mở và xem trên Microsoft Word.</p>`;
            document.getElementById('resultBox').scrollIntoView({ behavior: 'smooth', block: 'start' });

            btn.disabled = false;
            btn.innerHTML = '<i data-lucide="file-text" class="w-4 h-4"></i><span>Chuyển Đổi Lại</span>';
            lucide.createIcons();
            showToast('Chuyển đổi sang Word (.docx) thành công!', 'success');
        } catch (err) {
            progressBox.classList.add('hidden');
            btn.disabled = false;
            btn.innerHTML = '<i data-lucide="file-text" class="w-4 h-4"></i><span>Thử Lại</span>';
            lucide.createIcons();
            showToast(err.message || 'Lỗi chuyển đổi trên máy chủ. Hãy thử dùng Engine Trình Duyệt!', 'error');
        }
    }

    /**
     * Smart Client-Side Engine (Column Detection, Image Extraction & Native OpenXML)
     */
    async function startClientConversion() {
        const btn = document.getElementById('btnConvert');
        btn.disabled = true;
        btn.innerHTML = '<span class="animate-spin inline-block mr-2">⏳</span> Đang bóc tách & giữ ảnh...';

        const progressBox = document.getElementById('progressBox');
        const progressStatus = document.getElementById('progressStatus');
        const progressPercent = document.getElementById('progressPercent');
        const progressBar = document.getElementById('progressBar');
        progressBox.classList.remove('hidden');

        extractedPagesData = [];
        allExtractedImages = [];
        fullExtractedText = '';

        const totalPages = currentPdfDoc.numPages;
        const rangeMode = document.getElementById('pageRangeSelect').value;
        const customRange = document.getElementById('customPagesInput').value.trim();
        const optExtractImages = document.getElementById('optExtractImages').checked;
        const optAutoTwoColumn = document.getElementById('optAutoTwoColumn').checked;
        const optSidebarStyle = document.getElementById('optSidebarStyle').checked;

        // Determine pages to extract
        let pagesToExtract = [];
        if (rangeMode === 'custom' && customRange) {
            const parts = customRange.split(',');
            for (const part of parts) {
                if (part.includes('-')) {
                    const [start, end] = part.split('-').map(Number);
                    if (start && end) {
                        for (let p = Math.max(1, start); p <= Math.min(totalPages, end); p++) {
                            if (!pagesToExtract.includes(p)) pagesToExtract.push(p);
                        }
                    }
                } else {
                    const p = Number(part);
                    if (p >= 1 && p <= totalPages && !pagesToExtract.includes(p)) {
                        pagesToExtract.push(p);
                    }
                }
            }
        } else {
            for (let i = 1; i <= totalPages; i++) pagesToExtract.push(i);
        }

        if (pagesToExtract.length === 0) {
            pagesToExtract = [1];
        }

        // Process pages sequentially
        for (let idx = 0; idx < pagesToExtract.length; idx++) {
            const pageNum = pagesToExtract[idx];
            const pct = Math.round(((idx + 0.3) / pagesToExtract.length) * 80);
            progressStatus.innerText = `Đang render & quét ảnh trang ${pageNum} / ${totalPages}...`;
            progressPercent.innerText = `${pct}%`;
            progressBar.style.width = `${pct}%`;

            const page = await currentPdfDoc.getPage(pageNum);
            const viewport = page.getViewport({ scale: 1.5 });

            // 1. Render page to canvas to populate page.objs & provide visual fallback
            const canvas = document.createElement('canvas');
            canvas.width = viewport.width;
            canvas.height = viewport.height;
            const ctx = canvas.getContext('2d');
            await page.render({ canvasContext: ctx, viewport: viewport }).promise;

            // 2. Extract embedded images
            let pageImages = [];
            if (optExtractImages) {
                pageImages = await extractPageImages(page, canvas, viewport);
                pageImages.forEach(img => {
                    allExtractedImages.push({
                        pageNumber: pageNum,
                        ...img
                    });
                });
            }

            // 3. Extract text items and coordinates
            progressStatus.innerText = `Đang phân tích cấu trúc cột & khối văn bản trang ${pageNum}...`;
            const textContent = await page.getTextContent();
            const cleanItems = [];

            for (const item of textContent.items) {
                const str = item.str;
                if (!str || !str.trim()) continue;

                // PDF coordinates: tx is left, ty is bottom
                const tx = item.transform[4];
                const ty = item.transform[5];
                const yTop = viewport.height - (ty * 1.5);
                const xLeft = tx * 1.5;
                const fontSize = item.height ? (item.height * 1.5) : (Math.abs(item.transform[0]) * 1.5) || 16;

                cleanItems.push({
                    str: str.trim(),
                    x: xLeft,
                    y: yTop,
                    fontSize: fontSize,
                    width: item.width ? (item.width * 1.5) : (str.length * fontSize * 0.5)
                });
            }

            // 4. Detect Multi-column layout (CV / Resume style)
            let isTwoColumn = false;
            let splitX = viewport.width * 0.35;

            if (optAutoTwoColumn && cleanItems.length > 15) {
                const minSplit = viewport.width * 0.22;
                const maxSplit = viewport.width * 0.45;
                let bestSplit = null;
                let maxColScore = 0;

                for (let candX = minSplit; candX <= maxSplit; candX += 12) {
                    const leftItems = cleanItems.filter(it => it.x < candX - 10);
                    const rightItems = cleanItems.filter(it => it.x >= candX + 10);

                    if (leftItems.length >= 5 && rightItems.length >= 8) {
                        const leftYs = leftItems.map(it => it.y);
                        const rightYs = rightItems.map(it => it.y);
                        const leftMin = Math.min(...leftYs), leftMax = Math.max(...leftYs);
                        const rightMin = Math.min(...rightYs), rightMax = Math.max(...rightYs);
                        const overlap = Math.max(0, Math.min(leftMax, rightMax) - Math.max(leftMin, rightMin));

                        if (overlap > 100) {
                            const score = leftItems.length * 2 + rightItems.length + overlap;
                            if (score > maxColScore) {
                                maxColScore = score;
                                bestSplit = candX;
                            }
                        }
                    }
                }

                if (bestSplit !== null) {
                    isTwoColumn = true;
                    splitX = bestSplit;
                }
            }

            // 5. Group into structured lines
            let pageData = {
                pageNumber: pageNum,
                isTwoColumn: isTwoColumn,
                splitX: splitX,
                images: pageImages
            };

            if (isTwoColumn) {
                const leftItems = cleanItems.filter(it => it.x < splitX);
                const rightItems = cleanItems.filter(it => it.x >= splitX);
                pageData.leftLines = groupItemsIntoLines(leftItems);
                pageData.rightLines = groupItemsIntoLines(rightItems);
                fullExtractedText += `\n\n--- TRANG ${pageNum} (CỘT TRÁI - THÔNG TIN & KỸ NĂNG) ---\n\n` + pageData.leftLines.map(l => l.text).join('\n');
                fullExtractedText += `\n\n--- TRANG ${pageNum} (CỘT PHẢI - KINH NGHIỆM & MỤC TIÊU) ---\n\n` + pageData.rightLines.map(l => l.text).join('\n');
            } else {
                pageData.lines = groupItemsIntoLines(cleanItems);
                fullExtractedText += `\n\n--- TRANG ${pageNum} ---\n\n` + pageData.lines.map(l => l.text).join('\n');
            }

            extractedPagesData.push(pageData);
        }

        // 6. Build DOCX with embedded images and OpenXML tables
        progressStatus.innerText = 'Đang nhúng ảnh đại diện & đóng gói file Word OpenXML (.docx)...';
        progressPercent.innerText = '90%';
        progressBar.style.width = '90%';

        generatedDocxBlob = await buildRichOpenXmlDocx(extractedPagesData, allExtractedImages, {
            sidebarStyle: optSidebarStyle
        });

        progressBar.style.width = '100%';
        progressPercent.innerText = '100%';
        progressStatus.innerText = 'Hoàn tất xuất sắc!';

        // 7. Render UI Results & Image Gallery
        renderExtractedImagesGallery(allExtractedImages);
        renderDocumentPreview(extractedPagesData);

        document.getElementById('resultSummary').innerText = `Đã chuyển đổi thành công ${pagesToExtract.length} trang, giữ ${allExtractedImages.length} ảnh và tái tạo bố cục CV chuyên nghiệp.`;
        document.getElementById('resultBox').classList.remove('hidden');
        document.getElementById('resultBox').scrollIntoView({ behavior: 'smooth', block: 'start' });

        btn.disabled = false;
        btn.innerHTML = '<i data-lucide="file-text" class="w-4 h-4"></i><span>Chuyển Đổi Lại</span>';
        lucide.createIcons();
        showToast('Chuyển đổi sang Word (.docx) thành công!', 'success');
    }

    /**
     * Extract images from PDF.js operator list & page.objs with canvas crop fallback
     */
    async function extractPageImages(page, canvas, viewport) {
        const images = [];
        const handledKeys = new Set();

        try {
            const ops = await page.getOperatorList();
            for (let i = 0; i < ops.fnArray.length; i++) {
                const fn = ops.fnArray[i];
                if (fn === pdfjsLib.OPS.paintImageXObject || fn === pdfjsLib.OPS.paintInlineImageXObject) {
                    const imgKey = ops.argsArray[i][0];
                    if (handledKeys.has(imgKey)) continue;
                    handledKeys.add(imgKey);

                    const imgObj = await getImageFromPdfObjs(page, imgKey);
                    if (imgObj) {
                        const dataUrl = convertPdfImageToDataUrl(imgObj);
                        if (dataUrl) {
                            const w = imgObj.width || (imgObj.bitmap ? imgObj.bitmap.width : 0);
                            const h = imgObj.height || (imgObj.bitmap ? imgObj.bitmap.height : 0);
                            
                            // Filter out 1px spacers or full-page background graphics
                            if (w >= 35 && h >= 35) {
                                const isAvatar = (w >= 100 && w <= 800 && h >= 100 && h <= 800 && Math.abs(w - h) < 200);
                                images.push({
                                    id: imgKey,
                                    width: w,
                                    height: h,
                                    dataUrl: dataUrl,
                                    isAvatar: isAvatar
                                });
                            }
                        }
                    }
                }
            }
        } catch (e) {
            console.warn("Operator list image extraction:", e);
        }

        // Fallback: If no photo was extracted and this looks like a CV with top-left photo:
        if (images.length === 0) {
            try {
                // TopCV avatar standard area is top-left
                const cropW = Math.round(180 * (viewport.scale / 1.5));
                const cropH = cropW;
                const cropX = Math.round(18 * (viewport.scale / 1.5));
                const cropY = Math.round(18 * (viewport.scale / 1.5));

                if (cropX + cropW <= canvas.width && cropY + cropH <= canvas.height) {
                    const cropCanvas = document.createElement('canvas');
                    cropCanvas.width = cropW;
                    cropCanvas.height = cropH;
                    const cropCtx = cropCanvas.getContext('2d');
                    cropCtx.drawImage(canvas, cropX, cropY, cropW, cropH, 0, 0, cropW, cropH);
                    
                    // Check that it's not a pure white/black solid block
                    const pData = cropCtx.getImageData(0, 0, cropW, cropH).data;
                    let variance = 0;
                    for (let i = 0; i < pData.length; i += 40) {
                        variance += Math.abs(pData[i] - pData[i + 1]) + Math.abs(pData[i + 1] - pData[i + 2]);
                    }
                    if (variance > 1000) {
                        images.push({
                            id: 'avatar_crop',
                            width: cropW,
                            height: cropH,
                            dataUrl: cropCanvas.toDataURL('image/png'),
                            isAvatar: true
                        });
                    }
                }
            } catch (err) {
                console.warn("Canvas crop fallback error:", err);
            }
        }

        return images;
    }

    function getImageFromPdfObjs(page, imgKey) {
        return new Promise((resolve) => {
            try {
                let resolved = false;
                const cb = (res) => {
                    if (!resolved) {
                        resolved = true;
                        resolve(res);
                    }
                };
                let direct = null;
                if (page.objs && page.objs.has(imgKey)) {
                    direct = page.objs.get(imgKey, cb);
                } else if (page.commonObjs && page.commonObjs.has(imgKey)) {
                    direct = page.commonObjs.get(imgKey, cb);
                }
                if (direct && !resolved) {
                    resolved = true;
                    resolve(direct);
                }
                setTimeout(() => {
                    if (!resolved) {
                        resolved = true;
                        resolve(null);
                    }
                }, 350);
            } catch (e) {
                resolve(null);
            }
        });
    }

    function convertPdfImageToDataUrl(obj) {
        if (!obj) return null;
        try {
            const canvas = document.createElement('canvas');
            if (obj.bitmap && obj.bitmap.width && obj.bitmap.height) {
                canvas.width = obj.bitmap.width;
                canvas.height = obj.bitmap.height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(obj.bitmap, 0, 0);
                return canvas.toDataURL('image/png');
            }
            if (obj instanceof HTMLImageElement || obj instanceof HTMLCanvasElement) {
                canvas.width = obj.width;
                canvas.height = obj.height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(obj, 0, 0);
                return canvas.toDataURL('image/png');
            }
            if (obj.data && obj.width && obj.height) {
                canvas.width = obj.width;
                canvas.height = obj.height;
                const ctx = canvas.getContext('2d');
                const imgData = ctx.createImageData(obj.width, obj.height);
                const len = obj.data.length;
                const w = obj.width;
                const h = obj.height;
                if (len === w * h * 4) {
                    imgData.data.set(obj.data);
                } else if (len === w * h * 3) {
                    let d = 0;
                    for (let s = 0; s < len; s += 3) {
                        imgData.data[d++] = obj.data[s];
                        imgData.data[d++] = obj.data[s + 1];
                        imgData.data[d++] = obj.data[s + 2];
                        imgData.data[d++] = 255;
                    }
                } else if (len === w * h) {
                    let d = 0;
                    for (let s = 0; s < len; s++) {
                        const v = obj.data[s];
                        imgData.data[d++] = v;
                        imgData.data[d++] = v;
                        imgData.data[d++] = v;
                        imgData.data[d++] = 255;
                    }
                } else {
                    return null;
                }
                ctx.putImageData(imgData, 0, 0);
                return canvas.toDataURL('image/png');
            }
        } catch (err) {
            console.warn('Cannot convert image object to data URL:', err);
        }
        return null;
    }

    function groupItemsIntoLines(items) {
        items.sort((a, b) => {
            if (Math.abs(a.y - b.y) > 7) {
                return a.y - b.y; // Top to bottom
            }
            return a.x - b.x; // Left to right
        });

        const lines = [];
        let currentGroup = [];
        let currentY = null;

        for (const it of items) {
            if (currentY === null || Math.abs(it.y - currentY) <= 7) {
                currentGroup.push(it);
                currentY = (currentY === null) ? it.y : (currentY + it.y) / 2;
            } else {
                if (currentGroup.length > 0) {
                    lines.push(buildLineObject(currentGroup));
                }
                currentGroup = [it];
                currentY = it.y;
            }
        }
        if (currentGroup.length > 0) {
            lines.push(buildLineObject(currentGroup));
        }
        return lines;
    }

    function buildLineObject(items) {
        const text = items.map(it => it.str).join(' ');
        const maxFontSize = Math.max(...items.map(it => it.fontSize));
        const isHeader = maxFontSize > 22 || (text.length < 50 && (text === text.toUpperCase() || text.endsWith(':')));
        const isBullet = text.startsWith('•') || text.startsWith('-') || text.startsWith('+') || text.startsWith('*');

        return {
            text: text,
            fontSize: maxFontSize,
            isHeading: isHeader,
            isBullet: isBullet
        };
    }

    /**
     * Build rich OpenXML DOCX archive with embedded images, styles, and tables
     */
    async function buildRichOpenXmlDocx(pages, images, options) {
        const zip = new JSZip();

        // 1. Media folder: write all images and build relationships
        let imageIndex = 1;
        const imageRels = [];

        images.forEach(img => {
            const relId = `rIdImg${imageIndex}`;
            const fileName = `image${imageIndex}.png`;
            const base64Data = img.dataUrl.split(',')[1];
            const binaryData = Uint8Array.from(atob(base64Data), c => c.charCodeAt(0));

            zip.file(`word/media/${fileName}`, binaryData);
            img.relId = relId;
            img.imageIndex = imageIndex;
            imageRels.push({ relId, fileName });
            imageIndex++;
        });

        // 2. [Content_Types].xml
        zip.file('[Content_Types].xml', `<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
    <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
    <Default Extension="xml" ContentType="application/xml"/>
    <Default Extension="png" ContentType="image/png"/>
    <Default Extension="jpeg" ContentType="image/jpeg"/>
    <Default Extension="jpg" ContentType="image/jpeg"/>
    <Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>
    <Override PartName="/word/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml"/>
</Types>`);

        // 3. _rels/.rels
        zip.file('_rels/.rels', `<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>
</Relationships>`);

        // 4. word/_rels/document.xml.rels
        let relsXml = `<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rIdStyles" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>`;
        imageRels.forEach(rel => {
            relsXml += `
    <Relationship Id="${rel.relId}" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/${rel.fileName}"/>`;
        });
        relsXml += `\n</Relationships>`;
        zip.file('word/_rels/document.xml.rels', relsXml);

        // 5. word/styles.xml
        zip.file('word/styles.xml', `<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
    <w:docDefaults>
        <w:rPrDefault>
            <w:rPr>
                <w:rFonts w:ascii="Calibri" w:hAnsi="Calibri" w:cs="Calibri"/>
                <w:sz w:val="22"/>
                <w:szCs w:val="22"/>
                <w:lang w:val="vi-VN"/>
            </w:rPr>
        </w:rPrDefault>
        <w:pPrDefault>
            <w:pPr>
                <w:spacing w:line="240" w:lineRule="auto" w:after="80"/>
            </w:pPr>
        </w:pPrDefault>
    </w:docDefaults>
</w:styles>`);

        // 6. word/document.xml
        let docXml = `<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
            xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
            xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing"
            xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main"
            xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture">
    <w:body>`;

        pages.forEach((pg, pIdx) => {
            if (pg.isTwoColumn) {
                // Find avatar image for this page
                const pageAvatar = pg.images.find(img => img.isAvatar) || pg.images[0];
                const cx = 140 * 9525; // 140px in EMUs
                const cy = 140 * 9525;

                docXml += `
        <w:tbl>
            <w:tblPr>
                <w:tblW w:w="9400" w:type="dxa"/>
                <w:tblBorders>
                    <w:top w:val="none"/><w:left w:val="none"/><w:bottom w:val="none"/><w:right w:val="none"/>
                    <w:insideH w:val="none"/><w:insideV w:val="none"/>
                </w:tblBorders>
            </w:tblPr>
            <w:tblGrid>
                <w:gridCol w:w="3200"/>
                <w:gridCol w:w="6200"/>
            </w:tblGrid>
            <w:tr>
                <!-- CỘT TRÁI (SIDEBAR) -->
                <w:tc>
                    <w:tcPr>
                        <w:tcW w:w="3200" w:type="dxa"/>
                        ${options.sidebarStyle ? '<w:shd w:val="clear" w:color="auto" w:fill="2D3748"/>' : ''}
                        <w:tcMar>
                            <w:top w:w="240" w:type="dxa"/>
                            <w:left w:w="240" w:type="dxa"/>
                            <w:bottom w:w="240" w:type="dxa"/>
                            <w:right w:w="240" w:type="dxa"/>
                        </w:tcMar>
                    </w:tcPr>`;

                // Insert Avatar Drawing in Left Column if available
                if (pageAvatar && pageAvatar.relId) {
                    docXml += `
                    <w:p>
                        <w:pPr><w:jc w:val="center"/><w:spacing w:after="200"/></w:pPr>
                        <w:r>
                            <w:drawing>
                                <wp:inline distT="0" distB="0" distL="0" distR="0">
                                    <wp:extent cx="${cx}" cy="${cy}"/>
                                    <wp:effectExtent l="0" t="0" r="0" b="0"/>
                                    <wp:docPr id="${pageAvatar.imageIndex}" name="Avatar"/>
                                    <wp:cNvGraphicFramePr><a:graphicFrameLocks xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" noChangeAspect="1"/></wp:cNvGraphicFramePr>
                                    <a:graphic xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">
                                        <a:graphicData uri="http://schemas.openxmlformats.org/drawingml/2006/picture">
                                            <pic:pic xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture">
                                                <pic:nvPicPr><pic:cNvPr id="${pageAvatar.imageIndex}" name="Avatar"/><pic:cNvPicPr/></pic:nvPicPr>
                                                <pic:blipFill><a:blip r:embed="${pageAvatar.relId}"/><a:stretch><a:fillRect/></a:stretch></pic:blipFill>
                                                <pic:spPr>
                                                    <a:xfrm><a:off x="0" y="0"/><a:ext cx="${cx}" cy="${cy}"/></a:xfrm>
                                                    <a:prstGeom prst="rect"><a:avLst/></a:prstGeom>
                                                </pic:spPr>
                                            </pic:pic>
                                        </a:graphicData>
                                    </a:graphic>
                                </wp:inline>
                            </w:drawing>
                        </w:r>
                    </w:p>`;
                }

                // Left column content
                pg.leftLines.forEach(line => {
                    const safeText = escapeXml(line.text);
                    if (line.isHeading) {
                        docXml += `
                    <w:p>
                        <w:pPr><w:spacing w:before="160" w:after="80"/></w:pPr>
                        <w:r>
                            <w:rPr><w:b/><w:sz w:val="24"/><w:color w:val="${options.sidebarStyle ? 'F6AD55' : '2B6CB0'}"/></w:rPr>
                            <w:t xml:space="preserve">${safeText}</w:t>
                        </w:r>
                    </w:p>`;
                    } else {
                        docXml += `
                    <w:p>
                        <w:pPr><w:spacing w:after="40"/></w:pPr>
                        <w:r>
                            <w:rPr><w:sz w:val="19"/><w:color w:val="${options.sidebarStyle ? 'FFFFFF' : '4A5568'}"/></w:rPr>
                            <w:t xml:space="preserve">${safeText}</w:t>
                        </w:r>
                    </w:p>`;
                    }
                });

                docXml += `
                </w:tc>

                <!-- CỘT PHẢI (MAIN CONTENT) -->
                <w:tc>
                    <w:tcPr>
                        <w:tcW w:w="6200" w:type="dxa"/>
                        <w:tcMar>
                            <w:top w:w="240" w:type="dxa"/>
                            <w:left w:w="300" w:type="dxa"/>
                            <w:bottom w:w="240" w:type="dxa"/>
                            <w:right w:w="200" w:type="dxa"/>
                        </w:tcMar>
                    </w:tcPr>`;

                pg.rightLines.forEach((line, rIdx) => {
                    const safeText = escapeXml(line.text);
                    if (rIdx === 0 && line.fontSize > 20) {
                        // Candidate Name Header
                        docXml += `
                    <w:p>
                        <w:pPr><w:spacing w:after="60"/></w:pPr>
                        <w:r><w:rPr><w:b/><w:sz w:val="52"/><w:color w:val="1A202C"/></w:rPr><w:t xml:space="preserve">${safeText}</w:t></w:r>
                    </w:p>`;
                    } else if (line.isHeading) {
                        docXml += `
                    <w:p>
                        <w:pPr><w:spacing w:before="160" w:after="80"/></w:pPr>
                        <w:r><w:rPr><w:b/><w:sz w:val="26"/><w:color w:val="2D3748"/></w:rPr><w:t xml:space="preserve">${safeText}</w:t></w:r>
                    </w:p>`;
                    } else {
                        docXml += `
                    <w:p>
                        <w:pPr><w:spacing w:after="50"/></w:pPr>
                        <w:r><w:rPr><w:sz w:val="20"/><w:color w:val="2D3748"/></w:rPr><w:t xml:space="preserve">${safeText}</w:t></w:r>
                    </w:p>`;
                    }
                });

                docXml += `
                </w:tc>
            </w:tr>
        </w:tbl>`;

            } else {
                // Single Column Standard Document
                // Embed images if present
                if (pg.images.length > 0) {
                    pg.images.forEach(img => {
                        const cx = Math.min(img.width, 450) * 9525;
                        const cy = Math.min(img.height, 450) * 9525;
                        docXml += `
        <w:p>
            <w:pPr><w:jc w:val="center"/><w:spacing w:after="160"/></w:pPr>
            <w:r>
                <w:drawing>
                    <wp:inline distT="0" distB="0" distL="0" distR="0">
                        <wp:extent cx="${cx}" cy="${cy}"/>
                        <wp:effectExtent l="0" t="0" r="0" b="0"/>
                        <wp:docPr id="${img.imageIndex}" name="Image"/>
                        <wp:cNvGraphicFramePr><a:graphicFrameLocks xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" noChangeAspect="1"/></wp:cNvGraphicFramePr>
                        <a:graphic xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">
                            <a:graphicData uri="http://schemas.openxmlformats.org/drawingml/2006/picture">
                                <pic:pic xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture">
                                    <pic:nvPicPr><pic:cNvPr id="${img.imageIndex}" name="Image"/><pic:cNvPicPr/></pic:nvPicPr>
                                    <pic:blipFill><a:blip r:embed="${img.relId}"/><a:stretch><a:fillRect/></a:stretch></pic:blipFill>
                                    <pic:spPr>
                                        <a:xfrm><a:off x="0" y="0"/><a:ext cx="${cx}" cy="${cy}"/></a:xfrm>
                                        <a:prstGeom prst="rect"><a:avLst/></a:prstGeom>
                                    </pic:spPr>
                                </pic:pic>
                            </a:graphicData>
                        </a:graphic>
                    </wp:inline>
                </w:drawing>
            </w:r>
        </w:p>`;
                    });
                }

                pg.lines.forEach(line => {
                    const safeText = escapeXml(line.text);
                    if (line.isHeading) {
                        docXml += `
        <w:p>
            <w:pPr><w:spacing w:before="200" w:after="100"/></w:pPr>
            <w:r><w:rPr><w:b/><w:sz w:val="28"/><w:color w:val="1A202C"/></w:rPr><w:t xml:space="preserve">${safeText}</w:t></w:r>
        </w:p>`;
                    } else {
                        docXml += `
        <w:p>
            <w:pPr><w:spacing w:after="80"/></w:pPr>
            <w:r><w:rPr><w:sz w:val="22"/><w:color w:val="2D3748"/></w:rPr><w:t xml:space="preserve">${safeText}</w:t></w:r>
        </w:p>`;
                    }
                });
            }

            // Page Break if not last page
            if (pIdx < pages.length - 1) {
                docXml += `
        <w:p><w:r><w:br w:type="page"/></w:r></w:p>`;
            }
        });

        // Section standard A4
        docXml += `
        <w:sectPr>
            <w:pgSz w:w="11906" w:h="16838"/>
            <w:pgMar w:top="1000" w:right="1000" w:bottom="1000" w:left="1000" w:header="720" w:footer="720"/>
        </w:sectPr>
    </w:body>
</w:document>`;

        zip.file('word/document.xml', docXml);

        return await zip.generateAsync({ type: 'blob', mimeType: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' });
    }

    function renderExtractedImagesGallery(images) {
        const box = document.getElementById('extractedImagesBox');
        const grid = document.getElementById('imageGalleryGrid');
        const countBadge = document.getElementById('imgCountBadge');

        if (!images || images.length === 0) {
            box.classList.add('hidden');
            return;
        }

        box.classList.remove('hidden');
        countBadge.innerText = `${images.length} ảnh`;
        let html = '';

        images.forEach((img, idx) => {
            html += `
            <div class="relative group bg-white dark:bg-slate-900 p-2 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-3">
                <img src="${img.dataUrl}" alt="Extracted Image ${idx + 1}" class="w-14 h-14 object-cover rounded-lg border border-slate-100 dark:border-slate-800 shadow-inner">
                <div class="text-xs">
                    <p class="font-bold text-slate-800 dark:text-slate-200">${img.isAvatar ? 'Ảnh Đại Diện / Avatar' : `Hình Ảnh ${idx + 1}`}</p>
                    <p class="text-[10px] text-slate-400">${img.width} × ${img.height} px</p>
                    <button type="button" onclick="downloadSingleImage('${img.dataUrl}', '${img.isAvatar ? 'avatar_ziitool' : 'image_' + (idx + 1)}')" class="mt-1 text-[11px] text-indigo-600 dark:text-indigo-400 hover:underline font-semibold flex items-center gap-1">
                        <i data-lucide="download" class="w-3 h-3"></i> Tải ảnh (.PNG)
                    </button>
                </div>
            </div>`;
        });

        grid.innerHTML = html;
        lucide.createIcons();
    }

    function renderDocumentPreview(pages) {
        const previewBox = document.getElementById('previewContent');
        let html = '';

        pages.forEach(pg => {
            html += `<div class="p-4 mb-4 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">`;
            html += `<div class="text-[10px] font-mono text-indigo-500 font-bold uppercase mb-3 border-b border-slate-100 dark:border-slate-800 pb-1 flex items-center justify-between">
                        <span>Trang ${pg.pageNumber}</span>
                        <span>${pg.isTwoColumn ? 'Bố cục: 2 Cột CV (Bảo lưu ảnh & danh mục)' : 'Bố cục: Văn bản tiêu chuẩn'}</span>
                     </div>`;

            if (pg.isTwoColumn) {
                const avatar = pg.images.find(img => img.isAvatar) || pg.images[0];
                html += `<div class="grid grid-cols-1 md:grid-cols-3 gap-4">`;
                
                // Left Column
                html += `<div class="p-4 rounded-xl bg-slate-800 text-white space-y-2 text-xs">`;
                if (avatar) {
                    html += `<div class="text-center mb-3">
                                <img src="${avatar.dataUrl}" alt="Avatar" class="w-24 h-24 rounded-full mx-auto object-cover border-2 border-amber-400 shadow-md">
                             </div>`;
                }
                pg.leftLines.forEach(line => {
                    if (line.isHeading) {
                        html += `<p class="font-bold text-amber-400 text-xs mt-3 uppercase tracking-wider">${escapeHtml(line.text)}</p>`;
                    } else {
                        html += `<p class="text-slate-200 text-[11px]">${escapeHtml(line.text)}</p>`;
                    }
                });
                html += `</div>`;

                // Right Column
                html += `<div class="md:col-span-2 space-y-2 text-xs text-slate-800 dark:text-slate-200">`;
                pg.rightLines.forEach((line, rIdx) => {
                    if (rIdx === 0 && line.fontSize > 20) {
                        html += `<h2 class="text-xl font-extrabold text-slate-900 dark:text-white">${escapeHtml(line.text)}</h2>`;
                    } else if (line.isHeading) {
                        html += `<h4 class="font-bold text-slate-900 dark:text-white text-xs mt-3 border-b border-slate-200 dark:border-slate-800 pb-1">${escapeHtml(line.text)}</h4>`;
                    } else {
                        html += `<p class="text-slate-700 dark:text-slate-300 text-xs">${escapeHtml(line.text)}</p>`;
                    }
                });
                html += `</div>`;

                html += `</div>`;
            } else {
                pg.lines.forEach(line => {
                    if (line.isHeading) {
                        html += `<p class="font-bold text-slate-900 dark:text-white text-sm my-2">${escapeHtml(line.text)}</p>`;
                    } else {
                        html += `<p class="text-slate-700 dark:text-slate-300 text-xs my-1">${escapeHtml(line.text)}</p>`;
                    }
                });
            }

            html += `</div>`;
        });

        previewBox.innerHTML = html || '<p class="text-slate-400 italic">Không tìm thấy văn bản để hiển thị.</p>';
    }

    function downloadSingleImage(dataUrl, filename) {
        const a = document.createElement('a');
        a.href = dataUrl;
        a.download = `${filename}.png`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        showToast('Đã tải xuống hình ảnh!', 'success');
    }

    async function downloadAllExtractedImagesZip() {
        if (!allExtractedImages || allExtractedImages.length === 0) return;
        const zip = new JSZip();
        allExtractedImages.forEach((img, idx) => {
            const base64Data = img.dataUrl.split(',')[1];
            const binaryData = Uint8Array.from(atob(base64Data), c => c.charCodeAt(0));
            zip.file(`${img.isAvatar ? 'avatar' : 'image_' + (idx + 1)}.png`, binaryData);
        });
        const blob = await zip.generateAsync({ type: 'blob' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `${currentPdfFile ? currentPdfFile.name.replace(/\.[^/.]+$/, "") : 'document'}_images.zip`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        showToast('Đã tải xuống gói hình ảnh (.ZIP)!', 'success');
    }

    function downloadDocxFile() {
        if (!generatedDocxBlob) return;
        const originalName = currentPdfFile ? currentPdfFile.name.replace(/\.[^/.]+$/, "") : "document";
        const url = URL.createObjectURL(generatedDocxBlob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `${originalName}_ziitool.docx`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        showToast('Đã tải xuống tệp Word (.docx)!', 'success');
    }

    function downloadTxtFile() {
        if (!fullExtractedText) return;
        const originalName = currentPdfFile ? currentPdfFile.name.replace(/\.[^/.]+$/, "") : "document";
        const blob = new Blob([fullExtractedText], { type: 'text/plain;charset=utf-8' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `${originalName}_ziitool.txt`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        showToast('Đã tải xuống tệp văn bản (.txt)!', 'success');
    }

    function copyExtractedText() {
        if (!fullExtractedText) return;
        copyText(fullExtractedText, 'Đã sao chép toàn bộ văn bản vào bộ nhớ tạm!');
    }

    function escapeHtml(str) {
        return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function escapeXml(str) {
        return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&apos;');
    }
</script>
@endpush
@endsection
