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
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-rose-100 dark:bg-rose-900/60 text-rose-700 dark:text-rose-300 font-semibold">
                {{ $tool['badge'] ?? 'Giảm đến 90% dung lượng' }}
            </span>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 font-semibold flex items-center gap-1">
                <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> 100% Bảo mật
            </span>
        </div>
        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 max-w-3xl">
            {{ $tool['short_desc'] }} Tối ưu hóa dung lượng ảnh để website tải nhanh hơn, gửi email và lưu trữ nhẹ nhàng.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        
        <!-- Main Tool Workspace (2 cols) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Drag Drop Upload Area -->
            <div id="dropZone" onclick="document.getElementById('fileInput').click()" class="p-8 sm:p-12 rounded-3xl border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-indigo-500 bg-white dark:bg-slate-900 text-center cursor-pointer transition-all group">
                <input type="file" id="fileInput" accept="image/*" class="hidden" onchange="loadSingleImage(this.files[0])">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="minimize-2" class="w-8 h-8"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-200 mb-1">
                    Chọn ảnh để nén giảm dung lượng
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">
                    Kéo thả file JPG, PNG, WebP vào đây
                </p>
                <span class="text-xs text-indigo-600 font-semibold">So sánh Before / After trực quan</span>
            </div>

            <!-- Interactive Compressor Workspace (Hidden until image selected) -->
            <div id="compressorWorkspace" class="hidden space-y-6">

                <!-- Control Bar -->
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300 block">Mức nén mong muốn</span>
                            <span class="text-[11px] text-slate-400">Khuyên dùng: 75% - 85% cho chất lượng tối ưu nhất</span>
                        </div>
                        <span id="qualityLabel" class="text-base font-black text-indigo-600 dark:text-indigo-400 font-mono">80%</span>
                    </div>

                    <input type="range" id="compressQuality" min="10" max="95" value="80" oninput="onQualitySliderChange(this.value)" class="w-full accent-indigo-600 cursor-pointer">

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-2">
                        <button type="button" onclick="setPresetQuality(90)" class="py-2 px-3 text-xs font-medium rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800">
                            Nén Nhẹ (90%)
                        </button>
                        <button type="button" onclick="setPresetQuality(75)" class="py-2 px-3 text-xs font-medium rounded-xl border border-indigo-400 dark:border-indigo-600 text-indigo-600 dark:text-indigo-400 bg-indigo-50/50 dark:bg-indigo-900/30">
                            Cân Bằng (75%)
                        </button>
                        <button type="button" onclick="setPresetQuality(50)" class="py-2 px-3 text-xs font-medium rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800">
                            Nén Tối Đa (50%)
                        </button>
                    </div>
                </div>

                <!-- Stats Summary Box -->
                <div class="p-5 rounded-2xl bg-gradient-to-r from-emerald-500/10 via-indigo-500/10 to-transparent border border-emerald-500/20 grid grid-cols-3 gap-4 text-center">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Gốc (Before)</span>
                        <span id="originalSizeDisplay" class="text-sm font-mono font-bold text-slate-700 dark:text-slate-300">0 KB</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Sau Nén (After)</span>
                        <span id="compressedSizeDisplay" class="text-sm font-mono font-bold text-emerald-600">0 KB</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Tiết Kiệm</span>
                        <span id="savedPercentDisplay" class="text-sm font-mono font-bold text-indigo-600">-0%</span>
                    </div>
                </div>

                <!-- Live Preview Comparison -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-center">
                        <span class="text-xs font-semibold text-slate-500 block mb-2">Ảnh Gốc</span>
                        <div class="h-64 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                            <img id="originalPreview" src="" alt="Ảnh gốc" class="max-h-full max-w-full object-contain">
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-center">
                        <span class="text-xs font-semibold text-emerald-600 block mb-2">Ảnh Đã Nén</span>
                        <div class="h-64 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                            <img id="compressedPreview" src="" alt="Ảnh đã nén" class="max-h-full max-w-full object-contain">
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="flex gap-3">
                    <a id="btnDownloadCompressed" href="" download="compressed.jpg" class="flex-1 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm shadow-md transition flex items-center justify-center gap-2">
                        <i data-lucide="download" class="w-4 h-4"></i>
                        <span>Tải Ảnh Đã Nén Về Máy</span>
                    </a>
                    <button type="button" onclick="document.getElementById('fileInput').click()" class="px-5 py-3.5 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-300">
                        Chọn Ảnh Khác
                    </button>
                </div>

            </div>

            <!-- In-Tool Ad Banner -->
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
                            <div class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center text-xs">
                                <i data-lucide="{{ $relTool['icon'] ?? 'wrench' }}" class="w-4 h-4"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-rose-600 truncate">{{ $relTool['title'] }}</h4>
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
    let activeFile = null;
    let loadedImage = null;

    function formatBytes(bytes) {
        if (!bytes || bytes === 0) return '0 B';
        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }

    function loadSingleImage(file) {
        if (!file || !file.type.startsWith('image/')) {
            showToast('Vui lòng chọn một tệp hình ảnh!', 'error');
            return;
        }

        activeFile = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            const img = new Image();
            img.onload = () => {
                loadedImage = img;
                document.getElementById('originalPreview').src = e.target.result;
                document.getElementById('originalSizeDisplay').innerText = formatBytes(file.size);
                document.getElementById('dropZone').classList.add('hidden');
                document.getElementById('compressorWorkspace').classList.remove('hidden');
                recompressImage();
                showToast('Đã tải ảnh thành công!', 'success');
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    function onQualitySliderChange(val) {
        document.getElementById('qualityLabel').innerText = val + '%';
        debounceRecompress();
    }

    function setPresetQuality(val) {
        document.getElementById('compressQuality').value = val;
        document.getElementById('qualityLabel').innerText = val + '%';
        recompressImage();
    }

    let debounceTimer;
    function debounceRecompress() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(recompressImage, 200);
    }

    function recompressImage() {
        if (!loadedImage) return;

        const quality = parseInt(document.getElementById('compressQuality').value) / 100;
        const canvas = document.createElement('canvas');
        canvas.width = loadedImage.width;
        canvas.height = loadedImage.height;
        const ctx = canvas.getContext('2d');

        // Draw white bg in case of transparent png converting to jpeg
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(loadedImage, 0, 0);

        canvas.toBlob((blob) => {
            if (!blob) return;
            const compressedUrl = URL.createObjectURL(blob);
            document.getElementById('compressedPreview').src = compressedUrl;
            document.getElementById('compressedSizeDisplay').innerText = formatBytes(blob.size);

            const savedBytes = activeFile.size - blob.size;
            const savedPercent = Math.max(0, Math.round((savedBytes / activeFile.size) * 100));
            document.getElementById('savedPercentDisplay').innerText = `-${savedPercent}%`;

            const downloadBtn = document.getElementById('btnDownloadCompressed');
            downloadBtn.href = compressedUrl;
            const baseName = activeFile.name.substring(0, activeFile.name.lastIndexOf('.')) || 'image';
            downloadBtn.download = `${baseName}-compressed.jpg`;
        }, 'image/jpeg', quality);
    }
</script>
@endpush
@endsection

