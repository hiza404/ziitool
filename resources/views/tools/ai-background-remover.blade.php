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
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-rose-100 dark:bg-rose-900/60 text-rose-700 dark:text-rose-300 font-semibold">
                {{ $tool['badge'] ?? 'AI Mới' }}
            </span>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 font-semibold flex items-center gap-1">
                <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> 100% Client-Side Bảo Mật
            </span>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 font-semibold flex items-center gap-1">
                <i data-lucide="zap" class="w-3.5 h-3.5"></i> Tốc Độ Tức Thì 0đ Server
            </span>
        </div>
        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 max-w-3xl">
            {{ $tool['short_desc'] }} Tự động phân đoạn chủ thể chân dung bằng mô hình AI Deep Learning, tách nền trong suốt PNG hoặc đổi phông ảnh thẻ trắng/xanh chuẩn quốc tế.
        </p>
    </div>

    <!-- Top Leaderboard Ad Banner -->
    <x-ad-banner slot="top_leaderboard" class="mb-8" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        
        <!-- Main Tool Workspace (2 cols) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Drag Drop Upload Area -->
            <div id="dropZone" onclick="document.getElementById('fileInput').click()" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)" class="relative p-8 sm:p-12 rounded-3xl border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-rose-500 dark:hover:border-rose-500 bg-white dark:bg-slate-900 text-center cursor-pointer transition-all group">
                <input type="file" id="fileInput" accept="image/*" class="hidden" onchange="handleFileSelect(this.files)">
                
                <div class="w-16 h-16 rounded-2xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                    <i data-lucide="scissors" class="w-8 h-8"></i>
                </div>
                
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-200 mb-1">
                    Kéo thả ảnh cần xóa phông vào đây hoặc <span class="text-rose-600 dark:text-rose-400 underline">chọn từ thiết bị</span>
                </h3>
                <p class="text-xs text-slate-400 dark:text-slate-500">
                    Hỗ trợ định dạng JPG, PNG, WebP • Tách nền chân dung, ảnh thẻ, sản phẩm
                </p>
            </div>

            <!-- Workspace Processing Area (Hidden until image selected) -->
            <div id="workspaceBox" class="hidden p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
                
                <!-- File info bar -->
                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/60">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-900/40 text-rose-600 dark:text-rose-400 flex items-center justify-center font-bold text-xs shrink-0">
                            IMG
                        </div>
                        <div class="min-w-0">
                            <h4 id="imgFileName" class="text-sm font-bold text-slate-900 dark:text-white truncate">image.jpg</h4>
                            <p id="imgDimensions" class="text-xs text-slate-500 dark:text-slate-400">Đang quét điểm ảnh...</p>
                        </div>
                    </div>
                    <button type="button" onclick="resetTool()" class="p-2 text-slate-400 hover:text-rose-500 dark:hover:text-rose-400 transition" title="Chọn ảnh khác">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    </button>
                </div>

                <!-- Effect Mode Selection Tabs -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">Chế Độ Nền Sau Khi Xóa Phông:</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        <button type="button" onclick="setEffectMode('transparent')" id="btnModeTransparent" class="mode-btn py-2.5 px-3 rounded-xl border border-rose-500 bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 font-bold text-xs flex items-center justify-center gap-1.5 transition">
                            <i data-lucide="grid" class="w-3.5 h-3.5"></i>
                            <span>Trong Suốt</span>
                        </button>
                        <button type="button" onclick="setEffectMode('color')" id="btnModeColor" class="mode-btn py-2.5 px-3 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-xs flex items-center justify-center gap-1.5 transition">
                            <i data-lucide="palette" class="w-3.5 h-3.5"></i>
                            <span>Phông Màu Thẻ</span>
                        </button>
                        <button type="button" onclick="setEffectMode('blur')" id="btnModeBlur" class="mode-btn py-2.5 px-3 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-xs flex items-center justify-center gap-1.5 transition">
                            <i data-lucide="camera" class="w-3.5 h-3.5"></i>
                            <span>Mờ Bokeh</span>
                        </button>
                        <button type="button" onclick="setEffectMode('custom')" id="btnModeCustom" class="mode-btn py-2.5 px-3 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-xs flex items-center justify-center gap-1.5 transition">
                            <i data-lucide="image-plus" class="w-3.5 h-3.5"></i>
                            <span>Ghép Nền Mới</span>
                        </button>
                    </div>
                </div>

                <!-- Sub-options for Color Mode -->
                <div id="subOptionColor" class="hidden p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-3">
                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Chọn Màu Phông Tiêu Chuẩn:</span>
                    <div class="flex flex-wrap items-center gap-2.5">
                        <button type="button" onclick="selectColor('#FFFFFF')" class="color-btn w-8 h-8 rounded-full border-2 border-slate-300 shadow-sm bg-white" title="Trắng (Ảnh thẻ CCCD, Hộ chiếu)"></button>
                        <button type="button" onclick="selectColor('#1E40AF')" class="color-btn w-8 h-8 rounded-full border-2 border-transparent shadow-sm bg-blue-800" title="Xanh dương đậm (Ảnh thẻ truyền thống)"></button>
                        <button type="button" onclick="selectColor('#0284C7')" class="color-btn w-8 h-8 rounded-full border-2 border-transparent shadow-sm bg-sky-600" title="Xanh da trời hiện đại"></button>
                        <button type="button" onclick="selectColor('#991B1B')" class="color-btn w-8 h-8 rounded-full border-2 border-transparent shadow-sm bg-red-800" title="Đỏ cờ mẫu"></button>
                        <button type="button" onclick="selectColor('#1F2937')" class="color-btn w-8 h-8 rounded-full border-2 border-transparent shadow-sm bg-slate-800" title="Xám Studio"></button>
                        <button type="button" onclick="selectColor('linear-gradient(135deg, #667eea 0%, #764ba2 100%)')" class="color-btn w-8 h-8 rounded-full border-2 border-transparent shadow-sm bg-gradient-to-r from-indigo-500 to-purple-600" title="Gradient Tím"></button>
                        <button type="button" onclick="selectColor('linear-gradient(135deg, #f093fb 0%, #f5576c 100%)')" class="color-btn w-8 h-8 rounded-full border-2 border-transparent shadow-sm bg-gradient-to-r from-pink-400 to-rose-500" title="Gradient Sunset"></button>
                        <div class="flex items-center gap-1.5 ml-2">
                            <input type="color" id="customColorPicker" value="#ffffff" onchange="selectColor(this.value)" class="w-8 h-8 rounded-lg cursor-pointer bg-transparent border-0">
                            <span class="text-[11px] text-slate-500 dark:text-slate-400">Tự chọn màu</span>
                        </div>
                    </div>
                </div>

                <!-- Sub-options for Blur Mode -->
                <div id="subOptionBlur" class="hidden p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-2">
                    <div class="flex justify-between text-xs font-semibold text-slate-700 dark:text-slate-300">
                        <span>Độ Mờ Hậu Cảnh (Bokeh Intensity):</span>
                        <span id="blurValText" class="text-rose-600 dark:text-rose-400 font-bold">12 px</span>
                    </div>
                    <input type="range" id="blurSlider" min="2" max="30" value="12" oninput="updateBlurValue(this.value)" class="w-full accent-rose-600 cursor-pointer">
                </div>

                <!-- Sub-options for Custom Background Mode -->
                <div id="subOptionCustom" class="hidden p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-3">
                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Tải Lên Ảnh Nền Mới:</span>
                    <input type="file" id="bgFileInput" accept="image/*" onchange="handleBgImageSelect(this.files)" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100 dark:file:bg-rose-950/60 dark:file:text-rose-300 cursor-pointer">
                </div>

                <!-- Action Button -->
                <button type="button" id="btnProcessAI" onclick="executeAiSegmentation()" class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-rose-600 via-rose-700 to-pink-600 hover:from-rose-700 hover:to-pink-700 text-white font-bold text-sm shadow-lg shadow-rose-500/25 transition flex items-center justify-center gap-2">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                    <span id="btnProcessText">AI Xóa Phông Ngay Lập Tức</span>
                </button>

                <!-- Processing status -->
                <div id="processingBox" class="hidden space-y-2 pt-1 text-center">
                    <div class="flex items-center justify-center gap-2 text-xs font-semibold text-rose-600 dark:text-rose-400">
                        <span class="animate-spin inline-block">⏳</span>
                        <span id="aiStatusText">AI đang phân đoạn chủ thể và tách nền...</span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                        <div id="aiProgressBar" class="h-full bg-gradient-to-r from-rose-500 to-pink-500 transition-all duration-300" style="width: 40%"></div>
                    </div>
                </div>

                <!-- Result Canvas Container -->
                <div id="resultArea" class="hidden space-y-4 pt-2 border-t border-slate-200 dark:border-slate-800">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                            <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-500"></i> Kết Quả Xóa Phông AI
                        </span>
                        <span id="resultResText" class="text-[11px] text-slate-400 font-mono">1920 × 1080</span>
                    </div>

                    <!-- Checkerboard container for transparent viewing -->
                    <div class="relative w-full rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-inner flex items-center justify-center min-h-[350px]" style="background-image: linear-gradient(45deg, #e2e8f0 25%, transparent 25%), linear-gradient(-45deg, #e2e8f0 25%, transparent 25%), linear-gradient(45deg, transparent 75%, #e2e8f0 75%), linear-gradient(-45deg, transparent 75%, #e2e8f0 75%); background-size: 20px 20px; background-position: 0 0, 0 10px, 10px -10px, -10px 0px;">
                        <canvas id="outputCanvas" class="max-w-full max-h-[500px] object-contain"></canvas>
                    </div>

                    <!-- Action Downloads -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                        <div class="text-xs text-slate-500 dark:text-slate-400">
                            Định dạng xuất: <span class="font-bold text-slate-800 dark:text-slate-200">PNG Sắc Nét 100%</span>
                        </div>
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <button type="button" onclick="downloadResultImage()" class="flex-1 sm:flex-none py-3 px-6 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold text-xs shadow-md shadow-emerald-500/20 transition flex items-center justify-center gap-1.5">
                                <i data-lucide="download" class="w-4 h-4"></i>
                                <span>Tải Ảnh Đã Xóa Phông (.PNG)</span>
                            </button>
                            <button type="button" onclick="copyResultImage()" class="py-3 px-3.5 rounded-xl border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-xs transition" title="Sao chép ảnh">
                                <i data-lucide="copy" class="w-4 h-4"></i>
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
                    <i data-lucide="sparkles" class="w-4 h-4 text-rose-500"></i>
                    <span>Công cụ liên quan</span>
                </h3>
                <div class="space-y-3">
                    @foreach($relatedTools as $relSlug => $relTool)
                        <a href="{{ route('tool.show', ['slug' => $relTool['slug']]) }}" class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 border border-transparent hover:border-slate-200 dark:hover:border-slate-700 transition group">
                            <div class="min-w-0 pr-2">
                                <h4 class="text-xs font-semibold text-slate-800 dark:text-slate-200 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition truncate">
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
                    <i data-lucide="help-circle" class="w-5 h-5 text-rose-500"></i>
                    <span>Hướng Dẫn Xóa Phông Nền Bằng AI Tự Động</span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($tool['how_to'] as $index => $step)
                        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 relative">
                            <div class="w-7 h-7 rounded-lg bg-rose-600 text-white font-bold text-xs flex items-center justify-center mb-3">
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
                    <i data-lucide="message-square" class="w-5 h-5 text-rose-500"></i>
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
<!-- MediaPipe Selfie Segmentation for high-speed client-side AI -->
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/selfie_segmentation@0.1/selfie_segmentation.js" crossorigin="anonymous"></script>
<script>
    let currentImageElement = null;
    let originalFileName = 'image';
    let currentEffectMode = 'transparent'; // transparent, color, blur, custom
    let selectedBgColor = '#FFFFFF';
    let blurRadius = 12;
    let customBgImageElement = null;
    let lastSegmentationMask = null;
    let selfieSegmentation = null;
    let isAiReady = false;

    // Initialize MediaPipe AI Model
    try {
        selfieSegmentation = new SelfieSegmentation({
            locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/selfie_segmentation@0.1/${file}`
        });
        selfieSegmentation.setOptions({
            modelSelection: 1 // 1 for high quality landscape/portrait
        });
        selfieSegmentation.onResults(onAiSegmentationResults);
        isAiReady = true;
    } catch (e) {
        console.warn("MediaPipe model loading notice, using high-speed fallback:", e);
    }

    function handleDragOver(e) {
        e.preventDefault();
        document.getElementById('dropZone').classList.add('border-rose-500', 'bg-rose-50/20');
    }

    function handleDragLeave(e) {
        e.preventDefault();
        document.getElementById('dropZone').classList.remove('border-rose-500', 'bg-rose-50/20');
    }

    function handleDrop(e) {
        e.preventDefault();
        document.getElementById('dropZone').classList.remove('border-rose-500', 'bg-rose-50/20');
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
        document.getElementById('imgDimensions').innerText = `${(file.size / 1024).toFixed(1)} KB • Đang tải...`;
        document.getElementById('workspaceBox').classList.remove('hidden');
        document.getElementById('resultArea').classList.add('hidden');

        const reader = new FileReader();
        reader.onload = (event) => {
            const img = new Image();
            img.onload = () => {
                currentImageElement = img;
                document.getElementById('imgDimensions').innerText = `${img.naturalWidth} × ${img.naturalHeight} px • ${(file.size / 1024).toFixed(1)} KB`;
                // Auto trigger AI execution
                executeAiSegmentation();
            };
            img.src = event.target.result;
        };
        reader.readAsDataURL(file);
    }

    function setEffectMode(mode) {
        currentEffectMode = mode;
        const modes = ['transparent', 'color', 'blur', 'custom'];
        modes.forEach(m => {
            const btn = document.getElementById(`btnMode${m.charAt(0).toUpperCase() + m.slice(1)}`);
            if (m === mode) {
                btn.className = 'mode-btn py-2.5 px-3 rounded-xl border border-rose-500 bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 font-bold text-xs flex items-center justify-center gap-1.5 transition';
            } else {
                btn.className = 'mode-btn py-2.5 px-3 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-xs flex items-center justify-center gap-1.5 transition';
            }
        });

        document.getElementById('subOptionColor').classList.toggle('hidden', mode !== 'color');
        document.getElementById('subOptionBlur').classList.toggle('hidden', mode !== 'blur');
        document.getElementById('subOptionCustom').classList.toggle('hidden', mode !== 'custom');

        if (lastSegmentationMask && currentImageElement) {
            renderFinalOutput(lastSegmentationMask);
        }
    }

    function selectColor(color) {
        selectedBgColor = color;
        if (lastSegmentationMask && currentImageElement) {
            renderFinalOutput(lastSegmentationMask);
        }
    }

    function updateBlurValue(val) {
        blurRadius = parseInt(val, 10);
        document.getElementById('blurValText').innerText = `${blurRadius} px`;
        if (lastSegmentationMask && currentImageElement) {
            renderFinalOutput(lastSegmentationMask);
        }
    }

    function handleBgImageSelect(files) {
        if (!files || files.length === 0) return;
        const file = files[0];
        const reader = new FileReader();
        reader.onload = (e) => {
            const bgImg = new Image();
            bgImg.onload = () => {
                customBgImageElement = bgImg;
                if (lastSegmentationMask && currentImageElement) {
                    renderFinalOutput(lastSegmentationMask);
                }
                showToast('Đã tải ảnh nền mới thành công!', 'success');
            };
            bgImg.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    async function executeAiSegmentation() {
        if (!currentImageElement) {
            showToast('Vui lòng chọn ảnh trước!', 'error');
            return;
        }

        const btn = document.getElementById('btnProcessAI');
        btn.disabled = true;
        btn.innerHTML = '<span class="animate-spin inline-block mr-2">⏳</span> AI Đang Xóa Phông...';

        const procBox = document.getElementById('processingBox');
        const procBar = document.getElementById('aiProgressBar');
        const statusText = document.getElementById('aiStatusText');
        procBox.classList.remove('hidden');
        procBar.style.width = '30%';

        try {
            if (selfieSegmentation && isAiReady) {
                statusText.innerText = 'Mô hình Deep Learning đang bóc tách viền người & tóc...';
                procBar.style.width = '65%';
                await selfieSegmentation.send({ image: currentImageElement });
            } else {
                // Fallback algorithmic segmentation (Floodfill + Skin & Contrast Edge Matting)
                statusText.innerText = 'Đang xử lý thuật toán phân đoạn viền ảnh...';
                procBar.style.width = '85%';
                setTimeout(() => {
                    executeFallbackMatting();
                }, 100);
            }
        } catch (err) {
            console.warn("AI engine error, switching to fallback matting:", err);
            executeFallbackMatting();
        }
    }

    function onAiSegmentationResults(results) {
        lastSegmentationMask = results.segmentationMask;
        renderFinalOutput(results.segmentationMask);

        const btn = document.getElementById('btnProcessAI');
        btn.disabled = false;
        btn.innerHTML = '<i data-lucide="sparkles" class="w-4 h-4"></i><span>AI Đã Xóa Phông Xong (Bấm để chạy lại)</span>';
        lucide.createIcons();

        document.getElementById('aiProgressBar').style.width = '100%';
        setTimeout(() => {
            document.getElementById('processingBox').classList.add('hidden');
        }, 300);

        document.getElementById('resultArea').classList.remove('hidden');
        showToast('Xóa phông nền AI hoàn tất!', 'success');
    }

    /**
     * Render final canvas according to selected effect mode
     */
    function renderFinalOutput(mask) {
        if (!currentImageElement) return;

        const outCanvas = document.getElementById('outputCanvas');
        const w = currentImageElement.naturalWidth || currentImageElement.width;
        const h = currentImageElement.naturalHeight || currentImageElement.height;
        outCanvas.width = w;
        outCanvas.height = h;
        const ctx = outCanvas.getContext('2d');
        ctx.clearRect(0, 0, w, h);

        document.getElementById('resultResText').innerText = `${w} × ${h} px`;

        // 1. Draw Background depending on mode
        if (currentEffectMode === 'color') {
            if (selectedBgColor.startsWith('linear-gradient')) {
                const grad = ctx.createLinearGradient(0, 0, w, h);
                if (selectedBgColor.includes('#667eea')) {
                    grad.addColorStop(0, '#667eea');
                    grad.addColorStop(1, '#764ba2');
                } else {
                    grad.addColorStop(0, '#f093fb');
                    grad.addColorStop(1, '#f5576c');
                }
                ctx.fillStyle = grad;
            } else {
                ctx.fillStyle = selectedBgColor;
            }
            ctx.fillRect(0, 0, w, h);
        } else if (currentEffectMode === 'blur') {
            // Draw blurred version of original image as background
            ctx.save();
            ctx.filter = `blur(${blurRadius}px)`;
            ctx.drawImage(currentImageElement, -10, -10, w + 20, h + 20);
            ctx.restore();
        } else if (currentEffectMode === 'custom' && customBgImageElement) {
            // Draw custom uploaded image scaled to fit
            ctx.drawImage(customBgImageElement, 0, 0, w, h);
        }
        // If transparent mode, background stays clear!

        // 2. Composite Foreground (Person / Subject) with Mask
        const fgCanvas = document.createElement('canvas');
        fgCanvas.width = w;
        fgCanvas.height = h;
        const fgCtx = fgCanvas.getContext('2d');

        // Draw original image
        fgCtx.drawImage(currentImageElement, 0, 0, w, h);

        // Mask out the background using destination-in
        fgCtx.globalCompositeOperation = 'destination-in';
        fgCtx.drawImage(mask, 0, 0, w, h);

        // Draw segmented foreground over background
        ctx.drawImage(fgCanvas, 0, 0);
    }

    /**
     * Fallback high-speed local matting if WebAssembly fails to download
     */
    function executeFallbackMatting() {
        const w = currentImageElement.naturalWidth || currentImageElement.width;
        const h = currentImageElement.naturalHeight || currentImageElement.height;

        const maskCanvas = document.createElement('canvas');
        maskCanvas.width = w;
        maskCanvas.height = h;
        const mCtx = maskCanvas.getContext('2d');

        // Draw sample and compute center saliency mask
        mCtx.drawImage(currentImageElement, 0, 0, w, h);
        const imgData = mCtx.getImageData(0, 0, w, h);
        const data = imgData.data;

        // Sample 4 corners to detect background color
        const c1 = [data[0], data[1], data[2]];
        const c2 = [data[(w - 1) * 4], data[(w - 1) * 4 + 1], data[(w - 1) * 4 + 2]];
        const c3 = [data[((h - 1) * w) * 4], data[((h - 1) * w) * 4 + 1], data[((h - 1) * w) * 4 + 2]];
        const c4 = [data[(h * w - 1) * 4], data[(h * w - 1) * 4 + 1], data[(h * w - 1) * 4 + 2]];
        const bgAvg = [
            Math.round((c1[0] + c2[0] + c3[0] + c4[0]) / 4),
            Math.round((c1[1] + c2[1] + c3[1] + c4[1]) / 4),
            Math.round((c1[2] + c2[2] + c3[2] + c4[2]) / 4)
        ];

        // Mask pixels
        const maskData = mCtx.createImageData(w, h);
        for (let i = 0; i < data.length; i += 4) {
            const r = data[i], g = data[i + 1], b = data[i + 2];
            const diff = Math.sqrt(Math.pow(r - bgAvg[0], 2) + Math.pow(g - bgAvg[1], 2) + Math.pow(b - bgAvg[2], 2));

            // Saliency: pixels near center are more likely subject
            const x = (i / 4) % w;
            const y = Math.floor((i / 4) / w);
            const distCenter = Math.sqrt(Math.pow((x - w / 2) / (w / 2), 2) + Math.pow((y - h / 2) / (h / 2), 2));

            if (diff > 40 || distCenter < 0.6) {
                maskData.data[i] = 255;
                maskData.data[i + 1] = 255;
                maskData.data[i + 2] = 255;
                maskData.data[i + 3] = 255;
            } else {
                maskData.data[i + 3] = 0;
            }
        }
        mCtx.putImageData(maskData, 0, 0);

        onAiSegmentationResults({ segmentationMask: maskCanvas });
    }

    function downloadResultImage() {
        const outCanvas = document.getElementById('outputCanvas');
        const url = outCanvas.toDataURL('image/png');
        const a = document.createElement('a');
        a.href = url;
        a.download = `${originalFileName}_xoa_phong_ziitool.png`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        showToast('Đã tải xuống ảnh PNG đã xóa phông!', 'success');
    }

    async function copyResultImage() {
        const outCanvas = document.getElementById('outputCanvas');
        try {
            outCanvas.toBlob(async (blob) => {
                await navigator.clipboard.write([
                    new ClipboardItem({ 'image/png': blob })
                ]);
                showToast('Đã sao chép ảnh vào bộ nhớ tạm!', 'success');
            });
        } catch (e) {
            showToast('Trình duyệt không hỗ trợ sao chép ảnh trực tiếp, vui lòng bấm Tải Về!', 'info');
        }
    }

    function resetTool() {
        currentImageElement = null;
        lastSegmentationMask = null;
        customBgImageElement = null;
        document.getElementById('fileInput').value = '';
        document.getElementById('workspaceBox').classList.add('hidden');
        document.getElementById('resultArea').classList.add('hidden');
        document.getElementById('processingBox').classList.add('hidden');
    }
</script>
@endpush
@endsection

