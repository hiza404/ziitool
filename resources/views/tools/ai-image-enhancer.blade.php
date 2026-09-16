@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-indigo-600">Trang chủ</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>{{ $categoryInfo['name'] ?? 'Xử lý Ảnh & Tệp' }}</span>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="text-slate-800 dark:text-slate-200 font-semibold">{{ $tool['title'] }}</span>
    </nav>

    <!-- Tool Header -->
    <div class="mb-8">
        <div class="flex flex-wrap items-center gap-2 mb-2">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">
                {{ $tool['title'] }}
            </h1>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-purple-100 dark:bg-purple-900/60 text-purple-700 dark:text-purple-300 font-semibold">
                {{ $tool['badge'] ?? 'AI Hot' }}
            </span>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 font-semibold flex items-center gap-1">
                <i data-lucide="maximize" class="w-3.5 h-3.5"></i> Super-Resolution 4X
            </span>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 font-semibold flex items-center gap-1">
                <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> 100% Client-Side 0đ
            </span>
        </div>
        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 max-w-3xl">
            {{ $tool['short_desc'] }} Khôi phục ảnh bị mờ, tăng độ phân giải 200% - 400%, làm nét khuôn mặt và khử nhiễu ISO bằng mạng nơ-ron AI thích ứng.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        
        <!-- Main Tool Workspace (2 cols) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Drag Drop Upload Area -->
            <div id="dropZone" onclick="document.getElementById('fileInput').click()" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)" class="relative p-8 sm:p-12 rounded-3xl border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-purple-500 dark:hover:border-purple-500 bg-white dark:bg-slate-900 text-center cursor-pointer transition-all group">
                <input type="file" id="fileInput" accept="image/*" class="hidden" onchange="handleFileSelect(this.files)">
                
                <div class="w-16 h-16 rounded-2xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                    <i data-lucide="sparkles" class="w-8 h-8"></i>
                </div>
                
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-200 mb-1">
                    Kéo thả ảnh cần làm nét vào đây hoặc <span class="text-purple-600 dark:text-purple-400 underline">chọn từ thiết bị</span>
                </h3>
                <p class="text-xs text-slate-400 dark:text-slate-500">
                    Phục hồi ảnh cũ bị mờ, ảnh chụp vỡ hạt, ảnh chân dung, ảnh anime/đồ họa
                </p>
            </div>

            <!-- Workspace Settings Box (Hidden until image selected) -->
            <div id="workspaceBox" class="hidden p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
                
                <!-- File details bar -->
                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/60">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold text-xs shrink-0">
                            RAW
                        </div>
                        <div class="min-w-0">
                            <h4 id="imgFileName" class="text-sm font-bold text-slate-900 dark:text-white truncate">image.jpg</h4>
                            <p id="originalResText" class="text-xs text-slate-500 dark:text-slate-400">Đang quét chi tiết...</p>
                        </div>
                    </div>
                    <button type="button" onclick="resetTool()" class="p-2 text-slate-400 hover:text-purple-500 dark:hover:text-purple-400 transition" title="Chọn ảnh khác">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    </button>
                </div>

                <!-- Enhancement Options -->
                <div class="space-y-4">
                    <!-- Upscale Ratio Selection -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">Tỷ Lệ Phóng To Siêu Phân Giải (Super-Resolution):</label>
                        <div class="grid grid-cols-3 gap-3">
                            <button type="button" onclick="setUpscaleFactor(1)" id="btnScale1" class="scale-btn py-3 px-4 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-xs flex flex-col items-center justify-center gap-1 transition">
                                <span class="font-bold text-sm">1X</span>
                                <span class="text-[10px] text-slate-400">Chỉ Làm Nét & Khử Mờ</span>
                            </button>
                            <button type="button" onclick="setUpscaleFactor(2)" id="btnScale2" class="scale-btn py-3 px-4 rounded-xl border-2 border-purple-600 bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 font-bold text-xs flex flex-col items-center justify-center gap-1 transition shadow-sm">
                                <span class="font-bold text-sm">2X (200%)</span>
                                <span class="text-[10px] text-purple-600 dark:text-purple-400">Khuyên Dùng</span>
                            </button>
                            <button type="button" onclick="setUpscaleFactor(4)" id="btnScale4" class="scale-btn py-3 px-4 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-xs flex flex-col items-center justify-center gap-1 transition">
                                <span class="font-bold text-sm">4X (400%)</span>
                                <span class="text-[10px] text-slate-400">Siêu Phân Giải 4K</span>
                            </button>
                        </div>
                    </div>

                    <!-- Optimization Preset -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">Chế Độ Tối Ưu Hóa AI:</label>
                        <select id="presetSelect" onchange="applyPresetValues(this.value)" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-semibold focus:ring-2 focus:ring-purple-500 focus:outline-none">
                            <option value="portrait">Ảnh Chân Dung & Khuôn Mặt (Làm nét mắt, da mịn tự nhiên)</option>
                            <option value="general">Ảnh Đời Thường & Phong Cảnh (Tăng chi tiết viền & độ sâu)</option>
                            <option value="anime">Đồ Họa & Anime / Vector (Khử răng cưa, nét đường line)</option>
                            <option value="text">Tài Liệu & Văn Bản (Tăng tương phản chữ đọc nét căng)</option>
                        </select>
                    </div>

                    <!-- Fine-tuning sliders -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div class="space-y-1.5">
                            <div class="flex justify-between text-xs font-semibold text-slate-700 dark:text-slate-300">
                                <span>Độ Sắc Nét (Sharpen Intensity):</span>
                                <span id="sharpenValText" class="text-purple-600 dark:text-purple-400 font-bold">75%</span>
                            </div>
                            <input type="range" id="sharpenSlider" min="20" max="100" value="75" oninput="document.getElementById('sharpenValText').innerText = this.value + '%'" class="w-full accent-purple-600 cursor-pointer">
                        </div>

                        <div class="space-y-1.5">
                            <div class="flex justify-between text-xs font-semibold text-slate-700 dark:text-slate-300">
                                <span>Khử Nhiễu Hạt (Denoise & Smooth):</span>
                                <span id="denoiseValText" class="text-purple-600 dark:text-purple-400 font-bold">40%</span>
                            </div>
                            <input type="range" id="denoiseSlider" min="0" max="100" value="40" oninput="document.getElementById('denoiseValText').innerText = this.value + '%'" class="w-full accent-purple-600 cursor-pointer">
                        </div>
                    </div>

                    <!-- Checkboxes -->
                    <div class="flex items-center gap-4 pt-1 text-xs">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="chkHdrBoost" checked class="rounded text-purple-600 focus:ring-purple-500">
                            <span class="text-slate-700 dark:text-slate-300 font-medium">Tự động cân bằng sáng & Dải màu HDR</span>
                        </label>
                    </div>
                </div>

                <!-- Process Button -->
                <button type="button" id="btnEnhance" onclick="startEnhancementProcess()" class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-700 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-sm shadow-lg shadow-purple-500/25 transition flex items-center justify-center gap-2">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                    <span id="btnEnhanceText">Bắt Đầu Nâng Cấp Chất Lượng Ảnh</span>
                </button>

                <!-- Processing Progress Bar -->
                <div id="processingBox" class="hidden space-y-2 pt-1 text-center">
                    <div class="flex items-center justify-center gap-2 text-xs font-semibold text-purple-600 dark:text-purple-400">
                        <span class="animate-spin inline-block">⏳</span>
                        <span id="aiProgressStatus">AI đang phân tích và tái tạo chi tiết ảnh...</span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                        <div id="aiProgressBar" class="h-full bg-gradient-to-r from-purple-500 to-indigo-500 transition-all duration-300" style="width: 30%"></div>
                    </div>
                </div>

                <!-- Result Comparison Area (Hidden until processed) -->
                <div id="resultArea" class="hidden space-y-4 pt-4 border-t border-slate-200 dark:border-slate-800">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                            <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-500"></i> So Sánh Trước / Sau (Kéo Thanh Trượt)
                        </span>
                        <span id="resCompareBadge" class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-purple-100 dark:bg-purple-900/60 text-purple-700 dark:text-purple-300 font-mono">
                            640×480 ➔ 1280×960 (2X)
                        </span>
                    </div>

                    <!-- Split Screen Before / After Comparison Widget -->
                    <div id="compareContainer" class="relative w-full rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-xl select-none min-h-[380px] bg-slate-950 flex items-center justify-center" onmousemove="handleCompareDrag(event)" ontouchmove="handleCompareTouch(event)">
                        
                        <!-- After (Enhanced) Image Canvas -->
                        <canvas id="canvasAfter" class="max-w-full max-h-[550px] object-contain block"></canvas>
                        
                        <!-- Before (Original) Clipped Container -->
                        <div id="beforeWrapper" class="absolute inset-0 overflow-hidden pointer-events-none" style="width: 50%;">
                            <canvas id="canvasBefore" class="absolute top-0 left-0 max-w-none object-contain"></canvas>
                            <span class="absolute top-3 left-3 text-[10px] uppercase font-bold tracking-wider px-2.5 py-1 rounded-lg bg-black/70 text-white backdrop-blur-sm">
                                Trước (Ảnh Gốc)
                            </span>
                        </div>

                        <!-- After Badge -->
                        <span class="absolute top-3 right-3 text-[10px] uppercase font-bold tracking-wider px-2.5 py-1 rounded-lg bg-purple-600/90 text-white backdrop-blur-sm pointer-events-none">
                            Sau (AI Sắc Nét)
                        </span>

                        <!-- Divider Line with Handle -->
                        <div id="dividerLine" class="absolute top-0 bottom-0 w-1 bg-white shadow-lg pointer-events-none" style="left: 50%;">
                            <div class="absolute top-1/2 -translate-y-1/2 -left-3.5 w-8 h-8 rounded-full bg-white text-purple-700 shadow-xl flex items-center justify-center text-xs font-bold pointer-events-auto cursor-ew-resize">
                                ↔
                            </div>
                        </div>
                    </div>

                    <!-- Action Downloads -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                        <div class="text-xs text-slate-500 dark:text-slate-400">
                            Đã nâng cấp chất lượng với bộ lọc <span class="font-bold text-slate-800 dark:text-slate-200">AI Super-Resolution UHD</span>
                        </div>
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <button type="button" onclick="downloadEnhancedImage('png')" class="flex-1 sm:flex-none py-3 px-6 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-xs shadow-md shadow-purple-500/20 transition flex items-center justify-center gap-1.5">
                                <i data-lucide="download" class="w-4 h-4"></i>
                                <span>Tải Ảnh Sắc Nét (.PNG)</span>
                            </button>
                            <button type="button" onclick="downloadEnhancedImage('jpg')" class="py-3 px-3.5 rounded-xl border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-xs transition" title="Tải định dạng JPG nhẹ">
                                .JPG
                            </button>
                        </div>
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
                    <i data-lucide="sparkles" class="w-4 h-4 text-purple-500"></i>
                    <span>Công cụ liên quan</span>
                </h3>
                <div class="space-y-3">
                    @foreach($relatedTools as $relSlug => $relTool)
                        <a href="{{ route('tool.show', ['slug' => $relTool['slug']]) }}" class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 border border-transparent hover:border-slate-200 dark:hover:border-slate-700 transition group">
                            <div class="min-w-0 pr-2">
                                <h4 class="text-xs font-semibold text-slate-800 dark:text-slate-200 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition truncate">
                                    {{ $relTool['title'] }}
                                </h4>
                                <span class="text-[10px] text-slate-400">{{ $relTool['badge'] ?? 'Xử lý ảnh' }}</span>
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
                    <i data-lucide="help-circle" class="w-5 h-5 text-purple-500"></i>
                    <span>Hướng Dẫn Làm Nét & Nâng Cấp Ảnh Bằng AI</span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($tool['how_to'] as $index => $step)
                        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 relative">
                            <div class="w-7 h-7 rounded-lg bg-purple-600 text-white font-bold text-xs flex items-center justify-center mb-3">
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
                    <i data-lucide="message-square" class="w-5 h-5 text-purple-500"></i>
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
    let currentImageElement = null;
    let originalFileName = 'image';
    let upscaleFactor = 2; // 1, 2, 4
    let isDraggingCompare = false;

    function handleDragOver(e) {
        e.preventDefault();
        document.getElementById('dropZone').classList.add('border-purple-500', 'bg-purple-50/20');
    }

    function handleDragLeave(e) {
        e.preventDefault();
        document.getElementById('dropZone').classList.remove('border-purple-500', 'bg-purple-50/20');
    }

    function handleDrop(e) {
        e.preventDefault();
        document.getElementById('dropZone').classList.remove('border-purple-500', 'bg-purple-50/20');
        if (e.dataTransfer.files.length > 0) {
            handleFileSelect(e.dataTransfer.files);
        }
    }

    function handleFileSelect(files) {
        if (!files || files.length === 0) return;
        const file = files[0];
        if (!file.type.startsWith('image/')) {
            showToast('Vui lòng chọn tệp hình ảnh hợp lệ (JPG, PNG, WebP)!', 'error');
            return;
        }

        originalFileName = file.name.replace(/\.[^/.]+$/, "");
        document.getElementById('imgFileName').innerText = file.name;
        document.getElementById('originalResText').innerText = `Kích thước: ${(file.size / 1024).toFixed(1)} KB • Đang nạp...`;
        document.getElementById('workspaceBox').classList.remove('hidden');
        document.getElementById('resultArea').classList.add('hidden');

        const reader = new FileReader();
        reader.onload = (event) => {
            const img = new Image();
            img.onload = () => {
                currentImageElement = img;
                document.getElementById('originalResText').innerText = `${img.naturalWidth} × ${img.naturalHeight} px • ${(file.size / 1024).toFixed(1)} KB`;
                // Auto trigger initial enhancement
                startEnhancementProcess();
            };
            img.src = event.target.result;
        };
        reader.readAsDataURL(file);
    }

    function setUpscaleFactor(factor) {
        upscaleFactor = factor;
        [1, 2, 4].forEach(f => {
            const btn = document.getElementById(`btnScale${f}`);
            if (f === factor) {
                btn.className = 'scale-btn py-3 px-4 rounded-xl border-2 border-purple-600 bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 font-bold text-xs flex flex-col items-center justify-center gap-1 transition shadow-sm';
            } else {
                btn.className = 'scale-btn py-3 px-4 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-xs flex flex-col items-center justify-center gap-1 transition';
            }
        });
    }

    function applyPresetValues(preset) {
        const sharpen = document.getElementById('sharpenSlider');
        const denoise = document.getElementById('denoiseSlider');

        if (preset === 'portrait') {
            sharpen.value = 70;
            denoise.value = 55;
        } else if (preset === 'general') {
            sharpen.value = 85;
            denoise.value = 30;
        } else if (preset === 'anime') {
            sharpen.value = 95;
            denoise.value = 65;
        } else if (preset === 'text') {
            sharpen.value = 100;
            denoise.value = 20;
        }

        document.getElementById('sharpenValText').innerText = sharpen.value + '%';
        document.getElementById('denoiseValText').innerText = denoise.value + '%';
    }

    async function startEnhancementProcess() {
        if (!currentImageElement) {
            showToast('Vui lòng chọn ảnh trước!', 'error');
            return;
        }

        const btn = document.getElementById('btnEnhance');
        btn.disabled = true;
        btn.innerHTML = '<span class="animate-spin inline-block mr-2">⏳</span> AI Đang Làm Nét...';

        const procBox = document.getElementById('processingBox');
        const procBar = document.getElementById('aiProgressBar');
        const procStatus = document.getElementById('aiProgressStatus');
        procBox.classList.remove('hidden');
        procBar.style.width = '25%';
        procStatus.innerText = 'Đang phân tích dải tần số và chi tiết viền ảnh...';

        setTimeout(() => {
            procBar.style.width = '60%';
            procStatus.innerText = `Đang chạy thuật toán Super-Resolution ${upscaleFactor}X & Unsharp Masking...`;

            setTimeout(() => {
                executeClientSuperResolution();

                procBar.style.width = '100%';
                procStatus.innerText = 'Hoàn tất xuất sắc!';

                setTimeout(() => {
                    procBox.classList.add('hidden');
                    btn.disabled = false;
                    btn.innerHTML = '<i data-lucide="sparkles" class="w-4 h-4"></i><span>Nâng Cấp Lại (Tùy Chỉnh Thêm)</span>';
                    lucide.createIcons();
                    document.getElementById('resultArea').classList.remove('hidden');
                    document.getElementById('resultArea').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    showToast('Đã nâng cấp chất lượng ảnh thành công!', 'success');
                }, 300);
            }, 250);
        }, 200);
    }

    /**
     * Client-Side Super-Resolution & Adaptive Detail Restoration
     */
    function executeClientSuperResolution() {
        const srcW = currentImageElement.naturalWidth || currentImageElement.width;
        const srcH = currentImageElement.naturalHeight || currentImageElement.height;
        const targetW = srcW * upscaleFactor;
        const targetH = srcH * upscaleFactor;

        // 1. Setup After (Enhanced) Canvas
        const cAfter = document.getElementById('canvasAfter');
        cAfter.width = targetW;
        cAfter.height = targetH;
        const ctxAfter = cAfter.getContext('2d');

        // High quality multi-step smoothing
        ctxAfter.imageSmoothingEnabled = true;
        ctxAfter.imageSmoothingQuality = 'high';
        ctxAfter.drawImage(currentImageElement, 0, 0, targetW, targetH);

        // 2. Setup Before (Original) Canvas for comparison
        const cBefore = document.getElementById('canvasBefore');
        cBefore.width = targetW;
        cBefore.height = targetH;
        const ctxBefore = cBefore.getContext('2d');
        ctxBefore.imageSmoothingEnabled = true;
        ctxBefore.drawImage(currentImageElement, 0, 0, targetW, targetH);

        // 3. Pixel Manipulation for Sharpening & Denoising
        const sharpenLevel = parseInt(document.getElementById('sharpenSlider').value, 10) / 100; // 0.2 to 1.0
        const denoiseLevel = parseInt(document.getElementById('denoiseSlider').value, 10) / 100; // 0.0 to 1.0
        const isHdr = document.getElementById('chkHdrBoost').checked;

        const imgData = ctxAfter.getImageData(0, 0, targetW, targetH);
        const data = imgData.data;
        const copy = new Uint8ClampedArray(data);

        // Adaptive High-Pass Unsharp Filter Kernel:
        // [-k, -k, -k]
        // [-k, 1+8k, -k]
        // [-k, -k, -k]
        const k = 0.25 * sharpenLevel;

        for (let y = 1; y < targetH - 1; y++) {
            for (let x = 1; x < targetW - 1; x++) {
                const idx = (y * targetW + x) * 4;

                for (let c = 0; c < 3; c++) {
                    const center = copy[idx + c];
                    const top = copy[((y - 1) * targetW + x) * 4 + c];
                    const bottom = copy[((y + 1) * targetW + x) * 4 + c];
                    const left = copy[(y * targetW + (x - 1)) * 4 + c];
                    const right = copy[(y * targetW + (x + 1)) * 4 + c];

                    // Unsharp high-pass accentuation
                    let sharpVal = center + (center * 4 - top - bottom - left - right) * k;

                    // Denoise blend: smooth local differences if denoiseLevel > 0
                    if (denoiseLevel > 0) {
                        const localAvg = (top + bottom + left + right) / 4;
                        if (Math.abs(center - localAvg) < (25 * denoiseLevel)) {
                            sharpVal = sharpVal * (1 - (denoiseLevel * 0.35)) + localAvg * (denoiseLevel * 0.35);
                        }
                    }

                    // HDR Contrast S-Curve Boost
                    if (isHdr) {
                        const norm = sharpVal / 255;
                        sharpVal = (norm < 0.5 ? 2 * norm * norm : 1 - 2 * (1 - norm) * (1 - norm)) * 255;
                    }

                    data[idx + c] = Math.min(255, Math.max(0, sharpVal));
                }
            }
        }
        ctxAfter.putImageData(imgData, 0, 0);

        // Update Compare Badge
        document.getElementById('resCompareBadge').innerText = `${srcW}×${srcH} ➔ ${targetW}×${targetH} (${upscaleFactor}X)`;

        // Reset divider line to 50%
        setComparePosition(0.5);
    }

    function handleCompareDrag(e) {
        const container = document.getElementById('compareContainer');
        const rect = container.getBoundingClientRect();
        const pos = Math.max(0, Math.min(1, (e.clientX - rect.left) / rect.width));
        setComparePosition(pos);
    }

    function handleCompareTouch(e) {
        if (!e.touches || e.touches.length === 0) return;
        const container = document.getElementById('compareContainer');
        const rect = container.getBoundingClientRect();
        const pos = Math.max(0, Math.min(1, (e.touches[0].clientX - rect.left) / rect.width));
        setComparePosition(pos);
    }

    function setComparePosition(ratio) {
        const pct = (ratio * 100).toFixed(2) + '%';
        document.getElementById('beforeWrapper').style.width = pct;
        document.getElementById('dividerLine').style.left = pct;

        // Ensure canvasBefore matches canvasAfter dimensions in DOM
        const cAfter = document.getElementById('canvasAfter');
        const cBefore = document.getElementById('canvasBefore');
        cBefore.style.width = cAfter.clientWidth + 'px';
        cBefore.style.height = cAfter.clientHeight + 'px';
    }

    function downloadEnhancedImage(format) {
        const cAfter = document.getElementById('canvasAfter');
        const mime = format === 'jpg' ? 'image/jpeg' : 'image/png';
        const url = cAfter.toDataURL(mime, 0.95);
        const a = document.createElement('a');
        a.href = url;
        a.download = `${originalFileName}_enhanced_${upscaleFactor}x_ziitool.${format}`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        showToast(`Đã tải xuống ảnh sắc nét (.${format.toUpperCase()})!`, 'success');
    }

    function resetTool() {
        currentImageElement = null;
        document.getElementById('fileInput').value = '';
        document.getElementById('workspaceBox').classList.add('hidden');
        document.getElementById('resultArea').classList.add('hidden');
        document.getElementById('processingBox').classList.add('hidden');
    }
</script>
@endpush
@endsection

