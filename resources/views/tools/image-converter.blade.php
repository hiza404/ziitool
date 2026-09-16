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
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 font-semibold">
                Client-Side 0đ
            </span>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 font-semibold flex items-center gap-1">
                <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> 100% Bảo mật
            </span>
        </div>
        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 max-w-3xl">
            {{ $tool['short_desc'] }} Ảnh được chuyển đổi trực tiếp trong bộ nhớ trình duyệt, không upload lên máy chủ.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        
        <!-- Main Tool Workspace (2 cols) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Drag Drop Upload Area -->
            <div id="dropZone" onclick="document.getElementById('fileInput').click()" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)" class="relative p-8 sm:p-12 rounded-3xl border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-500 bg-white dark:bg-slate-900 text-center cursor-pointer transition-all group">
                <input type="file" id="fileInput" accept="image/*" multiple class="hidden" onchange="handleFileSelect(this.files)">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="upload-cloud" class="w-8 h-8"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-200 mb-1">
                    Kéo thả ảnh vào đây hoặc nhấp để chọn
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">
                    Hỗ trợ PNG, JPG, JPEG, WebP, SVG, BMP, ICO (Xử lý hàng loạt cùng lúc)
                </p>
                <div class="inline-flex items-center gap-1.5 text-xs text-indigo-600 dark:text-indigo-400 font-semibold">
                    <i data-lucide="zap" class="w-4 h-4"></i> Tốc độ chuyển đổi tức thì
                </div>
            </div>

            <!-- Conversion Settings -->
            <div id="settingsBox" class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-5">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="sliders" class="w-4 h-4 text-indigo-600"></i> Cấu Hình Chuyển Đổi
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Định dạng xuất</label>
                        <select id="targetFormat" onchange="updateConvertEstimates()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-medium focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="webp" selected>WebP (Tối ưu hóa web, nhẹ nhất)</option>
                            <option value="png">PNG (Chất lượng không nén, hỗ trợ trong suốt)</option>
                            <option value="jpeg">JPEG / JPG (Phổ biến)</option>
                            <option value="bmp">BMP (Bitmap tiêu chuẩn)</option>
                        </select>
                    </div>

                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            <span>Chất lượng ảnh xuất</span>
                            <span id="qualityVal" class="text-indigo-600 dark:text-indigo-400 font-mono">90%</span>
                        </div>
                        <input type="range" id="qualitySlider" min="10" max="100" value="90" oninput="document.getElementById('qualityVal').innerText = this.value + '%'" class="w-full accent-indigo-600 cursor-pointer">
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="startConvertAll()" id="btnConvert" class="flex-1 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-indigo-500/20 transition flex items-center justify-center gap-2">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                        <span>Chuyển Đổi Toàn Bộ Ảnh</span>
                    </button>
                    <button type="button" onclick="clearFiles()" class="px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-semibold transition">
                        Xóa tất cả
                    </button>
                </div>
            </div>

            <!-- Converted Results List -->
            <div id="resultsBox" class="space-y-3"></div>

            <!-- In-Tool Ad Banner -->
            <x-ad-banner slot="in_tool" />

        </div>

        <!-- Sidebar (1 col) -->
        <div class="space-y-6">
            
            <!-- Sidebar Ad Banner -->
            <x-ad-banner slot="sidebar" />

            <!-- Related Tools -->
            <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Công Cụ Cùng Nhóm</h3>
                <div class="space-y-3">
                    @foreach($relatedTools as $relTool)
                        <a href="{{ route('tool.show', ['slug' => $relTool['slug']]) }}" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition group">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs">
                                <i data-lucide="{{ $relTool['icon'] ?? 'wrench' }}" class="w-4 h-4"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-indigo-600 truncate">{{ $relTool['title'] }}</h4>
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
    let selectedFiles = [];

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
        handleDragLeave(e);
        if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
            handleFileSelect(e.dataTransfer.files);
        }
    }

    function handleFileSelect(files) {
        const imageFiles = Array.from(files).filter(f => f.type.startsWith('image/'));
        if (imageFiles.length === 0) {
            showToast('Vui lòng chọn tệp hình ảnh hợp lệ!', 'error');
            return;
        }
        selectedFiles = [...selectedFiles, ...imageFiles];
        renderFileList();
        showToast(`Đã thêm ${imageFiles.length} ảnh vào danh sách!`, 'success');
    }

    function formatBytes(bytes) {
        if (bytes === 0) return '0 B';
        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }

    function renderFileList() {
        const box = document.getElementById('resultsBox');
        if (selectedFiles.length === 0) {
            box.innerHTML = '';
            return;
        }

        box.innerHTML = selectedFiles.map((file, idx) => `
            <div id="fileItem_${idx}" class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center font-bold text-xs text-indigo-600">
                        IMG
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate">${file.name}</div>
                        <div class="text-[11px] text-slate-400 font-mono">${formatBytes(file.size)} • ${file.type || 'image'}</div>
                    </div>
                </div>
                <div class="flex items-center gap-2" id="actionBtn_${idx}">
                    <span class="text-[11px] text-amber-500 font-medium">Sẵn sàng</span>
                    <button onclick="removeFile(${idx})" class="p-1 text-slate-400 hover:text-rose-500">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        `).join('');

        lucide.createIcons();
    }

    function removeFile(idx) {
        selectedFiles.splice(idx, 1);
        renderFileList();
    }

    function clearFiles() {
        selectedFiles = [];
        renderFileList();
        showToast('Đã xóa danh sách tệp!', 'info');
    }

    async function startConvertAll() {
        if (selectedFiles.length === 0) {
            showToast('Chưa có ảnh nào được chọn. Hãy chọn ảnh trước!', 'error');
            return;
        }

        const format = document.getElementById('targetFormat').value;
        const quality = parseInt(document.getElementById('qualitySlider').value) / 100;
        const mimeType = format === 'jpeg' ? 'image/jpeg' : (format === 'webp' ? 'image/webp' : 'image/png');

        showToast('Đang chuyển đổi...', 'info');

        for (let i = 0; i < selectedFiles.length; i++) {
            const file = selectedFiles[i];
            const actionContainer = document.getElementById(`actionBtn_${i}`);
            if (actionContainer) {
                actionContainer.innerHTML = `<span class="text-[11px] text-indigo-600 animate-pulse">Đang xử lý...</span>`;
            }

            try {
                const convertedBlob = await convertImageBlob(file, mimeType, quality);
                const originalNameWithoutExt = file.name.substring(0, file.name.lastIndexOf('.')) || file.name;
                const newFileName = `${originalNameWithoutExt}.${format}`;
                const downloadUrl = URL.createObjectURL(convertedBlob);

                if (actionContainer) {
                    actionContainer.innerHTML = `
                        <div class="text-right mr-2">
                            <span class="text-[11px] font-mono text-emerald-600 block">${formatBytes(convertedBlob.size)}</span>
                            <span class="text-[10px] text-slate-400 uppercase font-semibold">${format}</span>
                        </div>
                        <a href="${downloadUrl}" download="${newFileName}" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold flex items-center gap-1 shadow-sm">
                            <i data-lucide="download" class="w-3.5 h-3.5"></i> Tải Về
                        </a>
                    `;
                    lucide.createIcons();
                }
            } catch (err) {
                if (actionContainer) {
                    actionContainer.innerHTML = `<span class="text-[11px] text-rose-500">Lỗi chuyển đổi</span>`;
                }
            }
        }

        showToast('Đã chuyển đổi hoàn tất!', 'success');
    }

    function convertImageBlob(file, mimeType, quality) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.onload = () => {
                const canvas = document.createElement('canvas');
                canvas.width = img.width;
                canvas.height = img.height;
                const ctx = canvas.getContext('2d');

                // Fill white bg if converting to JPEG
                if (mimeType === 'image/jpeg') {
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                }

                ctx.drawImage(img, 0, 0);
                canvas.toBlob((blob) => {
                    if (blob) resolve(blob);
                    else reject(new Error('Canvas toBlob failed'));
                }, mimeType, quality);
            };
            img.onerror = reject;
            img.src = URL.createObjectURL(file);
        });
    }

    function updateConvertEstimates() {}
</script>
@endpush
@endsection
