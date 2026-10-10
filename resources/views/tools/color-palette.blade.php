@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-6">
            <a href="{{ route('home') }}" class="hover:text-indigo-600">Trang chủ</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span>{{ $categoryInfo['name'] ?? 'Đồ họa' }}</span>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span class="text-slate-800 dark:text-slate-200 font-semibold">{{ $tool['title'] }}</span>
        </nav>

        <!-- Tool Header -->
        <div class="mb-8">
            <div class="flex flex-wrap items-center gap-2 mb-2">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">
                    {{ $tool['title'] }}
                </h1>
                <span
                    class="text-xs px-2.5 py-0.5 rounded-full bg-rose-100 dark:bg-rose-900/60 text-rose-700 dark:text-rose-300 font-semibold">
                    {{ $tool['badge'] ?? 'Phân Tích Điểm Ảnh Tự Động' }}
                </span>
                <span
                    class="text-xs px-2.5 py-0.5 rounded-full bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 font-semibold">
                    Xuất CSS & Tailwind
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 max-w-3xl">
                {{ $tool['short_desc'] }} Tải ảnh bất kỳ lên để lấy bảng màu chủ đạo (Color Palette) gồm mã HEX, RGB, HSL
                phục vụ thiết kế UI/UX và đồ họa.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">

            <!-- Controls & Image Upload (1 col) -->
            <div class="space-y-6">

                <!-- Upload Area -->
                <div onclick="document.getElementById('paletteFileInput').click()"
                    class="p-8 rounded-3xl border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-rose-500 bg-white dark:bg-slate-900 text-center cursor-pointer transition group">
                    <input type="file" id="paletteFileInput" accept="image/*" class="hidden"
                        onchange="extractColorsFromImage(this.files[0])">
                    <div
                        class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center group-hover:scale-110 transition">
                        <i data-lucide="palette" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 mb-1">Tải Ảnh Lên Để Lấy Bảng Màu</h3>
                    <p class="text-xs text-slate-400">Ảnh chân dung, phong cảnh, poster, logo</p>
                </div>

                <!-- Uploaded Image Preview Box -->
                <div id="imagePreviewBox"
                    class="hidden p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-center space-y-3">
                    <span class="text-xs font-semibold text-slate-400 block">Ảnh Đã Phân Tích</span>
                    <div
                        class="h-48 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                        <img id="paletteImgPreview" src="" class="max-h-full max-w-full object-contain">
                    </div>
                </div>

                <!-- Number of Colors Slider -->
                <div
                    class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-3">
                    <div class="flex justify-between text-xs font-bold text-slate-700 dark:text-slate-300">
                        <span>Số lượng màu cần trích xuất</span>
                        <span id="colorCountLabel" class="text-rose-600 font-mono">8 Màu</span>
                    </div>
                    <input type="range" id="colorCount" min="4" max="12" value="8"
                        oninput="document.getElementById('colorCountLabel').innerText = this.value + ' Màu'; reExtract()"
                        class="w-full accent-rose-600">
                </div>

                <x-ad-banner placement="sidebar" />

            </div>

            <!-- Palette Results (2 cols) -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Extracted Palette Swatches -->
                <div
                    class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Bảng Màu Chủ Đạo (Nhấp để Copy HEX)
                        </h3>
                        <span class="text-[11px] text-slate-400">1-click copy</span>
                    </div>

                    <div id="paletteGrid" class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div
                            class="p-8 rounded-2xl bg-slate-100 dark:bg-slate-800 text-center col-span-4 text-xs text-slate-400">
                            Chưa có ảnh nào được chọn. Hãy tải ảnh lên để xem bảng màu!
                        </div>
                    </div>
                </div>

                <!-- Export Code Options -->
                <div
                    class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-4">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Xuất Mã Code</h3>
                        <div class="flex gap-1 bg-slate-100 dark:bg-slate-800 p-1 rounded-xl text-xs font-semibold">
                            <button type="button" onclick="setExportCodeType('css')" id="expTab_css"
                                class="px-3 py-1.5 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs">CSS
                                Variables</button>
                            <button type="button" onclick="setExportCodeType('tailwind')" id="expTab_tailwind"
                                class="px-3 py-1.5 rounded-lg text-slate-500 hover:text-slate-900 dark:hover:text-white">Tailwind</button>
                            <button type="button" onclick="setExportCodeType('json')" id="expTab_json"
                                class="px-3 py-1.5 rounded-lg text-slate-500 hover:text-slate-900 dark:hover:text-white">JSON</button>
                        </div>
                    </div>

                    <div class="relative">
                        <textarea id="codeExportArea" readonly
                            class="w-full h-40 p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/60 font-mono text-xs text-slate-800 dark:text-slate-200 focus:outline-none"></textarea>
                        <button type="button" onclick="copyText(document.getElementById('codeExportArea').value)"
                            class="absolute top-3 right-3 px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-sm flex items-center gap-1.5">
                            <i data-lucide="copy" class="w-3.5 h-3.5"></i> Copy Code
                        </button>
                    </div>
                </div>

                <x-ad-banner placement="in_tool" />

            </div>

        </div>

        <!-- SEO Content: How To & FAQ -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-slate-200 dark:border-slate-800 pt-10">
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-lucide="book-open" class="w-4 h-4 text-indigo-600"></i> Hướng Dẫn Sử Dụng
                </h2>
                <ol class="space-y-3 text-xs text-slate-600 dark:text-slate-400 list-decimal list-inside leading-relaxed">
                    @foreach ($tool['how_to'] ?? [] as $step)
                        <li>{{ $step }}</li>
                    @endforeach
                </ol>
            </div>

            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-lucide="help-circle" class="w-4 h-4 text-indigo-600"></i> Câu Hỏi Thường Gặp
                </h2>
                <div class="space-y-3">
                    @foreach ($tool['faq'] ?? [] as $faq)
                        <div
                            class="p-3.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
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
            let activePaletteImage = null;
            let extractedColors = [];
            let currentExportType = 'css';

            function extractColorsFromImage(file) {
                if (!file || !file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = new Image();
                    img.onload = () => {
                        activePaletteImage = img;
                        document.getElementById('paletteImgPreview').src = e.target.result;
                        document.getElementById('imagePreviewBox').classList.remove('hidden');
                        processImagePalette();
                        showToast('Đã trích xuất bảng màu thành công!', 'success');
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }

            function reExtract() {
                if (activePaletteImage) {
                    processImagePalette();
                }
            }

            function processImagePalette() {
                const count = parseInt(document.getElementById('colorCount').value) || 8;
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');

                // Sample down to 100x100 for fast k-means sampling
                canvas.width = 100;
                canvas.height = 100;
                ctx.drawImage(activePaletteImage, 0, 0, 100, 100);

                const imgData = ctx.getImageData(0, 0, 100, 100).data;
                const colorMap = {};

                // Quantize colors (group by 16)
                for (let i = 0; i < imgData.length; i += 16) {
                    const r = Math.round(imgData[i] / 24) * 24;
                    const g = Math.round(imgData[i + 1] / 24) * 24;
                    const b = Math.round(imgData[i + 2] / 24) * 24;
                    const a = imgData[i + 3];

                    if (a < 128) continue; // Skip transparent
                    const hex = rgbToHex(r, g, b);
                    colorMap[hex] = (colorMap[hex] || 0) + 1;
                }

                // Sort by frequency
                const sorted = Object.keys(colorMap).sort((a, b) => colorMap[b] - colorMap[a]);
                extractedColors = sorted.slice(0, count);

                renderSwatches();
                updateCodeExport();
            }

            function rgbToHex(r, g, b) {
                return "#" + (1 << 24 | r << 16 | g << 8 | b).toString(16).slice(1);
            }

            function renderSwatches() {
                const grid = document.getElementById('paletteGrid');
                grid.innerHTML = extractedColors.map(hex => `
            <div onclick="copyText('${hex}', 'Đã sao chép mã màu ${hex}!')" class="group cursor-pointer rounded-2xl p-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 hover:scale-105 transition-all shadow-xs">
                <div class="h-20 rounded-xl mb-2 shadow-inner border border-black/10" style="background-color: ${hex}"></div>
                <div class="text-center">
                    <span class="font-mono font-bold text-xs text-slate-800 dark:text-slate-100 block group-hover:text-rose-500">${hex}</span>
                    <span class="text-[10px] text-slate-400">Click to copy</span>
                </div>
            </div>
        `).join('');
            }

            function setExportCodeType(type) {
                currentExportType = type;
                ['css', 'tailwind', 'json'].forEach(t => {
                    document.getElementById(`expTab_${t}`).className =
                        'px-3 py-1.5 rounded-lg text-slate-500 hover:text-slate-900 dark:hover:text-white';
                });
                document.getElementById(`expTab_${type}`).className =
                    'px-3 py-1.5 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs';
                updateCodeExport();
            }

            function updateCodeExport() {
                if (extractedColors.length === 0) return;
                const area = document.getElementById('codeExportArea');

                if (currentExportType === 'css') {
                    let css = ':root {\n';
                    extractedColors.forEach((hex, i) => {
                        css += `  --color-palette-${i+1}: ${hex};\n`;
                    });
                    css += '}';
                    area.value = css;
                } else if (currentExportType === 'tailwind') {
                    let tw = 'colors: {\n';
                    extractedColors.forEach((hex, i) => {
                        tw += `  'palette-${i+1}': '${hex}',\n`;
                    });
                    tw += '}';
                    area.value = tw;
                } else {
                    area.value = JSON.stringify(extractedColors, null, 2);
                }
            }
        </script>
    @endpush
@endsection
