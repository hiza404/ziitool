@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-indigo-600">Trang chủ</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>{{ $categoryInfo['name'] ?? 'Xử lý Ảnh' }}</span>
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
                Xuất File ZIP Tức Thì
            </span>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 font-semibold flex items-center gap-1">
                <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> 100% Bảo mật
            </span>
        </div>
        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 max-w-3xl">
            {{ $tool['short_desc'] }} Tự động thay đổi kích thước hàng chục bức ảnh cùng lúc, giữ nguyên tỉ lệ và đóng gói ZIP tự động.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        
        <!-- Main Tool Workspace (2 cols) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Drag Drop Upload Area -->
            <div id="dropZone" onclick="document.getElementById('fileInput').click()" class="p-8 sm:p-12 rounded-3xl border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-indigo-500 bg-white dark:bg-slate-900 text-center cursor-pointer transition-all group">
                <input type="file" id="fileInput" accept="image/*" multiple class="hidden" onchange="handleResizeFiles(this.files)">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="maximize-2" class="w-8 h-8"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-200 mb-1">
                    Chọn một hoặc nhiều ảnh để Resize
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">
                    Kéo thả nhiều ảnh cùng lúc vào khung này
                </p>
                <span class="text-xs text-indigo-600 font-semibold">Tự động tải về file ZIP trọn gói</span>
            </div>

            <!-- Resize Configuration Panel -->
            <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-4">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="sliders" class="w-4 h-4 text-indigo-600"></i> Thiết Lập Kích Thước Mới
                </h3>

                <!-- Mode Selector -->
                <div class="grid grid-cols-2 p-1 rounded-xl bg-slate-100 dark:bg-slate-800 text-xs font-semibold">
                    <button type="button" onclick="setResizeMode('pixel')" id="btnModePixel" class="py-2 rounded-lg bg-white dark:bg-slate-700 shadow-xs text-slate-900 dark:text-white transition">
                        Theo Pixel Cụ Thể (px)
                    </button>
                    <button type="button" onclick="setResizeMode('percent')" id="btnModePercent" class="py-2 rounded-lg text-slate-500 hover:text-slate-900 dark:hover:text-white transition">
                        Theo Tỉ Lệ Phần Trăm (%)
                    </button>
                </div>

                <!-- Pixel Options -->
                <div id="pixelInputs" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Chiều rộng (Width px)</label>
                        <input type="number" id="targetWidth" value="1200" min="10" max="10000" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Chiều cao (Height px)</label>
                        <input type="number" id="targetHeight" value="800" min="10" max="10000" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-mono">
                    </div>
                </div>

                <!-- Percent Options -->
                <div id="percentInputs" class="hidden">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tỉ lệ phần trăm so với ảnh gốc</label>
                    <div class="grid grid-cols-4 gap-2">
                        <button type="button" onclick="setPercent(25)" class="py-2 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-semibold hover:bg-slate-100 dark:hover:bg-slate-800">25%</button>
                        <button type="button" onclick="setPercent(50)" class="py-2 rounded-lg border border-indigo-500 text-indigo-600 bg-indigo-50/50 dark:bg-indigo-900/30 text-xs font-semibold">50%</button>
                        <button type="button" onclick="setPercent(75)" class="py-2 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-semibold hover:bg-slate-100 dark:hover:bg-slate-800">75%</button>
                        <button type="button" onclick="setPercent(150)" class="py-2 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-semibold hover:bg-slate-100 dark:hover:bg-slate-800">150%</button>
                    </div>
                </div>

                <!-- Aspect Ratio & Format -->
                <div class="flex flex-wrap items-center justify-between gap-4 pt-2">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-medium text-slate-700 dark:text-slate-300">
                        <input type="checkbox" id="chkAspectRatio" checked class="rounded text-indigo-600 focus:ring-indigo-500">
                        <span>Khóa tỉ lệ khung hình (Giữ nguyên Aspect Ratio)</span>
                    </label>

                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-500">Xuất định dạng:</span>
                        <select id="exportFormat" class="px-2.5 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs">
                            <option value="keep">Giữ định dạng gốc</option>
                            <option value="webp">WebP</option>
                            <option value="jpeg">JPEG</option>
                            <option value="png">PNG</option>
                        </select>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="startBatchResize()" id="btnProcessResize" class="flex-1 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs sm:text-sm shadow-md transition flex items-center justify-center gap-2">
                        <i data-lucide="maximize-2" class="w-4 h-4"></i>
                        <span>Bắt Đầu Resize Toàn Bộ</span>
                    </button>
                    <button type="button" onclick="downloadAllAsZip()" id="btnZipAll" disabled class="px-5 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 disabled:pointer-events-none text-white font-bold text-xs sm:text-sm shadow-md transition flex items-center gap-2">
                        <i data-lucide="archive" class="w-4 h-4"></i>
                        <span>Tải File ZIP</span>
                    </button>
                </div>
            </div>

            <!-- Resized Images Container -->
            <div id="resizeList" class="space-y-3"></div>

            <x-ad-banner slot="in_tool" />

        </div>

        <!-- Sidebar (1 col) -->
        <div class="space-y-6">
            <x-ad-banner slot="sidebar" />

            <!-- Related Tools -->
            <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Công Cụ Cùng Nhóm</h3>
                <div class="space-y-3">
                    @foreach($relatedTools as $relTool)
                        <a href="{{ route('tool.show', ['slug' => $relTool['slug']]) }}" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition group">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs">
                                <i data-lucide="{{ $relTool['icon'] ?? 'wrench' }}" class="w-4 h-4"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-blue-600 truncate">{{ $relTool['title'] }}</h4>
                                <span class="text-[10px] text-slate-400">{{ $relTool['badge'] }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

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
    let resizeFiles = [];
    let processedBlobs = [];
    let currentMode = 'pixel';
    let selectedPercent = 50;

    function setResizeMode(mode) {
        currentMode = mode;
        if (mode === 'pixel') {
            document.getElementById('pixelInputs').classList.remove('hidden');
            document.getElementById('percentInputs').classList.add('hidden');
            document.getElementById('btnModePixel').className = 'py-2 rounded-lg bg-white dark:bg-slate-700 shadow-xs text-slate-900 dark:text-white transition';
            document.getElementById('btnModePercent').className = 'py-2 rounded-lg text-slate-500 hover:text-slate-900 dark:hover:text-white transition';
        } else {
            document.getElementById('pixelInputs').classList.add('hidden');
            document.getElementById('percentInputs').classList.remove('hidden');
            document.getElementById('btnModePercent').className = 'py-2 rounded-lg bg-white dark:bg-slate-700 shadow-xs text-slate-900 dark:text-white transition';
            document.getElementById('btnModePixel').className = 'py-2 rounded-lg text-slate-500 hover:text-slate-900 dark:hover:text-white transition';
        }
    }

    function setPercent(pct) {
        selectedPercent = pct;
        showToast(`Đã chọn tỉ lệ resize: ${pct}%`, 'info');
    }

    function handleResizeFiles(files) {
        const imgs = Array.from(files).filter(f => f.type.startsWith('image/'));
        if (imgs.length === 0) return;
        resizeFiles = [...resizeFiles, ...imgs];
        renderResizeQueue();
        showToast(`Đã nạp ${imgs.length} ảnh vào hàng đợi!`, 'success');
    }

    function renderResizeQueue() {
        const list = document.getElementById('resizeList');
        if (resizeFiles.length === 0) {
            list.innerHTML = '';
            return;
        }

        list.innerHTML = resizeFiles.map((file, i) => `
            <div id="resizeItem_${i}" class="p-3.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-2.5 truncate">
                    <span class="w-6 h-6 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 flex items-center justify-center font-bold text-[10px]">${i+1}</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-200 truncate">${file.name}</span>
                </div>
                <div id="resizeStatus_${i}" class="text-slate-400 shrink-0 font-medium">Chờ xử lý</div>
            </div>
        `).join('');
    }

    async function startBatchResize() {
        if (resizeFiles.length === 0) {
            showToast('Vui lòng chọn ảnh trước!', 'error');
            return;
        }

        processedBlobs = [];
        const btnZip = document.getElementById('btnZipAll');
        btnZip.disabled = true;

        showToast('Đang tiến hành resize hàng loạt...', 'info');

        const keepAspect = document.getElementById('chkAspectRatio').checked;
        const targetW = parseInt(document.getElementById('targetWidth').value) || 1200;
        const targetH = parseInt(document.getElementById('targetHeight').value) || 800;
        const expFmt = document.getElementById('exportFormat').value;

        for (let i = 0; i < resizeFiles.length; i++) {
            const file = resizeFiles[i];
            const statusEl = document.getElementById(`resizeStatus_${i}`);
            if (statusEl) statusEl.innerHTML = `<span class="text-blue-500 animate-pulse">Đang resize...</span>`;

            try {
                const blob = await resizeSingleImage(file, { currentMode, selectedPercent, targetW, targetH, keepAspect, expFmt });
                processedBlobs.push({ name: file.name, blob: blob });
                const blobUrl = URL.createObjectURL(blob);

                if (statusEl) {
                    statusEl.innerHTML = `
                        <a href="${blobUrl}" download="resized-${file.name}" class="text-emerald-600 font-semibold hover:underline flex items-center gap-1">
                            <i data-lucide="download" class="w-3.5 h-3.5"></i> Tải ngay
                        </a>
                    `;
                    lucide.createIcons();
                }
            } catch (err) {
                if (statusEl) statusEl.innerHTML = `<span class="text-rose-500">Lỗi</span>`;
            }
        }

        btnZip.disabled = false;
        showToast('Hoàn tất resize tất cả ảnh!', 'success');
    }

    function resizeSingleImage(file, options) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.onload = () => {
                let newW, newH;
                if (options.currentMode === 'percent') {
                    newW = Math.round(img.width * (options.selectedPercent / 100));
                    newH = Math.round(img.height * (options.selectedPercent / 100));
                } else {
                    if (options.keepAspect) {
                        const ratio = img.width / img.height;
                        newW = options.targetW;
                        newH = Math.round(newW / ratio);
                    } else {
                        newW = options.targetW;
                        newH = options.targetH;
                    }
                }

                const canvas = document.createElement('canvas');
                canvas.width = Math.max(1, newW);
                canvas.height = Math.max(1, newH);
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                let mime = file.type || 'image/jpeg';
                if (options.expFmt === 'webp') mime = 'image/webp';
                else if (options.expFmt === 'png') mime = 'image/png';
                else if (options.expFmt === 'jpeg') mime = 'image/jpeg';

                canvas.toBlob((b) => {
                    if (b) resolve(b);
                    else reject(new Error('Resize blob error'));
                }, mime, 0.9);
            };
            img.onerror = reject;
            img.src = URL.createObjectURL(file);
        });
    }

    async function downloadAllAsZip() {
        if (processedBlobs.length === 0) return;
        showToast('Đang nén file ZIP...', 'info');

        const zip = new JSZip();
        processedBlobs.forEach(item => {
            const ext = item.name.split('.').pop();
            const base = item.name.substring(0, item.name.lastIndexOf('.')) || item.name;
            zip.file(`${base}-resized.${ext}`, item.blob);
        });

        const zipBlob = await zip.generateAsync({ type: 'blob' });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(zipBlob);
        a.download = `ZiiTool-Resized-Images-${Date.now()}.zip`;
        a.click();
        showToast('Đã tải xuống file ZIP thành công!', 'success');
    }
</script>
@endpush
@endsection

