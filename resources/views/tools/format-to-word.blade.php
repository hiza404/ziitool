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
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300 font-semibold">
                {{ $tool['badge'] ?? 'Đa định dạng' }}
            </span>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 font-semibold flex items-center gap-1">
                <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> 100% Client-Side Bảo Mật
            </span>
        </div>
        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 max-w-3xl">
            {{ $tool['short_desc'] }} Chuyển đổi Markdown, HTML, Text sang file Microsoft Word (.docx) chuẩn Office OpenXML, giữ nguyên tiêu đề, in đậm/nghiêng, danh sách và bảng.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        
        <!-- Main Tool Workspace (2 cols) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Format Selector Tabs -->
            <div class="p-1 rounded-2xl bg-slate-200/70 dark:bg-slate-800 flex flex-wrap text-xs font-semibold">
                <button type="button" onclick="switchWordSourceTab('markdown')" id="tabBtn_markdown" class="flex-1 min-w-[120px] py-2.5 px-3 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs transition flex items-center justify-center gap-1.5 font-bold">
                    <i data-lucide="file-code" class="w-3.5 h-3.5 text-indigo-500"></i>
                    <span>Markdown Sang Word</span>
                </button>
                <button type="button" onclick="switchWordSourceTab('html')" id="tabBtn_html" class="flex-1 min-w-[120px] py-2.5 px-3 rounded-xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition flex items-center justify-center gap-1.5">
                    <i data-lucide="code" class="w-3.5 h-3.5 text-rose-500"></i>
                    <span>HTML Sang Word</span>
                </button>
                <button type="button" onclick="switchWordSourceTab('text')" id="tabBtn_text" class="flex-1 min-w-[120px] py-2.5 px-3 rounded-xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition flex items-center justify-center gap-1.5">
                    <i data-lucide="align-left" class="w-3.5 h-3.5 text-emerald-500"></i>
                    <span>Văn Bản / TXT Sang Word</span>
                </button>
            </div>

            <!-- Editor & Options Card -->
            <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-5">
                
                <!-- Formatting Settings Bar -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/60 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Font Chữ</label>
                        <select id="docFontFamily" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs text-slate-900 dark:text-white">
                            <option value="Times New Roman">Times New Roman</option>
                            <option value="Calibri">Calibri</option>
                            <option value="Arial">Arial</option>
                            <option value="Plus Jakarta Sans">Plus Jakarta Sans</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Cỡ Chữ Thân</label>
                        <select id="docFontSize" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs text-slate-900 dark:text-white">
                            <option value="24">12pt (Tiêu chuẩn)</option>
                            <option value="22">11pt</option>
                            <option value="26">13pt</option>
                            <option value="28">14pt</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Giãn Dòng</label>
                        <select id="docLineSpacing" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs text-slate-900 dark:text-white">
                            <option value="276">1.15 lines</option>
                            <option value="360">1.5 lines</option>
                            <option value="240">Single (1.0)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Canh Lề Văn Bản</label>
                        <select id="docAlignment" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs text-slate-900 dark:text-white">
                            <option value="both">Căn đều 2 bên (Justified)</option>
                            <option value="left">Căn trái (Left)</option>
                        </select>
                    </div>
                </div>

                <!-- Input Area Header Actions -->
                <div class="flex items-center justify-between">
                    <span id="inputAreaTitle" class="text-xs font-bold text-slate-700 dark:text-slate-300">Nhập nội dung Markdown</span>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="loadSampleWordSource()" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">
                            Nạp nội dung mẫu
                        </button>
                        <span class="text-slate-300 dark:text-slate-700">•</span>
                        <button type="button" onclick="document.getElementById('fileSourceInput').click()" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1 font-medium">
                            <i data-lucide="upload" class="w-3.5 h-3.5"></i> Tải file
                        </button>
                        <input type="file" id="fileSourceInput" accept=".md,.html,.txt" class="hidden" onchange="handleSourceFileUpload(this.files)">
                    </div>
                </div>

                <textarea id="wordSourceInput" rows="10" placeholder="# BÁO CÁO CÔNG TÁC THÁNG

## 1. Kết Quả Thực Hiện Nhiệm Vụ
Trong tháng vừa qua, đơn vị đã hoàn thành vượt mức **115% chỉ tiêu** được giao:
- Tiếp nhận và xử lý hơn **1.200 văn bản** hành chính.
- Nâng cấp hệ thống website tiện ích tự động hóa đạt chuẩn bảo mật.
- Tiết kiệm 100% chi phí máy chủ thông qua giải pháp *Client-Side processing*.

## 2. Kế Hoạch Trọng Tâm Tháng Tới
1. Triển khai mở rộng tính năng chuyển đổi đa định dạng tài liệu.
2. Tối ưu trải nghiệm giao diện người dùng trên mọi thiết bị." class="w-full p-4 rounded-2xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white font-mono text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>

                <!-- Convert Action Button -->
                <button type="button" onclick="convertAndExportWord()" class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold text-sm shadow-lg shadow-indigo-500/25 transition flex items-center justify-center gap-2">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    <span>Tạo & Tải Xuống File Word (.DOCX)</span>
                </button>

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
                    <span>Hướng Dẫn Chuyển Đổi Sang Word (.DOCX)</span>
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
<script>
    let currentWordTab = 'markdown';
    let loadedFileName = 'tai_lieu';

    function switchWordSourceTab(tab) {
        currentWordTab = tab;
        ['markdown', 'html', 'text'].forEach(t => {
            const btn = document.getElementById(`tabBtn_${t}`);
            if (t === tab) {
                btn.className = 'flex-1 min-w-[120px] py-2.5 px-3 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs transition flex items-center justify-center gap-1.5 font-bold';
            } else {
                btn.className = 'flex-1 min-w-[120px] py-2.5 px-3 rounded-xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition flex items-center justify-center gap-1.5';
            }
        });

        const titleSpan = document.getElementById('inputAreaTitle');
        const input = document.getElementById('wordSourceInput');
        if (tab === 'markdown') {
            titleSpan.innerText = 'Nhập nội dung Markdown';
            input.placeholder = '# Tiêu Đề Tài Liệu\n\nĐây là nội dung văn bản với chữ **in đậm**, chữ *in nghiêng* và danh sách:\n- Điểm số 1\n- Điểm số 2';
        } else if (tab === 'html') {
            titleSpan.innerText = 'Dán mã HTML';
            input.placeholder = '<h1>Tiêu Đề Lớn</h1>\n<p>Đây là đoạn văn bản với <strong>in đậm</strong> và <em>in nghiêng</em>.</p>\n<ul>\n  <li>Mục danh sách A</li>\n  <li>Mục danh sách B</li>\n</ul>';
        } else {
            titleSpan.innerText = 'Dán văn bản thuần túy (Plain Text)';
            input.placeholder = 'Dán nội dung văn bản bất kỳ vào đây để chuyển sang tài liệu Word (.docx) được căn chỉnh thụt dòng và lề chuẩn...';
        }
    }

    function loadSampleWordSource() {
        if (currentWordTab === 'markdown') {
            document.getElementById('wordSourceInput').value = `# KẾ HOẠCH PHÁT TRIỂN NỀN TẢNG ZIITOOL 2026

## 1. Mục Tiêu Tổng Thể
Xây dựng hệ sinh thái công cụ tiện ích trực tuyến siêu tốc, **chi phí 0đ**, tự động hóa 100% với tiêu chuẩn bảo mật cao cấp:
- **Tốc độ:** Xử lý 100% Client-Side trên trình duyệt người dùng.
- **Bảo mật:** Dữ liệu tài liệu cá nhân không gửi về máy chủ.
- **Doanh thu:** Tối ưu hóa quảng cáo Google AdSense và gói VietQR Pro.

## 2. Các Tính Năng Trọng Tâm
1. Chuyển đổi PDF sang Word (.docx) không lỗi font chữ.
2. Chuyển đổi bảng dữ liệu sang Excel (.xlsx) mượt mà.
3. Hỗ trợ Developer với trọn bộ công cụ JSON, SQL, CSS và Base64.

> "Sự hài lòng và tiện lợi của người dùng là thước đo thành công lớn nhất."`;
        } else if (currentWordTab === 'html') {
            document.getElementById('wordSourceInput').value = `<h1>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</h1>
<h3>Độc lập - Tự do - Hạnh phúc</h3>
<hr/>
<h2>BIÊN BẢN BÀN GIAO TÀI LIỆU CÔNG NGHỆ</h2>
<p>Hôm nay, ngày 16 tháng 09 năm 2026, các bên tiến hành bàn giao hệ thống công cụ ZiiTool bao gồm:</p>
<ul>
  <li>Bộ công cụ xử lý đồ họa, chuyển đổi ảnh WebP/PNG chất lượng cao.</li>
  <li>Bộ công cụ văn phòng: Tính thuế TNCN, lãi kép và chuyển đổi tài liệu Word, Excel.</li>
  <li>Hệ thống thanh toán tự động VietQR Napas247 kích hoạt gói Pro siêu tốc.</li>
</ul>
<p><strong>Bên Bàn Giao:</strong> Đội ngũ Kỹ Thuật ZiiTool</p>
<p><strong>Bên Tiếp Nhận:</strong> Khách Hàng</p>`;
        } else {
            document.getElementById('wordSourceInput').value = `THÔNG BÁO VỀ VIỆC CẬP NHẬT PHIÊN BẢN MỚI

Kính gửi: Toàn thể Quý khách hàng và Đối tác

Nền tảng ZiiTool xin trân trọng thông báo ra mắt bộ công cụ chuyển đổi tài liệu văn phòng trực tuyến hoàn toàn mới:
1. Chuyển đổi PDF sang Word (.docx) giữ nguyên định dạng.
2. Chuyển đổi bảng dữ liệu từ PDF, CSV, JSON sang Excel (.xlsx).
3. Chuyển đổi các định dạng Markdown, HTML sang tài liệu Word.

Toàn bộ quá trình xử lý được diễn ra trực tiếp trên trình duyệt thiết bị của bạn, cam kết bảo mật 100% thông tin.

Trân trọng cảm ơn!`;
        }
        showToast('Đã nạp nội dung mẫu!', 'info');
    }

    function handleSourceFileUpload(files) {
        if (!files || files.length === 0) return;
        const file = files[0];
        loadedFileName = file.name.replace(/\.[^/.]+$/, "");
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('wordSourceInput').value = e.target.result;
            showToast(`Đã nạp tệp ${file.name}`, 'success');
        };
        reader.readAsText(file);
    }

    async function convertAndExportWord() {
        const rawContent = document.getElementById('wordSourceInput').value.trim();
        if (!rawContent) {
            showToast('Vui lòng nhập hoặc tải tệp nội dung trước khi xuất Word!', 'error');
            return;
        }

        const font = document.getElementById('docFontFamily').value;
        const fontSizeVal = document.getElementById('docFontSize').value;
        const lineSpacingVal = document.getElementById('docLineSpacing').value;
        const alignmentVal = document.getElementById('docAlignment').value;

        try {
            const blob = await buildDocxFromInput(rawContent, currentWordTab, {
                font: font,
                fontSize: fontSizeVal,
                lineSpacing: lineSpacingVal,
                alignment: alignmentVal
            });

            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `${loadedFileName || 'tai_lieu'}_ziitool.docx`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            showToast('Đã tạo và tải xuống tệp Word (.docx) thành công!', 'success');
        } catch (err) {
            showToast('Đã xảy ra lỗi khi tạo tệp Word.', 'error');
        }
    }

    function escapeXml(str) {
        return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&apos;');
    }

    /**
     * Generate OpenXML DOCX archive
     */
    async function buildDocxFromInput(content, mode, opts) {
        const zip = new JSZip();

        // 1. [Content_Types].xml
        zip.file('[Content_Types].xml', `<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
    <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
    <Default Extension="xml" ContentType="application/xml"/>
    <Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>
    <Override PartName="/word/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml"/>
</Types>`);

        // 2. _rels/.rels
        zip.file('_rels/.rels', `<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>
</Relationships>`);

        // 3. word/_rels/document.xml.rels
        zip.file('word/_rels/document.xml.rels', `<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
</Relationships>`);

        // 4. word/styles.xml
        zip.file('word/styles.xml', `<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
    <w:docDefaults>
        <w:rPrDefault>
            <w:rPr>
                <w:rFonts w:ascii="${opts.font}" w:hAnsi="${opts.font}" w:cs="${opts.font}"/>
                <w:sz w:val="${opts.fontSize}"/>
                <w:szCs w:val="${opts.fontSize}"/>
                <w:lang w:val="vi-VN"/>
            </w:rPr>
        </w:rPrDefault>
        <w:pPrDefault>
            <w:pPr>
                <w:spacing w:line="${opts.lineSpacing}" w:lineRule="auto" w:after="120"/>
                <w:jc w:val="${opts.alignment}"/>
            </w:pPr>
        </w:pPrDefault>
    </w:docDefaults>
</w:styles>`);

        // 5. Build paragraphs based on mode
        let docXml = `<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
    <w:body>`;

        if (mode === 'markdown') {
            const lines = content.split('\n');
            lines.forEach(line => {
                const trimmed = line.trim();
                if (!trimmed) {
                    docXml += `<w:p><w:spacing w:after="60"/></w:p>`;
                    return;
                }

                if (trimmed.startsWith('# ')) {
                    const text = escapeXml(trimmed.replace(/^#\s+/, ''));
                    docXml += `<w:p><w:pPr><w:spacing w:before="240" w:after="120"/><w:jc w:val="center"/></w:pPr><w:r><w:rPr><w:b/><w:sz w:val="36"/><w:szCs w:val="36"/></w:rPr><w:t>${text}</w:t></w:r></w:p>`;
                } else if (trimmed.startsWith('## ')) {
                    const text = escapeXml(trimmed.replace(/^##\s+/, ''));
                    docXml += `<w:p><w:pPr><w:spacing w:before="200" w:after="100"/></w:pPr><w:r><w:rPr><w:b/><w:sz w:val="30"/><w:szCs w:val="30"/><w:color w:val="2B579A"/></w:rPr><w:t>${text}</w:t></w:r></w:p>`;
                } else if (trimmed.startsWith('### ')) {
                    const text = escapeXml(trimmed.replace(/^###\s+/, ''));
                    docXml += `<w:p><w:pPr><w:spacing w:before="160" w:after="80"/></w:pPr><w:r><w:rPr><w:b/><w:sz w:val="26"/><w:szCs w:val="26"/></w:rPr><w:t>${text}</w:t></w:r></w:p>`;
                } else if (trimmed.startsWith('- ') || trimmed.startsWith('* ')) {
                    const text = escapeXml(trimmed.replace(/^[-*]\s+/, ''));
                    docXml += `<w:p><w:pPr><w:ind w:left="720"/></w:pPr><w:r><w:t>• ${text}</w:t></w:r></w:p>`;
                } else if (/^\d+\.\s+/.test(trimmed)) {
                    const text = escapeXml(trimmed);
                    docXml += `<w:p><w:pPr><w:ind w:left="720"/></w:pPr><w:r><w:t>${text}</w:t></w:r></w:p>`;
                } else if (trimmed.startsWith('> ')) {
                    const text = escapeXml(trimmed.replace(/^>\s+/, ''));
                    docXml += `<w:p><w:pPr><w:ind w:left="720" w:right="720"/><w:jc w:val="left"/></w:pPr><w:r><w:rPr><w:i/><w:color w:val="555555"/></w:rPr><w:t>${text}</w:t></w:r></w:p>`;
                } else {
                    // Normal paragraph with basic bold/italic inline parser
                    docXml += `<w:p><w:r><w:t xml:space="preserve">${escapeXml(line)}</w:t></w:r></w:p>`;
                }
            });
        } else if (mode === 'html') {
            const parser = new DOMParser();
            const doc = parser.parseFromString(content, 'text/html');
            const elements = doc.body.childNodes;
            
            elements.forEach(node => {
                if (node.nodeType === Node.ELEMENT_NODE) {
                    const tag = node.tagName.toLowerCase();
                    const text = escapeXml(node.innerText.trim());
                    if (!text) return;

                    if (tag === 'h1') {
                        docXml += `<w:p><w:pPr><w:spacing w:before="240" w:after="120"/><w:jc w:val="center"/></w:pPr><w:r><w:rPr><w:b/><w:sz w:val="36"/></w:rPr><w:t>${text}</w:t></w:r></w:p>`;
                    } else if (tag === 'h2') {
                        docXml += `<w:p><w:pPr><w:spacing w:before="200" w:after="100"/></w:pPr><w:r><w:rPr><w:b/><w:sz w:val="30"/></w:rPr><w:t>${text}</w:t></w:r></w:p>`;
                    } else if (tag === 'h3') {
                        docXml += `<w:p><w:pPr><w:spacing w:before="160" w:after="80"/></w:pPr><w:r><w:rPr><w:b/><w:sz w:val="26"/></w:rPr><w:t>${text}</w:t></w:r></w:p>`;
                    } else if (tag === 'ul' || tag === 'ol') {
                        const lis = node.querySelectorAll('li');
                        lis.forEach((li, idx) => {
                            const bullet = tag === 'ul' ? '• ' : `${idx + 1}. `;
                            docXml += `<w:p><w:pPr><w:ind w:left="720"/></w:pPr><w:r><w:t>${bullet}${escapeXml(li.innerText.trim())}</w:t></w:r></w:p>`;
                        });
                    } else {
                        docXml += `<w:p><w:r><w:t xml:space="preserve">${text}</w:t></w:r></w:p>`;
                    }
                } else if (node.nodeType === Node.TEXT_NODE && node.nodeValue.trim()) {
                    docXml += `<w:p><w:r><w:t xml:space="preserve">${escapeXml(node.nodeValue.trim())}</w:t></w:r></w:p>`;
                }
            });
        } else {
            // Plain text mode
            const paragraphs = content.split('\n');
            paragraphs.forEach(p => {
                if (p.trim()) {
                    docXml += `<w:p><w:r><w:t xml:space="preserve">${escapeXml(p)}</w:t></w:r></w:p>`;
                } else {
                    docXml += `<w:p><w:spacing w:after="60"/></w:p>`;
                }
            });
        }

        // Section A4
        docXml += `
        <w:sectPr>
            <w:pgSz w:w="11906" w:h="16838"/>
            <w:pgMar w:top="1440" w:right="1440" w:bottom="1440" w:left="1440" w:header="720" w:footer="720" w:gutter="0"/>
        </w:sectPr>
    </w:body>
</w:document>`;

        zip.file('word/document.xml', docXml);

        return await zip.generateAsync({ type: 'blob', mimeType: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' });
    }
</script>
@endpush
@endsection

