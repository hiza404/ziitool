@extends('layouts.app')

@section('content')
<!-- ZiiPhoto Studio: Chỉnh Sửa, Sửa Ảnh & Phục Dựng Ảnh Chuyên Nghiệp -->
<div id="photoStudioRoot" class="fixed inset-0 z-50 w-screen h-screen overflow-hidden bg-[#0a0c0e] text-slate-100 flex flex-col font-sans select-none">
    
    <!-- 1. TOP BAR (Thanh điều hướng & Thao tác chính) -->
    <header class="h-14 bg-[#141619] border-b border-[#24272e] px-4 flex items-center justify-between shrink-0 z-30">
        <!-- Left: Logo, Title & File Change -->
        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" class="flex items-center gap-2 group mr-1" title="Quay về Trang Chủ ZiiTool">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-purple-600 via-indigo-600 to-pink-500 flex items-center justify-center text-white font-extrabold text-sm shadow-md group-hover:scale-105 transition">
                    Z
                </div>
                <div class="hidden sm:flex items-center gap-1.5">
                    <span class="font-extrabold text-base tracking-tight text-white">ZiiTool</span>
                    <span class="text-[10px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded-md bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-sm">
                        Photo Studio
                    </span>
                    <h1 class="sr-only">{{ $tool['title'] }}</h1>
                </div>
            </a>

            <div class="h-5 w-[1px] bg-[#24272e] hidden sm:block"></div>

            <!-- Image File Name (Editable) -->
            <div class="relative group">
                <input type="text" id="photoTitleInput" value="anh_chinh_sua_ziitool" class="bg-transparent hover:bg-[#1f2228] focus:bg-[#1f2228] border border-transparent hover:border-[#343842] focus:border-purple-500 px-2.5 py-1 rounded-lg text-xs sm:text-sm font-semibold text-white focus:outline-none transition max-w-[140px] sm:max-w-[220px] truncate" title="Bấm để đổi tên file khi tải về">
            </div>

            <!-- Button: Upload/Change Image -->
            <button type="button" onclick="document.getElementById('hiddenFileInput').click()" class="hidden md:flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-[#1f2228] hover:bg-[#282c35] text-slate-300 hover:text-white border border-[#343842] text-xs font-semibold transition" title="Tải ảnh khác từ máy tính">
                <i data-lucide="image-plus" class="w-3.5 h-3.5 text-purple-400"></i>
                <span>Đổi ảnh</span>
            </button>
            <input type="file" id="hiddenFileInput" accept="image/*" class="hidden" onchange="handleFileSelect(this.files)">
        </div>

        <!-- Center: History (Undo/Redo) & Auto-save Status -->
        <div class="flex items-center gap-1">
            <button type="button" onclick="undoAction()" class="p-2 rounded-lg hover:bg-[#1f2228] text-slate-400 hover:text-white transition" title="Hoàn tác (Ctrl+Z)">
                <i data-lucide="undo-2" class="w-4 h-4"></i>
            </button>
            <button type="button" onclick="redoAction()" class="p-2 rounded-lg hover:bg-[#1f2228] text-slate-400 hover:text-white transition" title="Làm lại (Ctrl+Y)">
                <i data-lucide="redo-2" class="w-4 h-4"></i>
            </button>
            <div class="hidden lg:flex items-center gap-1.5 ml-2 text-[11px] text-slate-400 font-medium">
                <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-400"></i>
                <span>Tự động lưu</span>
            </div>
        </div>

        <!-- Right: Compare, Peek, Reset & Download -->
        <div class="flex items-center gap-2">
            <!-- Toggle Before / After Split Slider -->
            <button type="button" id="btnToggleSplitCompare" onclick="toggleSplitCompare()" class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#1f2228] hover:bg-[#282c35] text-slate-300 hover:text-white border border-[#343842] text-xs font-bold transition select-none" title="Bật/tắt thanh trượt chia đôi so sánh Trước và Sau">
                <i data-lucide="columns-2" class="w-3.5 h-3.5 text-cyan-400"></i>
                <span id="splitCompareBtnText">So Sánh Trước/Sau</span>
            </button>

            <!-- Peek Original Image (Hold to peek) -->
            <button type="button" id="btnPeekOriginal" onmousedown="peekOriginal(true)" onmouseup="peekOriginal(false)" ontouchstart="peekOriginal(true)" ontouchend="peekOriginal(false)" class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#1f2228] hover:bg-[#282c35] text-slate-300 hover:text-white border border-[#343842] text-xs font-bold transition select-none" title="Nhấn giữ chuột để xem ảnh gốc ban đầu">
                <i data-lucide="eye" class="w-3.5 h-3.5 text-pink-400"></i>
                <span>Giữ Xem Gốc</span>
            </button>

            <!-- Reset Adjustments -->
            <button type="button" onclick="resetAllPhotoAdjustments()" class="p-2 rounded-xl hover:bg-[#1f2228] text-slate-400 hover:text-amber-400 transition" title="Đặt lại toàn bộ chỉnh sửa về mặc định">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
            </button>

            <!-- Download Dropdown -->
            <div class="relative">
                <button type="button" onclick="toggleDownloadMenu()" class="py-1.5 px-4 rounded-xl bg-gradient-to-r from-purple-600 via-indigo-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 text-white font-extrabold text-xs sm:text-sm shadow-md shadow-purple-900/30 transition flex items-center gap-1.5">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    <span>Tải ảnh</span>
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </button>

                <div id="downloadDropdown" class="hidden absolute right-0 mt-2 w-64 rounded-2xl bg-[#1a1d22] border border-[#343842] shadow-2xl p-3 space-y-2 z-50">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 px-2 pb-1 border-b border-[#24272e]">
                        Định dạng tải ảnh
                    </div>
                    <button type="button" onclick="downloadPhoto('png')" class="w-full text-left p-2 rounded-xl hover:bg-[#252830] flex items-center justify-between transition group">
                        <div>
                            <p class="text-xs font-bold text-white group-hover:text-purple-400">Ảnh PNG Độ Nét Cao</p>
                            <p class="text-[10px] text-slate-400">Độ phân giải nguyên bản 100%</p>
                        </div>
                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-purple-900/50 text-purple-300">Chuẩn HD</span>
                    </button>
                    <button type="button" onclick="downloadPhoto('jpg')" class="w-full text-left p-2 rounded-xl hover:bg-[#252830] flex items-center justify-between transition group">
                        <div>
                            <p class="text-xs font-bold text-white group-hover:text-pink-400">Ảnh JPG Nhẹ (95%)</p>
                            <p class="text-[10px] text-slate-400">Tối ưu dung lượng chia sẻ</p>
                        </div>
                        <span class="text-[10px] text-slate-400">Tiêu chuẩn</span>
                    </button>
                    <button type="button" onclick="downloadPhoto('webp')" class="w-full text-left p-2 rounded-xl hover:bg-[#252830] flex items-center justify-between transition group">
                        <div>
                            <p class="text-xs font-bold text-white group-hover:text-indigo-400">Ảnh WebP Siêu Nén</p>
                            <p class="text-[10px] text-slate-400">Định dạng web hiện đại</p>
                        </div>
                        <span class="text-[10px] text-emerald-400">Web</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- 2. MAIN WORKSPACE (Dock + Drawer + Canvas Artboard) -->
    <div class="flex-1 flex overflow-hidden relative">

        <!-- 2A. LEFT NAVIGATION DOCK (72px Icon Bar) -->
        <aside class="w-[72px] bg-[#0f1114] border-r border-[#1f2228] flex flex-col items-center py-3 gap-1 shrink-0 z-20">
            <!-- 1. Phục Dựng Ảnh Cũ -->
            <button type="button" onclick="selectDockTab('restore')" id="dockBtnRestore" class="dock-btn w-14 h-14 rounded-2xl flex flex-col items-center justify-center gap-1 text-white bg-[#1f2228] transition text-[10px] font-bold" title="Phục Dựng Ảnh Cũ">
                <i data-lucide="history" class="w-5 h-5 text-amber-400"></i>
                <span>Phục Dựng</span>
            </button>

            <!-- 2. Làm Đẹp Chân Dung -->
            <button type="button" onclick="selectDockTab('beauty')" id="dockBtnBeauty" class="dock-btn w-14 h-14 rounded-2xl flex flex-col items-center justify-center gap-1 text-[#868b98] hover:text-white hover:bg-[#1f2228] transition text-[10px] font-semibold" title="Làm Đẹp Chân Dung">
                <i data-lucide="sparkles" class="w-5 h-5 text-pink-400"></i>
                <span>Làm Đẹp</span>
            </button>

            <!-- 3. Chỉnh Màu & Sáng -->
            <button type="button" onclick="selectDockTab('adjust')" id="dockBtnAdjust" class="dock-btn w-14 h-14 rounded-2xl flex flex-col items-center justify-center gap-1 text-[#868b98] hover:text-white hover:bg-[#1f2228] transition text-[10px] font-semibold" title="Chỉnh Màu & Bộ Lọc">
                <i data-lucide="sliders" class="w-5 h-5 text-purple-400"></i>
                <span>Chỉnh Màu</span>
            </button>

            <!-- 4. Cắt & Xoay -->
            <button type="button" onclick="selectDockTab('crop')" id="dockBtnCrop" class="dock-btn w-14 h-14 rounded-2xl flex flex-col items-center justify-center gap-1 text-[#868b98] hover:text-white hover:bg-[#1f2228] transition text-[10px] font-semibold" title="Cắt Cúp & Xoay">
                <i data-lucide="crop" class="w-5 h-5 text-emerald-400"></i>
                <span>Cắt & Xoay</span>
            </button>

            <!-- 5. Sticker Cute -->
            <button type="button" onclick="selectDockTab('stickers')" id="dockBtnStickers" class="dock-btn w-14 h-14 rounded-2xl flex flex-col items-center justify-center gap-1 text-[#868b98] hover:text-white hover:bg-[#1f2228] transition text-[10px] font-semibold" title="Kho Sticker Cute">
                <i data-lucide="smile" class="w-5 h-5 text-yellow-400"></i>
                <span>Sticker</span>
            </button>

            <!-- 6. Chữ & Bút Vẽ -->
            <button type="button" onclick="selectDockTab('text_draw')" id="dockBtnText_draw" class="dock-btn w-14 h-14 rounded-2xl flex flex-col items-center justify-center gap-1 text-[#868b98] hover:text-white hover:bg-[#1f2228] transition text-[10px] font-semibold" title="Chữ & Bút Vẽ Doodle">
                <i data-lucide="pen-tool" class="w-5 h-5 text-cyan-400"></i>
                <span>Chữ & Vẽ</span>
            </button>
        </aside>

        <!-- 2B. EXPANDING DRAWER PANEL (340px) -->
        <div id="drawerPanel" class="w-[340px] bg-[#141619] border-r border-[#1f2228] flex flex-col shrink-0 z-10 transition-all duration-200">
            
            <!-- Drawer Header -->
            <div class="h-12 px-4 border-b border-[#1f2228] flex items-center justify-between shrink-0">
                <span id="drawerTitle" class="text-xs font-extrabold uppercase tracking-wider text-slate-200 flex items-center gap-2">
                    <i data-lucide="history" class="w-4 h-4 text-amber-400"></i>
                    <span>Phục Dựng Ảnh Cũ</span>
                </span>
                <button type="button" onclick="toggleDrawer()" class="p-1.5 rounded-lg hover:bg-[#1f2228] text-slate-400 hover:text-white transition" title="Đóng/Mở bảng công cụ">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Drawer Scrollable Content -->
            <div class="flex-1 overflow-y-auto p-4 space-y-5 no-scrollbar">

                <!-- 1. TAB: PHỤC DỰNG ẢNH CŨ (Restore) -->
                <div id="drawerTabRestore" class="drawer-tab space-y-4">
                    <!-- 1-Click Auto Restore Button -->
                    <button type="button" onclick="applyAutoRestore()" class="w-full py-3 px-4 rounded-2xl bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 hover:from-amber-600 hover:to-rose-600 text-white font-extrabold text-xs shadow-lg shadow-orange-950/40 transition flex items-center justify-center gap-2 group">
                        <i data-lucide="wand-2" class="w-4 h-4 group-hover:rotate-12 transition-transform"></i>
                        <span>Tự Động Phục Hồi 1 Chạm</span>
                    </button>
                    <p class="text-[11px] text-slate-400 -mt-2">Tự động khử ố vàng, cân bằng sáng tối và làm nét chi tiết ảnh cũ tức thì.</p>

                    <!-- Sliders for Restore -->
                    <div class="space-y-3.5 pt-2 border-t border-[#24272e]">
                        <!-- Super Sharpness -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-xs text-slate-300">
                                <span class="flex items-center gap-1"><i data-lucide="sparkle" class="w-3 h-3 text-amber-400"></i> Siêu Làm Nét & Khử Mờ:</span>
                                <span id="valSharpness" class="font-bold text-amber-400">0%</span>
                            </div>
                            <input type="range" id="sliderSharpness" min="0" max="100" value="0" oninput="updateRestoreSliders()" class="w-full accent-amber-500 cursor-pointer">
                            <p class="text-[10px] text-slate-500">Tái tạo nét mắt, chân mày, tóc và các đường nét chi tiết bị mờ.</p>
                        </div>

                        <!-- De-Yellowing (Khử ố vàng) -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-xs text-slate-300">
                                <span>Khử Ố Vàng Giấy Cũ:</span>
                                <span id="valDeYellow" class="font-bold text-amber-400">0%</span>
                            </div>
                            <input type="range" id="sliderDeYellow" min="0" max="100" value="0" oninput="updateRestoreSliders()" class="w-full accent-amber-500 cursor-pointer">
                        </div>

                        <!-- Shadow Recovery (Cứu sáng bóng tối) -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-xs text-slate-300">
                                <span>Cứu Sáng Vùng Tối:</span>
                                <span id="valShadows" class="font-bold text-amber-400">0%</span>
                            </div>
                            <input type="range" id="sliderShadows" min="0" max="100" value="0" oninput="updateRestoreSliders()" class="w-full accent-amber-500 cursor-pointer">
                        </div>

                        <!-- Color Vibrance (Phục hồi sắc màu) -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-xs text-slate-300">
                                <span>Phục Hồi Màu Da & Nền:</span>
                                <span id="valVibrance" class="font-bold text-amber-400">0%</span>
                            </div>
                            <input type="range" id="sliderVibrance" min="0" max="100" value="0" oninput="updateRestoreSliders()" class="w-full accent-amber-500 cursor-pointer">
                        </div>
                    </div>

                    <!-- Scratch & Tear Inpainting Healing Brush -->
                    <div class="p-3.5 rounded-2xl bg-[#1a1d22] border border-[#2d313b] space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5 text-xs font-bold text-white">
                                <i data-lucide="brush" class="w-4 h-4 text-orange-400"></i>
                                <span>Cọ Xóa Xước & Vết Rách</span>
                            </div>
                            <button type="button" id="btnToggleScratchBrush" onclick="toggleHealingBrush('scratch')" class="px-2.5 py-1 rounded-lg bg-[#282c35] hover:bg-orange-600 text-white text-[11px] font-bold transition">
                                Bật Cọ
                            </button>
                        </div>
                        <p class="text-[10px] text-slate-400">Bật cọ rồi click hoặc quét trực tiếp lên các vết xước, vết gập để tự động vá điểm ảnh xung quanh liền mạch.</p>
                        
                        <div id="scratchBrushSizeBox" class="hidden space-y-1 pt-1 border-t border-[#24272e]">
                            <div class="flex justify-between text-[11px] text-slate-400">
                                <span>Cỡ cọ xóa:</span>
                                <span id="valScratchBrushSize" class="text-orange-400 font-bold">16 px</span>
                            </div>
                            <input type="range" id="sliderScratchBrushSize" min="6" max="45" value="16" oninput="updateBrushRadius(this.value, 'scratch')" class="w-full accent-orange-500 cursor-pointer">
                        </div>
                    </div>
                </div>

                <!-- 2. TAB: LÀM ĐẸP CHÂN DUNG (Beauty) -->
                <div id="drawerTabBeauty" class="drawer-tab hidden space-y-4">
                    <!-- 1-Click Auto Beauty Button -->
                    <button type="button" onclick="applyAutoBeauty()" class="w-full py-3 px-4 rounded-2xl bg-gradient-to-r from-pink-500 via-rose-500 to-amber-500 hover:from-pink-600 hover:to-rose-600 text-white font-extrabold text-xs shadow-lg shadow-pink-950/40 transition flex items-center justify-center gap-2 group">
                        <i data-lucide="sparkles" class="w-4 h-4 group-hover:rotate-12 transition-transform"></i>
                        <span>Làm Đẹp Tự Động 1 Chạm</span>
                    </button>
                    <p class="text-[11px] text-slate-400 -mt-2">Tự động nhận diện làm mịn da, nâng tông sáng hồng và cân bằng ánh sáng.</p>

                    <!-- Beauty Sliders -->
                    <div class="space-y-3.5 pt-2 border-t border-[#24272e]">
                        <!-- Skin Smoothing (Mịn da) -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-xs text-slate-300">
                                <span>Mịn da (Skin Smoothing):</span>
                                <span id="valSkinSmooth" class="font-bold text-pink-400">0%</span>
                            </div>
                            <input type="range" id="sliderSkinSmooth" min="0" max="100" value="0" oninput="updateBeautySliders()" class="w-full accent-pink-500 cursor-pointer">
                        </div>

                        <!-- Whitening (Trắng da) -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-xs text-slate-300">
                                <span>Trắng da (Whitening):</span>
                                <span id="valSkinTone" class="font-bold text-pink-400">0%</span>
                            </div>
                            <input type="range" id="sliderSkinTone" min="0" max="100" value="0" oninput="updateBeautySliders()" class="w-full accent-pink-500 cursor-pointer">
                        </div>

                        <!-- Rosy Blush (Má hồng) -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-xs text-slate-300">
                                <span>Má hồng (Rosy Blush):</span>
                                <span id="valBlush" class="font-bold text-pink-400">0%</span>
                            </div>
                            <input type="range" id="sliderBlush" min="0" max="100" value="0" oninput="updateBeautySliders()" class="w-full accent-pink-500 cursor-pointer">
                        </div>

                        <!-- Eye Brighten (Sáng mắt) -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-xs text-slate-300">
                                <span>Làm sáng mắt & Nét chân mày:</span>
                                <span id="valEyeBright" class="font-bold text-pink-400">0%</span>
                            </div>
                            <input type="range" id="sliderEyeBright" min="0" max="100" value="0" oninput="updateBeautySliders()" class="w-full accent-pink-500 cursor-pointer">
                        </div>
                    </div>

                    <!-- Blemish Eraser Brush (Cọ xóa mụn) -->
                    <div class="p-3.5 rounded-2xl bg-[#1a1d22] border border-[#2d313b] space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5 text-xs font-bold text-white">
                                <i data-lucide="target" class="w-4 h-4 text-pink-400"></i>
                                <span>Cọ Xóa Mụn & Tàn Nhang</span>
                            </div>
                            <button type="button" id="btnToggleBlemishBrush" onclick="toggleHealingBrush('blemish')" class="px-2.5 py-1 rounded-lg bg-[#282c35] hover:bg-pink-600 text-white text-[11px] font-bold transition">
                                Bật Cọ
                            </button>
                        </div>
                        <p class="text-[10px] text-slate-400">Chấm trực tiếp con trỏ vào nốt mụn, nốt ruồi hoặc vết thâm để xóa sạch và làm mịn tự nhiên.</p>

                        <div id="blemishBrushSizeBox" class="hidden space-y-1 pt-1 border-t border-[#24272e]">
                            <div class="flex justify-between text-[11px] text-slate-400">
                                <span>Cỡ cọ chấm:</span>
                                <span id="valBlemishBrushSize" class="text-pink-400 font-bold">14 px</span>
                            </div>
                            <input type="range" id="sliderBlemishBrushSize" min="5" max="35" value="14" oninput="updateBrushRadius(this.value, 'blemish')" class="w-full accent-pink-500 cursor-pointer">
                        </div>
                    </div>
                </div>

                <!-- 3. TAB: CHỈNH MÀU & BỘ LỌC (Adjust) -->
                <div id="drawerTabAdjust" class="drawer-tab hidden space-y-4">
                    <span class="text-xs font-bold text-slate-300 block">Thanh tinh chỉnh ánh sáng & màu:</span>
                    <div class="space-y-3">
                        <!-- Brightness -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-xs text-slate-300">
                                <span>Độ sáng (Brightness):</span>
                                <span id="valBrightness" class="font-bold text-purple-400">0</span>
                            </div>
                            <input type="range" id="sliderBrightness" min="-50" max="50" value="0" oninput="updateColorSliders()" class="w-full accent-purple-500 cursor-pointer">
                        </div>

                        <!-- Contrast -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-xs text-slate-300">
                                <span>Tương phản (Contrast):</span>
                                <span id="valContrast" class="font-bold text-purple-400">0</span>
                            </div>
                            <input type="range" id="sliderContrast" min="-50" max="50" value="0" oninput="updateColorSliders()" class="w-full accent-purple-500 cursor-pointer">
                        </div>

                        <!-- Saturation -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-xs text-slate-300">
                                <span>Bão hòa màu (Saturation):</span>
                                <span id="valSaturation" class="font-bold text-purple-400">0</span>
                            </div>
                            <input type="range" id="sliderSaturation" min="-50" max="50" value="0" oninput="updateColorSliders()" class="w-full accent-purple-500 cursor-pointer">
                        </div>

                        <!-- Warmth -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-xs text-slate-300">
                                <span>Nhiệt độ màu (Ấm / Lạnh):</span>
                                <span id="valWarmth" class="font-bold text-purple-400">0</span>
                            </div>
                            <input type="range" id="sliderWarmth" min="-50" max="50" value="0" oninput="updateColorSliders()" class="w-full accent-purple-500 cursor-pointer">
                        </div>

                        <!-- Vignette -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-xs text-slate-300">
                                <span>Viền tối nghệ thuật (Vignette):</span>
                                <span id="valVignette" class="font-bold text-purple-400">0%</span>
                            </div>
                            <input type="range" id="sliderVignette" min="0" max="100" value="0" oninput="updateColorSliders()" class="w-full accent-purple-500 cursor-pointer">
                        </div>
                    </div>

                    <!-- Presets: Bộ Lọc Màu Meitu -->
                    <div class="pt-2 border-t border-[#24272e] space-y-2">
                        <span class="text-xs font-bold text-slate-300 block">Bộ Lọc Màu Meitu & Film:</span>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" onclick="setPhotoFilter('none')" class="p-2 rounded-xl bg-[#1a1d22] hover:bg-[#252830] border border-[#2d313b] text-xs font-bold text-white text-center transition">Ảnh Gốc</button>
                            <button type="button" onclick="setPhotoFilter('rosy')" class="p-2 rounded-xl bg-[#1a1d22] hover:bg-[#252830] border border-[#2d313b] text-xs font-bold text-pink-400 text-center transition">Meitu Rosy</button>
                            <button type="button" onclick="setPhotoFilter('korean')" class="p-2 rounded-xl bg-[#1a1d22] hover:bg-[#252830] border border-[#2d313b] text-xs font-bold text-sky-400 text-center transition">Hàn Quốc</button>
                            <button type="button" onclick="setPhotoFilter('japanese')" class="p-2 rounded-xl bg-[#1a1d22] hover:bg-[#252830] border border-[#2d313b] text-xs font-bold text-emerald-400 text-center transition">Nhật Bản</button>
                            <button type="button" onclick="setPhotoFilter('vintage')" class="p-2 rounded-xl bg-[#1a1d22] hover:bg-[#252830] border border-[#2d313b] text-xs font-bold text-amber-400 text-center transition">Vintage Film</button>
                            <button type="button" onclick="setPhotoFilter('sunset')" class="p-2 rounded-xl bg-[#1a1d22] hover:bg-[#252830] border border-[#2d313b] text-xs font-bold text-orange-400 text-center transition">Hoàng Hôn</button>
                            <button type="button" onclick="setPhotoFilter('bw')" class="p-2 rounded-xl bg-[#1a1d22] hover:bg-[#252830] border border-[#2d313b] text-xs font-bold text-slate-300 text-center transition">Trắng Đen</button>
                            <button type="button" onclick="setPhotoFilter('cyberpunk')" class="p-2 rounded-xl bg-[#1a1d22] hover:bg-[#252830] border border-[#2d313b] text-xs font-bold text-fuchsia-400 text-center transition">Cyberpunk</button>
                        </div>
                    </div>
                </div>

                <!-- 4. TAB: CẮT & XOAY (Crop) -->
                <div id="drawerTabCrop" class="drawer-tab hidden space-y-4">
                    <span class="text-xs font-bold text-slate-300 block">Xoay & Lật ảnh:</span>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" onclick="rotatePhoto(90)" class="p-2.5 rounded-xl bg-[#1a1d22] hover:bg-[#252830] border border-[#2d313b] text-xs font-semibold text-white flex flex-col items-center gap-1 transition">
                            <i data-lucide="rotate-cw" class="w-4 h-4 text-emerald-400"></i>
                            <span>Xoay 90°</span>
                        </button>
                        <button type="button" onclick="flipPhoto('x')" class="p-2.5 rounded-xl bg-[#1a1d22] hover:bg-[#252830] border border-[#2d313b] text-xs font-semibold text-white flex flex-col items-center gap-1 transition">
                            <i data-lucide="flip-horizontal" class="w-4 h-4 text-emerald-400"></i>
                            <span>Lật ngang</span>
                        </button>
                        <button type="button" onclick="flipPhoto('y')" class="p-2.5 rounded-xl bg-[#1a1d22] hover:bg-[#252830] border border-[#2d313b] text-xs font-semibold text-white flex flex-col items-center gap-1 transition">
                            <i data-lucide="flip-vertical" class="w-4 h-4 text-emerald-400"></i>
                            <span>Lật dọc</span>
                        </button>
                    </div>

                    <div class="pt-2 border-t border-[#24272e] space-y-2">
                        <span class="text-xs font-bold text-slate-300 block">Cắt ảnh theo tỉ lệ chuẩn:</span>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <button type="button" onclick="cropToRatio('1:1')" class="p-2.5 rounded-xl bg-[#1a1d22] hover:bg-[#252830] border border-[#2d313b] text-left transition">
                                <p class="font-bold text-white">1 : 1 (Vuông)</p>
                                <p class="text-[10px] text-slate-400">Instagram, Avatar</p>
                            </button>
                            <button type="button" onclick="cropToRatio('4:3')" class="p-2.5 rounded-xl bg-[#1a1d22] hover:bg-[#252830] border border-[#2d313b] text-left transition">
                                <p class="font-bold text-white">4 : 3 (Tiêu chuẩn)</p>
                                <p class="text-[10px] text-slate-400">Chân dung ngang</p>
                            </button>
                            <button type="button" onclick="cropToRatio('3:4')" class="p-2.5 rounded-xl bg-[#1a1d22] hover:bg-[#252830] border border-[#2d313b] text-left transition">
                                <p class="font-bold text-white">3 : 4 (Dọc)</p>
                                <p class="text-[10px] text-slate-400">Ảnh thẻ, thẻ căn cước</p>
                            </button>
                            <button type="button" onclick="cropToRatio('9:16')" class="p-2.5 rounded-xl bg-[#1a1d22] hover:bg-[#252830] border border-[#2d313b] text-left transition">
                                <p class="font-bold text-white">9 : 16 (Dọc)</p>
                                <p class="text-[10px] text-slate-400">TikTok, Story</p>
                            </button>
                            <button type="button" onclick="cropToRatio('16:9')" class="p-2.5 rounded-xl bg-[#1a1d22] hover:bg-[#252830] border border-[#2d313b] text-left transition">
                                <p class="font-bold text-white">16 : 9 (Ngang)</p>
                                <p class="text-[10px] text-slate-400">YouTube, Màn hình</p>
                            </button>
                            <button type="button" onclick="resetCrop()" class="p-2.5 rounded-xl bg-[#1a1d22] hover:bg-[#252830] border border-[#2d313b] text-left transition">
                                <p class="font-bold text-amber-400">Khôi phục</p>
                                <p class="text-[10px] text-slate-400">Tỉ lệ gốc ảnh</p>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 5. TAB: KHO STICKER CUTE (Stickers) -->
                <div id="drawerTabStickers" class="drawer-tab hidden space-y-4">
                    <div class="space-y-2">
                        <span class="text-xs font-bold text-slate-300">Kho Sticker Cute</span>
                        <p class="text-[11px] text-slate-400">Bấm vào sticker để dán lên ảnh, kéo thả, phóng to thu nhỏ và xoay tự do:</p>
                        
                        <!-- Stickers Grid -->
                        <div class="grid grid-cols-4 gap-2 pt-1">
                            <button type="button" onclick="addSticker('🎀')" class="p-3 text-2xl rounded-xl bg-[#1a1d22] hover:bg-[#252830] hover:scale-110 transition" title="Nơ xinh">🎀</button>
                            <button type="button" onclick="addSticker('👑')" class="p-3 text-2xl rounded-xl bg-[#1a1d22] hover:bg-[#252830] hover:scale-110 transition" title="Vương miện">👑</button>
                            <button type="button" onclick="addSticker('🐱')" class="p-3 text-2xl rounded-xl bg-[#1a1d22] hover:bg-[#252830] hover:scale-110 transition" title="Tai mèo">🐱</button>
                            <button type="button" onclick="addSticker('🐰')" class="p-3 text-2xl rounded-xl bg-[#1a1d22] hover:bg-[#252830] hover:scale-110 transition" title="Tai thỏ">🐰</button>
                            <button type="button" onclick="addSticker('👓')" class="p-3 text-2xl rounded-xl bg-[#1a1d22] hover:bg-[#252830] hover:scale-110 transition" title="Kính cận">👓</button>
                            <button type="button" onclick="addSticker('🕶️')" class="p-3 text-2xl rounded-xl bg-[#1a1d22] hover:bg-[#252830] hover:scale-110 transition" title="Kính râm">🕶️</button>
                            <button type="button" onclick="addSticker('🌸')" class="p-3 text-2xl rounded-xl bg-[#1a1d22] hover:bg-[#252830] hover:scale-110 transition" title="Hoa đào">🌸</button>
                            <button type="button" onclick="addSticker('🦋')" class="p-3 text-2xl rounded-xl bg-[#1a1d22] hover:bg-[#252830] hover:scale-110 transition" title="Bướm xinh">🦋</button>
                            <button type="button" onclick="addSticker('✨')" class="p-3 text-2xl rounded-xl bg-[#1a1d22] hover:bg-[#252830] hover:scale-110 transition" title="Lấp lánh">✨</button>
                            <button type="button" onclick="addSticker('🔥')" class="p-3 text-2xl rounded-xl bg-[#1a1d22] hover:bg-[#252830] hover:scale-110 transition" title="Ngọn lửa">🔥</button>
                            <button type="button" onclick="addSticker('💋')" class="p-3 text-2xl rounded-xl bg-[#1a1d22] hover:bg-[#252830] hover:scale-110 transition" title="Son môi">💋</button>
                            <button type="button" onclick="addSticker('🌈')" class="p-3 text-2xl rounded-xl bg-[#1a1d22] hover:bg-[#252830] hover:scale-110 transition" title="Cầu vồng">🌈</button>
                            <button type="button" onclick="addSticker('❤️')" class="p-3 text-2xl rounded-xl bg-[#1a1d22] hover:bg-[#252830] hover:scale-110 transition" title="Trái tim">❤️</button>
                            <button type="button" onclick="addSticker('⭐')" class="p-3 text-2xl rounded-xl bg-[#1a1d22] hover:bg-[#252830] hover:scale-110 transition" title="Ngôi sao">⭐</button>
                            <button type="button" onclick="addSticker('😍')" class="p-3 text-2xl rounded-xl bg-[#1a1d22] hover:bg-[#252830] hover:scale-110 transition" title="Mắt tim">😍</button>
                            <button type="button" onclick="addSticker('😎')" class="p-3 text-2xl rounded-xl bg-[#1a1d22] hover:bg-[#252830] hover:scale-110 transition" title="Cool ngầu">😎</button>
                            <button type="button" onclick="addSticker('🥳')" class="p-3 text-2xl rounded-xl bg-[#1a1d22] hover:bg-[#252830] hover:scale-110 transition" title="Tiệc tùng">🥳</button>
                            <button type="button" onclick="addSticker('🦄')" class="p-3 text-2xl rounded-xl bg-[#1a1d22] hover:bg-[#252830] hover:scale-110 transition" title="Kỳ lân">🦄</button>
                            <button type="button" onclick="addSticker('🐾')" class="p-3 text-2xl rounded-xl bg-[#1a1d22] hover:bg-[#252830] hover:scale-110 transition" title="Chân mèo">🐾</button>
                            <button type="button" onclick="addSticker('🍓')" class="p-3 text-2xl rounded-xl bg-[#1a1d22] hover:bg-[#252830] hover:scale-110 transition" title="Dâu tây">🍓</button>
                        </div>
                    </div>
                </div>

                <!-- 6. TAB: CHỮ & BÚT VẼ (Text & Draw) -->
                <div id="drawerTabText_draw" class="drawer-tab hidden space-y-4">
                    <!-- Text Section -->
                    <div class="space-y-2">
                        <span class="text-xs font-bold text-slate-300 block">Thêm văn bản lên ảnh:</span>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" onclick="addTextToPhoto('Tiêu đề đẹp', { fontSize: 36, fontWeight: 'bold' })" class="p-3 rounded-xl bg-[#1a1d22] hover:bg-[#252830] border border-[#2d313b] text-left transition">
                                <p class="font-extrabold text-sm text-white">Chữ Lớn</p>
                                <p class="text-[10px] text-slate-400">Tiêu đề nổi bật</p>
                            </button>
                            <button type="button" onclick="addTextToPhoto('Watermark © ZiiTool', { fontSize: 20, fontStyle: 'italic' })" class="p-3 rounded-xl bg-[#1a1d22] hover:bg-[#252830] border border-[#2d313b] text-left transition">
                                <p class="font-medium text-xs text-slate-300 italic">Chữ Ký</p>
                                <p class="text-[10px] text-slate-400">Bản quyền watermark</p>
                            </button>
                        </div>
                    </div>

                    <!-- Freehand Draw Section -->
                    <div class="pt-3 border-t border-[#24272e] space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-300">Bút vẽ tự do Doodle:</span>
                            <button type="button" id="btnToggleFreeDraw" onclick="toggleFreeDraw()" class="text-xs font-bold px-3 py-1.5 rounded-xl bg-purple-600 text-white hover:bg-purple-700 transition">
                                Bật Bút Vẽ
                            </button>
                        </div>

                        <div id="drawControlsWrapper" class="hidden space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-400">Màu nét vẽ:</span>
                                <input type="color" id="drawColorPicker" value="#ec4899" onchange="updateDrawColor(this.value)" class="w-8 h-8 rounded-lg cursor-pointer bg-transparent border-0">
                            </div>

                            <div class="space-y-1">
                                <div class="flex justify-between text-xs text-slate-400">
                                    <span>Độ dày nét:</span>
                                    <span id="drawSizeText" class="text-purple-400 font-bold">5 px</span>
                                </div>
                                <input type="range" id="drawSizeSlider" min="1" max="40" value="5" oninput="updateDrawSize(this.value)" class="w-full accent-purple-500 cursor-pointer">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- 2C. CENTRAL WORKSPACE AREA -->
        <main class="flex-1 flex flex-col overflow-hidden bg-[#0a0c0e] relative">
            
            <!-- CONTEXTUAL PROPERTY BAR (Khi chọn chữ hoặc sticker) -->
            <div id="contextualToolbar" class="h-11 bg-[#141619] border-b border-[#1f2228] px-4 flex items-center justify-between shrink-0 z-10">
                <div id="dynamicObjectControls" class="flex items-center gap-2 overflow-x-auto no-scrollbar">
                    
                    <!-- Text Selected Controls -->
                    <div id="textControls" class="hidden flex items-center gap-2">
                        <select id="fontFamilySelect" onchange="changeActiveTextFont(this.value)" class="bg-[#1a1d22] border border-[#2d313b] rounded-lg px-2.5 py-1 text-xs text-white focus:outline-none">
                            <option value="Inter, sans-serif">Inter</option>
                            <option value="Montserrat, sans-serif">Montserrat</option>
                            <option value="'Playfair Display', serif">Playfair Display</option>
                            <option value="Pacifico, cursive">Pacifico</option>
                        </select>
                        <div class="flex items-center bg-[#1a1d22] border border-[#2d313b] rounded-lg px-1">
                            <button type="button" onclick="adjustFontSize(-4)" class="p-1 hover:text-purple-400"><i data-lucide="minus" class="w-3 h-3"></i></button>
                            <input type="text" id="fontSizeInput" value="36" onchange="setFontSize(this.value)" class="w-8 text-center bg-transparent text-xs font-bold text-white focus:outline-none">
                            <button type="button" onclick="adjustFontSize(4)" class="p-1 hover:text-purple-400"><i data-lucide="plus" class="w-3 h-3"></i></button>
                        </div>
                        <input type="color" id="textColorPicker" value="#ffffff" onchange="changeActiveTextColor(this.value)" class="w-6 h-6 rounded cursor-pointer bg-transparent border-0" title="Màu chữ">
                        <button type="button" onclick="toggleTextBold()" class="p-1.5 rounded-lg hover:bg-[#252830] text-slate-300 font-bold text-xs" title="In đậm">B</button>
                        <button type="button" onclick="toggleTextItalic()" class="p-1.5 rounded-lg hover:bg-[#252830] text-slate-300 italic text-xs" title="In nghiêng">I</button>
                    </div>

                    <!-- Shape/Sticker Controls -->
                    <div id="stickerControls" class="hidden flex items-center gap-2">
                        <button type="button" onclick="flipActiveObject('x')" class="p-1.5 rounded-lg hover:bg-[#252830] text-slate-300 text-xs flex items-center gap-1" title="Lật ngang">
                            <i data-lucide="flip-horizontal" class="w-3.5 h-3.5"></i>
                            <span class="text-[11px]">Lật X</span>
                        </button>
                        <button type="button" onclick="flipActiveObject('y')" class="p-1.5 rounded-lg hover:bg-[#252830] text-slate-300 text-xs flex items-center gap-1" title="Lật dọc">
                            <i data-lucide="flip-vertical" class="w-3.5 h-3.5"></i>
                            <span class="text-[11px]">Lật Y</span>
                        </button>
                    </div>

                    <!-- Default Prompt -->
                    <div id="canvasDefaultPrompt" class="flex items-center gap-2 text-xs text-slate-400">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5 text-amber-400"></i>
                        <span id="canvasStatusText">Kéo các thanh trượt bên trái để phục dựng hoặc làm đẹp ảnh trực tiếp</span>
                    </div>
                </div>

                <!-- Universal Actions (Opacity, Delete) -->
                <div id="universalObjectActions" class="hidden flex items-center gap-1.5">
                    <div class="flex items-center gap-1.5 px-2 py-0.5 rounded-lg bg-[#1a1d22] border border-[#2d313b] text-xs">
                        <i data-lucide="sun-medium" class="w-3 h-3 text-slate-400"></i>
                        <input type="range" id="opacitySlider" min="10" max="100" value="100" oninput="changeActiveOpacity(this.value)" class="w-14 accent-purple-500 cursor-pointer" title="Độ mờ đục">
                    </div>
                    <button type="button" onclick="duplicateActiveObject()" class="p-1.5 rounded-lg hover:bg-[#252830] text-slate-300" title="Nhân bản (Ctrl+D)">
                        <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                    </button>
                    <button type="button" onclick="deleteActiveObject()" class="p-1.5 rounded-lg hover:bg-rose-900/50 text-slate-400 hover:text-rose-400" title="Xóa (Delete)">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    </button>
                </div>
            </div>

            <!-- CANVAS ARTBOARD VIEWPORT -->
            <div id="artboardScrollArea" class="flex-1 overflow-auto p-4 sm:p-8 flex items-center justify-center relative">
                
                <!-- 1. HERO UPLOAD BOX (Hiện khi chưa có ảnh) -->
                <div id="uploadHeroBox" class="max-w-xl w-full p-8 rounded-3xl bg-[#141619] border-2 border-dashed border-[#2d313b] hover:border-purple-500 transition text-center space-y-6 shadow-2xl">
                    <div class="w-16 h-16 rounded-2xl bg-purple-950/60 text-purple-400 flex items-center justify-center mx-auto shadow-inner">
                        <i data-lucide="upload-cloud" class="w-8 h-8"></i>
                    </div>

                    <div class="space-y-1">
                        <h2 class="text-lg sm:text-xl font-black text-white">
                            Tải Ảnh Lên Để Chỉnh Sửa & Phục Dựng
                        </h2>
                        <p class="text-xs text-slate-400">
                            Kéo thả ảnh vào đây, hoặc chọn ảnh từ máy tính. Hỗ trợ mọi định dạng PNG, JPG, WebP.
                        </p>
                    </div>

                    <div>
                        <button type="button" onclick="document.getElementById('hiddenFileInput').click()" class="py-2.5 px-6 rounded-xl bg-gradient-to-r from-purple-600 via-indigo-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 text-white font-extrabold text-xs shadow-lg shadow-purple-950/50 transition">
                            Chọn Ảnh Từ Máy Tính
                        </button>
                    </div>

                    <!-- Quick Sample Photos -->
                    <div class="pt-4 border-t border-[#1f2228] space-y-2.5">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Hoặc thử ngay với ảnh mẫu có sẵn:</span>
                        <div class="grid grid-cols-3 gap-2.5 text-xs">
                            <button type="button" onclick="loadSamplePhoto('old')" class="p-2.5 rounded-2xl bg-[#1a1d22] hover:bg-[#232730] border border-[#282c35] text-center transition group">
                                <div class="text-2xl mb-1 group-hover:scale-110 transition-transform">📜</div>
                                <p class="font-bold text-white group-hover:text-amber-400">Ảnh Cũ Mờ</p>
                                <p class="text-[10px] text-slate-400">Thử phục dựng</p>
                            </button>
                            <button type="button" onclick="loadSamplePhoto('portrait')" class="p-2.5 rounded-2xl bg-[#1a1d22] hover:bg-[#232730] border border-[#282c35] text-center transition group">
                                <div class="text-2xl mb-1 group-hover:scale-110 transition-transform">👩</div>
                                <p class="font-bold text-white group-hover:text-pink-400">Ảnh Chân Dung</p>
                                <p class="text-[10px] text-slate-400">Thử làm đẹp da</p>
                            </button>
                            <button type="button" onclick="loadSamplePhoto('landscape')" class="p-2.5 rounded-2xl bg-[#1a1d22] hover:bg-[#232730] border border-[#282c35] text-center transition group">
                                <div class="text-2xl mb-1 group-hover:scale-110 transition-transform">🌄</div>
                                <p class="font-bold text-white group-hover:text-cyan-400">Ảnh Phong Cảnh</p>
                                <p class="text-[10px] text-slate-400">Thử chỉnh màu</p>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 2. PHOTO CANVAS CONTAINER (Hiện khi đã nạp ảnh) -->
                <div id="photoCanvasContainer" class="hidden relative select-none shadow-[0_25px_70px_rgba(0,0,0,0.85)] rounded-lg transition-transform duration-100 overflow-hidden bg-black">
                    
                    <!-- Bottom Layer: Original Image (Dùng cho Before / After Split Slider) -->
                    <div id="beforeCanvasWrapper" class="absolute inset-0 overflow-hidden pointer-events-none z-0">
                        <canvas id="beforeCanvas" class="w-full h-full object-contain"></canvas>
                        <div id="badgeBeforeLabel" class="hidden absolute top-3 left-3 px-2.5 py-1 rounded-md bg-black/75 backdrop-blur-md text-white text-[10px] font-extrabold uppercase tracking-wider border border-white/20 shadow-md">
                            Trước (Gốc)
                        </div>
                    </div>

                    <!-- Top Layer: Edited Photo + Fabric.js Artboard -->
                    <div id="afterCanvasWrapper" class="relative z-10 overflow-hidden">
                        <canvas id="canvaMainCanvas"></canvas>
                        <div id="badgeAfterLabel" class="hidden absolute top-3 right-3 px-2.5 py-1 rounded-md bg-purple-600/90 backdrop-blur-md text-white text-[10px] font-extrabold uppercase tracking-wider border border-purple-400/30 shadow-md">
                            Sau (Đã Phục Dựng)
                        </div>
                    </div>

                    <!-- Split Comparison Divider Line & Draggable Handle -->
                    <div id="splitDividerLine" class="hidden absolute top-0 bottom-0 w-0.5 bg-white z-20 shadow-2xl cursor-ew-resize flex items-center justify-center pointer-events-auto select-none" style="left: 50%;">
                        <div class="w-8 h-8 rounded-full bg-white text-slate-900 shadow-2xl flex items-center justify-center text-xs font-black -ml-4 hover:scale-110 transition-transform">
                            <i data-lucide="chevrons-left-right" class="w-4 h-4"></i>
                        </div>
                    </div>

                    <!-- Custom Brush Cursor Indicator -->
                    <div id="brushCursorCircle" class="hidden pointer-events-none fixed rounded-full border-2 border-white bg-white/20 shadow-lg z-50 transform -translate-x-1/2 -translate-y-1/2"></div>
                </div>

            </div>

            <!-- 3. FOOTER BAR (Zoom & Photo Info) -->
            <footer class="h-10 bg-[#0f1114] border-t border-[#1f2228] px-4 flex items-center justify-between text-xs text-slate-400 shrink-0 z-10">
                <div class="flex items-center gap-3">
                    <span id="photoDimensionBadge" class="text-[11px] font-semibold text-slate-300">Chưa nạp ảnh</span>
                    <span id="brushActiveBadge" class="hidden text-[10px] font-bold px-2 py-0.5 rounded-full bg-orange-950 text-orange-400 border border-orange-800">
                        Cọ vá đang bật - Click lên ảnh để xóa
                    </span>
                </div>

                <!-- Zoom Controls -->
                <div class="flex items-center gap-2">
                    <button type="button" onclick="adjustCanvasZoom(-0.1)" class="p-1 hover:text-white"><i data-lucide="minus" class="w-3.5 h-3.5"></i></button>
                    <span id="zoomPercentText" class="w-12 text-center text-[11px] font-bold text-slate-200">100%</span>
                    <button type="button" onclick="adjustCanvasZoom(0.1)" class="p-1 hover:text-white"><i data-lucide="plus" class="w-3.5 h-3.5"></i></button>
                    <button type="button" onclick="fitCanvasToScreen()" class="ml-2 px-2 py-0.5 rounded bg-[#1a1d22] hover:bg-[#252830] text-[10px] font-semibold text-slate-300 hover:text-white transition">Vừa khung</button>
                </div>
            </footer>
        </main>
    </div>

</div>

@push('scripts')
<!-- Fabric.js library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
<script>
    /**
     * ZIIPHOTO STUDIO: CLIENT-SIDE PHOTO EDITING & RESTORATION ENGINE
     */
    let canvas = null;
    let baseRawImage = null; // Original HTMLImageElement
    let originalCanvas = null; // Offscreen original canvas
    let workingCanvas = null;  // Offscreen processed canvas
    let currentZoom = 1.0;
    let isSplitCompareActive = false;
    let splitPositionPercent = 50;
    let activeDockTab = 'restore';
    let currentBrushMode = 'none'; // 'none', 'scratch', 'blemish'
    let currentBrushRadius = 16;
    let isDrawing = false;
    let isDraggingSplit = false;

    // Undo / Redo Stack
    let historyStack = [];
    let historyIndex = -1;
    let isRecordingHistory = true;

    // Global Photo Adjustments State
    let photoParams = {
        sharpness: 0,
        deYellow: 0,
        shadows: 0,
        vibrance: 0,
        skinSmooth: 0,
        whitening: 0,
        blush: 0,
        eyeBright: 0,
        brightness: 0,
        contrast: 0,
        saturation: 0,
        warmth: 0,
        vignette: 0,
        filter: 'none',
        rotation: 0,
        flipX: false,
        flipY: false
    };

    document.addEventListener('DOMContentLoaded', () => {
        initStudio();
    });

    function initStudio() {
        // Initialize Fabric.js canvas
        canvas = new fabric.Canvas('canvaMainCanvas', {
            preserveObjectStacking: true,
            selection: true
        });

        // Styling selection handles
        fabric.Object.prototype.transparentCorners = false;
        fabric.Object.prototype.cornerColor = '#ffffff';
        fabric.Object.prototype.cornerStrokeColor = '#a855f7';
        fabric.Object.prototype.borderColor = '#a855f7';
        fabric.Object.prototype.cornerSize = 10;
        fabric.Object.prototype.cornerStyle = 'circle';
        fabric.Object.prototype.borderScaleFactor = 1.5;

        // Bind fabric selection events
        canvas.on('selection:created', onObjectSelected);
        canvas.on('selection:updated', onObjectSelected);
        canvas.on('selection:cleared', onObjectDeselected);
        canvas.on('mouse:down', onCanvasMouseDown);
        canvas.on('mouse:move', onCanvasMouseMove);

        // Global shortcuts
        window.addEventListener('keydown', handleGlobalKeydown);
        window.addEventListener('mouseup', () => {
            if (isDraggingSplit) {
                isDraggingSplit = false;
            }
        });
        window.addEventListener('mousemove', handleSplitDragging);

        // Auto load sample old photo so user has an immediate hands-on experience
        loadSamplePhoto('old');
    }

    /**
     * File Drag & Drop and Upload
     */
    function handleFileSelect(files) {
        if (!files || !files.length) return;
        const file = files[0];
        if (!file.type.startsWith('image/')) {
            showToast('Vui lòng chọn một tệp hình ảnh!', 'error');
            return;
        }

        const fileName = file.name.replace(/\.[^/.]+$/, '');
        document.getElementById('photoTitleInput').value = fileName;

        const reader = new FileReader();
        reader.onload = (e) => {
            loadPhotoFromDataUrl(e.target.result);
        };
        reader.readAsDataURL(file);
    }

    /**
     * Load Photo into Canvas
     */
    function loadPhotoFromDataUrl(dataUrl) {
        const img = new Image();
        img.crossOrigin = 'anonymous';
        img.onload = () => {
            baseRawImage = img;

            // Setup original canvas
            originalCanvas = document.createElement('canvas');
            originalCanvas.width = img.naturalWidth || img.width;
            originalCanvas.height = img.naturalHeight || img.height;
            const origCtx = originalCanvas.getContext('2d');
            origCtx.drawImage(img, 0, 0);

            // Setup working canvas
            workingCanvas = document.createElement('canvas');
            workingCanvas.width = originalCanvas.width;
            workingCanvas.height = originalCanvas.height;
            const workCtx = workingCanvas.getContext('2d');
            workCtx.drawImage(img, 0, 0);

            // Render on beforeCanvas for Before/After split comparison
            const beforeCv = document.getElementById('beforeCanvas');
            beforeCv.width = originalCanvas.width;
            beforeCv.height = originalCanvas.height;
            const bCtx = beforeCv.getContext('2d');
            bCtx.drawImage(img, 0, 0);

            // Display canvas container & hide hero box
            document.getElementById('uploadHeroBox').classList.add('hidden');
            document.getElementById('photoCanvasContainer').classList.remove('hidden');

            // Reset params
            resetAllPhotoAdjustments(false);

            // Fit display container
            fitCanvasDimensions(img.naturalWidth || img.width, img.naturalHeight || img.height);

            // Initial render
            renderWorkingPhotoToCanvas();
            recordHistoryState();
            fitCanvasToScreen();

            showToast('Đã nạp ảnh thành công! Bạn có thể bắt đầu phục dựng & chỉnh sửa.', 'success');
        };
        img.src = dataUrl;
    }

    function fitCanvasDimensions(w, h) {
        const maxDisplaySize = 650;
        const ratio = w / h;
        let dw = maxDisplaySize;
        let dh = maxDisplaySize;

        if (ratio > 1) {
            dh = Math.round(maxDisplaySize / ratio);
        } else {
            dw = Math.round(maxDisplaySize * ratio);
        }

        const container = document.getElementById('photoCanvasContainer');
        container.style.width = dw + 'px';
        container.style.height = dh + 'px';

        canvas.setWidth(dw);
        canvas.setHeight(dh);
        canvas.renderAll();

        document.getElementById('photoDimensionBadge').innerText = `${w} × ${h} px (${ratio > 1 ? 'Ngang' : 'Dọc'})`;
    }

    /**
     * PIXEL RENDERING ENGINE (Restore, Beauty, Color grading)
     */
    function renderWorkingPhotoToCanvas() {
        if (!originalCanvas || !workingCanvas) return;

        const w = workingCanvas.width;
        const h = workingCanvas.height;
        const workCtx = workingCanvas.getContext('2d');

        // Draw current base photo
        workCtx.clearRect(0, 0, w, h);
        workCtx.drawImage(originalCanvas, 0, 0);

        // Get pixel data
        const imgData = workCtx.getImageData(0, 0, w, h);
        const data = imgData.data;
        const len = data.length;

        // Precompute parameters
        const brightness = photoParams.brightness * 1.5;
        const contrastFactor = (259 * (photoParams.contrast * 2 + 255)) / (255 * (259 - photoParams.contrast * 2));
        const deYellow = photoParams.deYellow / 100;
        const shadows = photoParams.shadows / 100;
        const vibrance = photoParams.vibrance / 100;
        const skinSmooth = photoParams.skinSmooth / 100;
        const whitening = photoParams.whitening / 100;
        const blush = photoParams.blush / 100;
        const eyeBright = photoParams.eyeBright / 100;
        const warmth = photoParams.warmth;
        const saturation = photoParams.saturation / 100;

        for (let i = 0; i < len; i += 4) {
            let r = data[i];
            let g = data[i + 1];
            let b = data[i + 2];

            // 1. De-Yellowing (Khử ố vàng giấy cũ)
            if (deYellow > 0) {
                // In yellow areas (high R and G, low B), lift Blue and slightly drop Red/Green
                if (r > 100 && g > 100 && r > b) {
                    const yellowTint = Math.min(r, g) - b;
                    if (yellowTint > 0) {
                        b += yellowTint * deYellow * 0.7;
                        r -= yellowTint * deYellow * 0.15;
                        g -= yellowTint * deYellow * 0.1;
                    }
                }
            }

            // 2. Shadow Boost (Cứu sáng bóng tối ảnh cũ)
            if (shadows > 0) {
                const lum = 0.299 * r + 0.587 * g + 0.114 * b;
                if (lum < 110) {
                    const lift = (110 - lum) * shadows * 0.8;
                    r += lift;
                    g += lift;
                    b += lift;
                }
            }

            // 3. Brightness & Contrast
            if (photoParams.brightness !== 0) {
                r += brightness;
                g += brightness;
                b += brightness;
            }
            if (photoParams.contrast !== 0) {
                r = contrastFactor * (r - 128) + 128;
                g = contrastFactor * (g - 128) + 128;
                b = contrastFactor * (b - 128) + 128;
            }

            // 4. Warmth (Ấm / Lạnh)
            if (warmth !== 0) {
                r += warmth * 0.6;
                b -= warmth * 0.6;
            }

            // 5. Saturation & Vibrance
            const avgLum = 0.299 * r + 0.587 * g + 0.114 * b;
            const satMultiplier = 1 + saturation + vibrance * 0.5;
            r = avgLum + (r - avgLum) * satMultiplier;
            g = avgLum + (g - avgLum) * satMultiplier;
            b = avgLum + (b - avgLum) * satMultiplier;

            // 6. Beauty Retouching (Skin detection & enhancement)
            const isSkin = (r > 95 && g > 40 && b > 20 && (r - g) > 15 && (r - b) > 15);
            if (isSkin) {
                // Whitening (Trắng da hồng hào)
                if (whitening > 0) {
                    r += whitening * 30;
                    g += whitening * 25;
                    b += whitening * 28;
                }
                // Rosy Blush (Má hồng)
                if (blush > 0) {
                    r += blush * 35;
                    b += blush * 10;
                }
            }

            // 7. Eye & High-contrast Brighten
            if (eyeBright > 0 && avgLum > 160) {
                r += eyeBright * 20;
                g += eyeBright * 20;
                b += eyeBright * 20;
            }

            // 8. Filters (Meitu Presets)
            if (photoParams.filter === 'rosy') {
                r *= 1.12;
                b *= 1.05;
                g *= 0.98;
            } else if (photoParams.filter === 'korean') {
                r = r * 1.08 + 10;
                g = g * 1.06 + 10;
                b = b * 1.1 + 15;
            } else if (photoParams.filter === 'japanese') {
                r = r * 0.95 + 15;
                g = g * 1.05 + 15;
                b = b * 1.08 + 20;
            } else if (photoParams.filter === 'vintage') {
                r = r * 1.15 + 10;
                g = g * 0.95 + 5;
                b = b * 0.8;
            } else if (photoParams.filter === 'sunset') {
                r = r * 1.25 + 15;
                g = g * 1.05;
                b = b * 0.85;
            } else if (photoParams.filter === 'bw') {
                const gray = avgLum;
                r = gray;
                g = gray;
                b = gray;
            } else if (photoParams.filter === 'cyberpunk') {
                r = r * 1.2 + 20;
                g = g * 0.8;
                b = b * 1.3 + 30;
            }

            data[i] = Math.max(0, Math.min(255, r));
            data[i + 1] = Math.max(0, Math.min(255, g));
            data[i + 2] = Math.max(0, Math.min(255, b));
        }

        workCtx.putImageData(imgData, 0, 0);

        // 9. Sharpness convolution (Unsharp mask kernel)
        if (photoParams.sharpness > 0) {
            applySharpnessConvolution(workCtx, w, h, photoParams.sharpness / 100);
        }

        // 10. Vignette
        if (photoParams.vignette > 0) {
            const rad = Math.max(w, h) * 0.7;
            const grad = workCtx.createRadialGradient(w / 2, h / 2, rad * 0.4, w / 2, h / 2, rad);
            grad.addColorStop(0, 'rgba(0,0,0,0)');
            grad.addColorStop(1, `rgba(0,0,0,${photoParams.vignette / 100 * 0.75})`);
            workCtx.fillStyle = grad;
            workCtx.fillRect(0, 0, w, h);
        }

        // Update Fabric Canvas background
        const dataUrl = workingCanvas.toDataURL('image/jpeg', 0.95);
        fabric.Image.fromURL(dataUrl, (fabricImg) => {
            fabricImg.set({
                originX: 'left',
                originY: 'top',
                left: 0,
                top: 0,
                scaleX: canvas.width / w,
                scaleY: canvas.height / h,
                selectable: false,
                evented: false
            });

            // Find existing background photo or set as new
            const objs = canvas.getObjects();
            const existingBg = objs.find(o => o.isPhotoBackground);
            if (existingBg) {
                canvas.remove(existingBg);
            }
            fabricImg.isPhotoBackground = true;
            canvas.insertAt(fabricImg, 0);
            canvas.renderAll();
        });
    }

    /**
     * Unsharp Mask Convolution Matrix
     */
    function applySharpnessConvolution(ctx, w, h, strength) {
        const k = strength * 0.75;
        const weights = [
            0, -k, 0,
            -k, 1 + 4 * k, -k,
            0, -k, 0
        ];
        const srcData = ctx.getImageData(0, 0, w, h);
        const src = srcData.data;
        const output = ctx.createImageData(w, h);
        const dst = output.data;

        for (let y = 1; y < h - 1; y++) {
            for (let x = 1; x < w - 1; x++) {
                let r = 0, g = 0, b = 0;
                for (let cy = -1; cy <= 1; cy++) {
                    for (let cx = -1; cx <= 1; cx++) {
                        const idx = ((y + cy) * w + (x + cx)) * 4;
                        const weight = weights[(cy + 1) * 3 + (cx + 1)];
                        r += src[idx] * weight;
                        g += src[idx + 1] * weight;
                        b += src[idx + 2] * weight;
                    }
                }
                const dstIdx = (y * w + x) * 4;
                dst[dstIdx] = Math.max(0, Math.min(255, r));
                dst[dstIdx + 1] = Math.max(0, Math.min(255, g));
                dst[dstIdx + 2] = Math.max(0, Math.min(255, b));
                dst[dstIdx + 3] = src[dstIdx + 3];
            }
        }
        ctx.putImageData(output, 0, 0);
    }

    /**
     * HEALING INPAINTING BRUSH (Xóa xước & Xóa mụn)
     */
    function toggleHealingBrush(mode) {
        if (currentBrushMode === mode) {
            currentBrushMode = 'none';
            document.getElementById('brushActiveBadge').classList.add('hidden');
            document.getElementById('scratchBrushSizeBox').classList.add('hidden');
            document.getElementById('blemishBrushSizeBox').classList.add('hidden');
            document.getElementById('btnToggleScratchBrush').innerText = 'Bật Cọ';
            document.getElementById('btnToggleScratchBrush').className = 'px-2.5 py-1 rounded-lg bg-[#282c35] hover:bg-orange-600 text-white text-[11px] font-bold transition';
            document.getElementById('btnToggleBlemishBrush').innerText = 'Bật Cọ';
            document.getElementById('btnToggleBlemishBrush').className = 'px-2.5 py-1 rounded-lg bg-[#282c35] hover:bg-pink-600 text-white text-[11px] font-bold transition';
            document.getElementById('brushCursorCircle').classList.add('hidden');
            canvas.defaultCursor = 'default';
        } else {
            currentBrushMode = mode;
            document.getElementById('brushActiveBadge').classList.remove('hidden');
            canvas.defaultCursor = 'crosshair';

            if (mode === 'scratch') {
                document.getElementById('scratchBrushSizeBox').classList.remove('hidden');
                document.getElementById('blemishBrushSizeBox').classList.add('hidden');
                document.getElementById('btnToggleScratchBrush').innerText = 'Tắt Cọ';
                document.getElementById('btnToggleScratchBrush').className = 'px-2.5 py-1 rounded-lg bg-orange-600 text-white text-[11px] font-bold shadow-md transition';
                document.getElementById('btnToggleBlemishBrush').innerText = 'Bật Cọ';
                currentBrushRadius = parseInt(document.getElementById('sliderScratchBrushSize').value, 10) || 16;
            } else {
                document.getElementById('blemishBrushSizeBox').classList.remove('hidden');
                document.getElementById('scratchBrushSizeBox').classList.add('hidden');
                document.getElementById('btnToggleBlemishBrush').innerText = 'Tắt Cọ';
                document.getElementById('btnToggleBlemishBrush').className = 'px-2.5 py-1 rounded-lg bg-pink-600 text-white text-[11px] font-bold shadow-md transition';
                document.getElementById('btnToggleScratchBrush').innerText = 'Bật Cọ';
                currentBrushRadius = parseInt(document.getElementById('sliderBlemishBrushSize').value, 10) || 14;
            }

            showToast(`Đã bật ${mode === 'scratch' ? 'Cọ Xóa Xước' : 'Cọ Xóa Mụn'}. Click lên điểm cần xóa trên ảnh!`, 'info');
        }
    }

    function updateBrushRadius(val, mode) {
        currentBrushRadius = parseInt(val, 10);
        if (mode === 'scratch') {
            document.getElementById('valScratchBrushSize').innerText = `${val} px`;
        } else {
            document.getElementById('valBlemishBrushSize').innerText = `${val} px`;
        }
    }

    function onCanvasMouseDown(opt) {
        if (currentBrushMode === 'none' || !originalCanvas) return;

        const pointer = canvas.getPointer(opt.e);
        // Map canvas pointer to original photo coordinates
        const scaleX = originalCanvas.width / canvas.width;
        const scaleY = originalCanvas.height / canvas.height;
        const targetX = Math.round(pointer.x * scaleX);
        const targetY = Math.round(pointer.y * scaleY);
        const radius = Math.round(currentBrushRadius * scaleX);

        applyHealingAtPoint(targetX, targetY, radius);
        renderWorkingPhotoToCanvas();
        recordHistoryState();
    }

    function onCanvasMouseMove(opt) {
        if (currentBrushMode === 'none') {
            document.getElementById('brushCursorCircle').classList.add('hidden');
            return;
        }

        const circle = document.getElementById('brushCursorCircle');
        circle.classList.remove('hidden');
        circle.style.left = opt.e.clientX + 'px';
        circle.style.top = opt.e.clientY + 'px';
        const displayDiameter = currentBrushRadius * 2;
        circle.style.width = displayDiameter + 'px';
        circle.style.height = displayDiameter + 'px';
    }

    function applyHealingAtPoint(cx, cy, r) {
        const ctx = originalCanvas.getContext('2d');
        const w = originalCanvas.width;
        const h = originalCanvas.height;

        const x0 = Math.max(0, cx - r - 6);
        const y0 = Math.max(0, cy - r - 6);
        const x1 = Math.min(w - 1, cx + r + 6);
        const y1 = Math.min(h - 1, cy + r + 6);
        const bw = x1 - x0 + 1;
        const bh = y1 - y0 + 1;

        if (bw <= 0 || bh <= 0) return;

        const imgData = ctx.getImageData(x0, y0, bw, bh);
        const data = imgData.data;

        // Sample surrounding ring of clean pixels
        let sumR = 0, sumG = 0, sumB = 0, count = 0;
        for (let y = 0; y < bh; y++) {
            for (let x = 0; x < bw; x++) {
                const dx = (x0 + x) - cx;
                const dy = (y0 + y) - cy;
                const dist = Math.sqrt(dx * dx + dy * dy);
                if (dist >= r && dist <= r + 5) {
                    const idx = (y * bw + x) * 4;
                    sumR += data[idx];
                    sumG += data[idx + 1];
                    sumB += data[idx + 2];
                    count++;
                }
            }
        }

        if (count === 0) return;
        const avgR = sumR / count;
        const avgG = sumG / count;
        const avgB = sumB / count;

        // Smoothly blend inside circle with cosine falloff
        for (let y = 0; y < bh; y++) {
            for (let x = 0; x < bw; x++) {
                const dx = (x0 + x) - cx;
                const dy = (y0 + y) - cy;
                const dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < r) {
                    const idx = (y * bw + x) * 4;
                    const factor = Math.cos((dist / r) * (Math.PI / 2)); // 1 at center, 0 at border
                    data[idx] = data[idx] * (1 - factor) + avgR * factor;
                    data[idx + 1] = data[idx + 1] * (1 - factor) + avgG * factor;
                    data[idx + 2] = data[idx + 2] * (1 - factor) + avgB * factor;
                }
            }
        }

        ctx.putImageData(imgData, x0, y0);
        showToast('Đã vá mịn điểm ảnh thành công!', 'success');
    }

    /**
     * QUICK RESTORE & BEAUTY PRESETS
     */
    function applyAutoRestore() {
        photoParams.sharpness = 45;
        photoParams.deYellow = 60;
        photoParams.shadows = 40;
        photoParams.contrast = 15;
        photoParams.brightness = 5;
        photoParams.vibrance = 30;

        document.getElementById('sliderSharpness').value = 45;
        document.getElementById('valSharpness').innerText = '45%';
        document.getElementById('sliderDeYellow').value = 60;
        document.getElementById('valDeYellow').innerText = '60%';
        document.getElementById('sliderShadows').value = 40;
        document.getElementById('valShadows').innerText = '40%';
        document.getElementById('sliderVibrance').value = 30;
        document.getElementById('valVibrance').innerText = '30%';

        renderWorkingPhotoToCanvas();
        recordHistoryState();
        showToast('Đã phục hồi ảnh cũ tự động! Nhấn "So Sánh Trước/Sau" để xem sự khác biệt.', 'success');
    }

    function applyAutoBeauty() {
        photoParams.skinSmooth = 50;
        photoParams.whitening = 35;
        photoParams.blush = 25;
        photoParams.eyeBright = 30;

        document.getElementById('sliderSkinSmooth').value = 50;
        document.getElementById('valSkinSmooth').innerText = '50%';
        document.getElementById('sliderSkinTone').value = 35;
        document.getElementById('valSkinTone').innerText = '35%';
        document.getElementById('sliderBlush').value = 25;
        document.getElementById('valBlush').innerText = '25%';
        document.getElementById('sliderEyeBright').value = 30;
        document.getElementById('valEyeBright').innerText = '30%';

        renderWorkingPhotoToCanvas();
        recordHistoryState();
        showToast('Đã làm đẹp da 1 chạm tự nhiên!', 'success');
    }

    function updateRestoreSliders() {
        photoParams.sharpness = parseInt(document.getElementById('sliderSharpness').value, 10);
        photoParams.deYellow = parseInt(document.getElementById('sliderDeYellow').value, 10);
        photoParams.shadows = parseInt(document.getElementById('sliderShadows').value, 10);
        photoParams.vibrance = parseInt(document.getElementById('sliderVibrance').value, 10);

        document.getElementById('valSharpness').innerText = photoParams.sharpness + '%';
        document.getElementById('valDeYellow').innerText = photoParams.deYellow + '%';
        document.getElementById('valShadows').innerText = photoParams.shadows + '%';
        document.getElementById('valVibrance').innerText = photoParams.vibrance + '%';

        renderWorkingPhotoToCanvas();
    }

    function updateBeautySliders() {
        photoParams.skinSmooth = parseInt(document.getElementById('sliderSkinSmooth').value, 10);
        photoParams.whitening = parseInt(document.getElementById('sliderSkinTone').value, 10);
        photoParams.blush = parseInt(document.getElementById('sliderBlush').value, 10);
        photoParams.eyeBright = parseInt(document.getElementById('sliderEyeBright').value, 10);

        document.getElementById('valSkinSmooth').innerText = photoParams.skinSmooth + '%';
        document.getElementById('valSkinTone').innerText = photoParams.whitening + '%';
        document.getElementById('valBlush').innerText = photoParams.blush + '%';
        document.getElementById('valEyeBright').innerText = photoParams.eyeBright + '%';

        renderWorkingPhotoToCanvas();
    }

    function updateColorSliders() {
        photoParams.brightness = parseInt(document.getElementById('sliderBrightness').value, 10);
        photoParams.contrast = parseInt(document.getElementById('sliderContrast').value, 10);
        photoParams.saturation = parseInt(document.getElementById('sliderSaturation').value, 10);
        photoParams.warmth = parseInt(document.getElementById('sliderWarmth').value, 10);
        photoParams.vignette = parseInt(document.getElementById('sliderVignette').value, 10);

        document.getElementById('valBrightness').innerText = photoParams.brightness;
        document.getElementById('valContrast').innerText = photoParams.contrast;
        document.getElementById('valSaturation').innerText = photoParams.saturation;
        document.getElementById('valWarmth').innerText = photoParams.warmth;
        document.getElementById('valVignette').innerText = photoParams.vignette + '%';

        renderWorkingPhotoToCanvas();
    }

    function setPhotoFilter(filterName) {
        photoParams.filter = filterName;
        renderWorkingPhotoToCanvas();
        recordHistoryState();
        showToast(`Đã áp dụng bộ lọc ${filterName}!`, 'info');
    }

    function resetAllPhotoAdjustments(shouldRender = true) {
        photoParams = {
            sharpness: 0,
            deYellow: 0,
            shadows: 0,
            vibrance: 0,
            skinSmooth: 0,
            whitening: 0,
            blush: 0,
            eyeBright: 0,
            brightness: 0,
            contrast: 0,
            saturation: 0,
            warmth: 0,
            vignette: 0,
            filter: 'none',
            rotation: 0,
            flipX: false,
            flipY: false
        };

        const rangeIds = [
            'sliderSharpness', 'sliderDeYellow', 'sliderShadows', 'sliderVibrance',
            'sliderSkinSmooth', 'sliderSkinTone', 'sliderBlush', 'sliderEyeBright',
            'sliderBrightness', 'sliderContrast', 'sliderSaturation', 'sliderWarmth', 'sliderVignette'
        ];
        rangeIds.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = 0;
        });

        document.getElementById('valSharpness').innerText = '0%';
        document.getElementById('valDeYellow').innerText = '0%';
        document.getElementById('valShadows').innerText = '0%';
        document.getElementById('valVibrance').innerText = '0%';
        document.getElementById('valSkinSmooth').innerText = '0%';
        document.getElementById('valSkinTone').innerText = '0%';
        document.getElementById('valBlush').innerText = '0%';
        document.getElementById('valEyeBright').innerText = '0%';
        document.getElementById('valBrightness').innerText = '0';
        document.getElementById('valContrast').innerText = '0';
        document.getElementById('valSaturation').innerText = '0';
        document.getElementById('valWarmth').innerText = '0';
        document.getElementById('valVignette').innerText = '0%';

        if (shouldRender) {
            renderWorkingPhotoToCanvas();
            recordHistoryState();
            showToast('Đã đặt lại thông số chỉnh sửa.', 'info');
        }
    }

    /**
     * BEFORE / AFTER SPLIT COMPARISON SLIDER
     */
    function toggleSplitCompare() {
        isSplitCompareActive = !isSplitCompareActive;
        const divider = document.getElementById('splitDividerLine');
        const badgeBefore = document.getElementById('badgeBeforeLabel');
        const badgeAfter = document.getElementById('badgeAfterLabel');
        const afterWrapper = document.getElementById('afterCanvasWrapper');
        const btnText = document.getElementById('splitCompareBtnText');

        if (isSplitCompareActive) {
            divider.classList.remove('hidden');
            badgeBefore.classList.remove('hidden');
            badgeAfter.classList.remove('hidden');
            btnText.innerText = 'Tắt So Sánh';
            updateSplitPosition(50);
            showToast('Kéo thanh trượt để so sánh Trước và Sau khi sửa!', 'info');
        } else {
            divider.classList.add('hidden');
            badgeBefore.classList.add('hidden');
            badgeAfter.classList.add('hidden');
            btnText.innerText = 'So Sánh Trước/Sau';
            afterWrapper.style.clipPath = 'none';
        }
    }

    function updateSplitPosition(percent) {
        splitPositionPercent = Math.max(0, Math.min(100, percent));
        const divider = document.getElementById('splitDividerLine');
        const afterWrapper = document.getElementById('afterCanvasWrapper');

        divider.style.left = splitPositionPercent + '%';
        // Clip top edited layer to show bottom original layer on the left
        afterWrapper.style.clipPath = `polygon(${splitPositionPercent}% 0, 100% 0, 100% 100%, ${splitPositionPercent}% 100%)`;
    }

    document.getElementById('splitDividerLine').addEventListener('mousedown', (e) => {
        isDraggingSplit = true;
        e.preventDefault();
    });

    function handleSplitDragging(e) {
        if (!isDraggingSplit || !isSplitCompareActive) return;
        const container = document.getElementById('photoCanvasContainer');
        const rect = container.getBoundingClientRect();
        const offsetX = e.clientX - rect.left;
        const percent = (offsetX / rect.width) * 100;
        updateSplitPosition(percent);
    }

    function peekOriginal(isHolding) {
        const afterWrapper = document.getElementById('afterCanvasWrapper');
        if (isHolding) {
            afterWrapper.style.opacity = '0';
        } else {
            afterWrapper.style.opacity = '1';
        }
    }

    /**
     * CROP & ROTATE
     */
    function rotatePhoto(angle) {
        if (!originalCanvas) return;
        photoParams.rotation = (photoParams.rotation + angle) % 360;

        const tmp = document.createElement('canvas');
        tmp.width = originalCanvas.height;
        tmp.height = originalCanvas.width;
        const tCtx = tmp.getContext('2d');
        tCtx.translate(tmp.width / 2, tmp.height / 2);
        tCtx.rotate((angle * Math.PI) / 180);
        tCtx.drawImage(originalCanvas, -originalCanvas.width / 2, -originalCanvas.height / 2);

        originalCanvas.width = tmp.width;
        originalCanvas.height = tmp.height;
        originalCanvas.getContext('2d').drawImage(tmp, 0, 0);

        workingCanvas.width = tmp.width;
        workingCanvas.height = tmp.height;

        const beforeCv = document.getElementById('beforeCanvas');
        beforeCv.width = tmp.width;
        beforeCv.height = tmp.height;
        beforeCv.getContext('2d').drawImage(tmp, 0, 0);

        fitCanvasDimensions(tmp.width, tmp.height);
        renderWorkingPhotoToCanvas();
        recordHistoryState();
        showToast('Đã xoay ảnh!', 'info');
    }

    function flipPhoto(axis) {
        if (!originalCanvas) return;
        const w = originalCanvas.width;
        const h = originalCanvas.height;
        const ctx = originalCanvas.getContext('2d');

        const tmp = document.createElement('canvas');
        tmp.width = w;
        tmp.height = h;
        tmp.getContext('2d').drawImage(originalCanvas, 0, 0);

        ctx.clearRect(0, 0, w, h);
        ctx.save();
        if (axis === 'x') {
            ctx.translate(w, 0);
            ctx.scale(-1, 1);
        } else {
            ctx.translate(0, h);
            ctx.scale(1, -1);
        }
        ctx.drawImage(tmp, 0, 0);
        ctx.restore();

        document.getElementById('beforeCanvas').getContext('2d').drawImage(originalCanvas, 0, 0);
        renderWorkingPhotoToCanvas();
        recordHistoryState();
        showToast(`Đã lật ảnh theo trục ${axis.toUpperCase()}!`, 'info');
    }

    function cropToRatio(ratioStr) {
        if (!originalCanvas) return;
        const [rw, rh] = ratioStr.split(':').map(Number);
        const w = originalCanvas.width;
        const h = originalCanvas.height;

        let targetW = w;
        let targetH = Math.round((w / rw) * rh);

        if (targetH > h) {
            targetH = h;
            targetW = Math.round((h / rh) * rw);
        }

        const startX = Math.round((w - targetW) / 2);
        const startY = Math.round((h - targetH) / 2);

        const tmp = document.createElement('canvas');
        tmp.width = targetW;
        tmp.height = targetH;
        tmp.getContext('2d').drawImage(originalCanvas, startX, startY, targetW, targetH, 0, 0, targetW, targetH);

        originalCanvas.width = targetW;
        originalCanvas.height = targetH;
        originalCanvas.getContext('2d').drawImage(tmp, 0, 0);

        workingCanvas.width = targetW;
        workingCanvas.height = targetH;

        const beforeCv = document.getElementById('beforeCanvas');
        beforeCv.width = targetW;
        beforeCv.height = targetH;
        beforeCv.getContext('2d').drawImage(tmp, 0, 0);

        fitCanvasDimensions(targetW, targetH);
        renderWorkingPhotoToCanvas();
        recordHistoryState();
        showToast(`Đã cắt ảnh theo tỉ lệ ${ratioStr}!`, 'success');
    }

    function resetCrop() {
        if (!baseRawImage) return;
        loadPhotoFromDataUrl(baseRawImage.src);
    }

    /**
     * STICKERS & TEXT
     */
    function addSticker(emoji) {
        const textObj = new fabric.Text(emoji, {
            left: canvas.width / 2,
            top: canvas.height / 2,
            fontSize: 64,
            originX: 'center',
            originY: 'center'
        });
        canvas.add(textObj);
        canvas.setActiveObject(textObj);
        canvas.renderAll();
        recordHistoryState();
        showToast(`Đã dán sticker ${emoji}!`, 'success');
    }

    function addTextToPhoto(str, opts = {}) {
        const text = new fabric.IText(str, {
            left: canvas.width / 2,
            top: canvas.height / 2,
            fontSize: opts.fontSize || 32,
            fontWeight: opts.fontWeight || 'normal',
            fontStyle: opts.fontStyle || 'normal',
            fill: '#ffffff',
            stroke: '#000000',
            strokeWidth: 1,
            originX: 'center',
            originY: 'center',
            fontFamily: 'Inter, sans-serif'
        });
        canvas.add(text);
        canvas.setActiveObject(text);
        canvas.renderAll();
        recordHistoryState();
    }

    function toggleFreeDraw() {
        isDrawing = !isDrawing;
        const btn = document.getElementById('btnToggleFreeDraw');
        const controls = document.getElementById('drawControlsWrapper');

        if (isDrawing) {
            btn.innerText = 'Tắt Bút Vẽ';
            btn.className = 'text-xs font-bold px-3 py-1.5 rounded-xl bg-rose-600 text-white hover:bg-rose-700 transition';
            controls.classList.remove('hidden');
            canvas.isDrawingMode = true;
            canvas.freeDrawingBrush.color = document.getElementById('drawColorPicker').value;
            canvas.freeDrawingBrush.width = parseInt(document.getElementById('drawSizeSlider').value, 10);
        } else {
            btn.innerText = 'Bật Bút Vẽ';
            btn.className = 'text-xs font-bold px-3 py-1.5 rounded-xl bg-purple-600 text-white hover:bg-purple-700 transition';
            controls.classList.add('hidden');
            canvas.isDrawingMode = false;
        }
    }

    function updateDrawColor(color) {
        if (canvas.freeDrawingBrush) canvas.freeDrawingBrush.color = color;
    }

    function updateDrawSize(size) {
        document.getElementById('drawSizeText').innerText = `${size} px`;
        if (canvas.freeDrawingBrush) canvas.freeDrawingBrush.width = parseInt(size, 10);
    }

    /**
     * DOCK TABS & CONTEXTUAL CONTROLS
     */
    function selectDockTab(tabKey) {
        activeDockTab = tabKey;
        const tabs = ['restore', 'beauty', 'adjust', 'crop', 'stickers', 'text_draw'];
        
        tabs.forEach(k => {
            const btn = document.getElementById(`dockBtn${k.charAt(0).toUpperCase() + k.slice(1)}`);
            const panel = document.getElementById(`drawerTab${k.charAt(0).toUpperCase() + k.slice(1)}`);
            if (k === tabKey) {
                btn.className = 'dock-btn w-14 h-14 rounded-2xl flex flex-col items-center justify-center gap-1 text-white bg-[#1f2228] transition text-[10px] font-bold';
                if (panel) panel.classList.remove('hidden');
            } else {
                btn.className = 'dock-btn w-14 h-14 rounded-2xl flex flex-col items-center justify-center gap-1 text-[#868b98] hover:text-white hover:bg-[#1f2228] transition text-[10px] font-semibold';
                if (panel) panel.classList.add('hidden');
            }
        });

        const titles = {
            restore: 'Phục Dựng Ảnh Cũ',
            beauty: 'Làm Đẹp Chân Dung',
            adjust: 'Chỉnh Màu & Sáng',
            crop: 'Cắt & Xoay Ảnh',
            stickers: 'Kho Sticker Cute',
            text_draw: 'Chữ Nghệ Thuật & Vẽ'
        };
        document.getElementById('drawerTitle').innerHTML = `<span>${titles[tabKey]}</span>`;
        toggleDrawer(true);
    }

    function toggleDrawer(forceState) {
        const panel = document.getElementById('drawerPanel');
        if (forceState !== undefined) {
            panel.classList.toggle('hidden', !forceState);
        } else {
            panel.classList.toggle('hidden');
        }
    }

    function onObjectSelected(e) {
        const active = e.selected ? e.selected[0] : canvas.getActiveObject();
        if (!active || active.isPhotoBackground) return;

        document.getElementById('canvasDefaultPrompt').classList.add('hidden');
        document.getElementById('universalObjectActions').classList.remove('hidden');

        if (active instanceof fabric.IText || active instanceof fabric.Text) {
            document.getElementById('textControls').classList.remove('hidden');
            document.getElementById('stickerControls').classList.add('hidden');
            document.getElementById('fontSizeInput').value = Math.round(active.fontSize || 32);
        } else {
            document.getElementById('textControls').classList.add('hidden');
            document.getElementById('stickerControls').classList.remove('hidden');
        }
        document.getElementById('opacitySlider').value = Math.round((active.opacity !== undefined ? active.opacity : 1) * 100);
    }

    function onObjectDeselected() {
        document.getElementById('textControls').classList.add('hidden');
        document.getElementById('stickerControls').classList.add('hidden');
        document.getElementById('universalObjectActions').classList.add('hidden');
        document.getElementById('canvasDefaultPrompt').classList.remove('hidden');
    }

    function changeActiveTextFont(font) {
        const active = canvas.getActiveObject();
        if (active && (active instanceof fabric.IText || active instanceof fabric.Text)) {
            active.set('fontFamily', font);
            canvas.renderAll();
        }
    }

    function adjustFontSize(delta) {
        const active = canvas.getActiveObject();
        if (active && (active instanceof fabric.IText || active instanceof fabric.Text)) {
            const newSize = Math.max(10, Math.min(180, (active.fontSize || 32) + delta));
            active.set('fontSize', newSize);
            document.getElementById('fontSizeInput').value = newSize;
            canvas.renderAll();
        }
    }

    function setFontSize(val) {
        const active = canvas.getActiveObject();
        if (active && (active instanceof fabric.IText || active instanceof fabric.Text)) {
            active.set('fontSize', parseInt(val, 10) || 32);
            canvas.renderAll();
        }
    }

    function changeActiveTextColor(color) {
        const active = canvas.getActiveObject();
        if (active && (active instanceof fabric.IText || active instanceof fabric.Text)) {
            active.set('fill', color);
            canvas.renderAll();
        }
    }

    function toggleTextBold() {
        const active = canvas.getActiveObject();
        if (active && (active instanceof fabric.IText || active instanceof fabric.Text)) {
            active.set('fontWeight', active.fontWeight === 'bold' ? 'normal' : 'bold');
            canvas.renderAll();
        }
    }

    function toggleTextItalic() {
        const active = canvas.getActiveObject();
        if (active && (active instanceof fabric.IText || active instanceof fabric.Text)) {
            active.set('fontStyle', active.fontStyle === 'italic' ? 'normal' : 'italic');
            canvas.renderAll();
        }
    }

    function flipActiveObject(axis) {
        const active = canvas.getActiveObject();
        if (active) {
            if (axis === 'x') active.set('flipX', !active.flipX);
            if (axis === 'y') active.set('flipY', !active.flipY);
            canvas.renderAll();
        }
    }

    function changeActiveOpacity(val) {
        const active = canvas.getActiveObject();
        if (active) {
            active.set('opacity', parseInt(val, 10) / 100);
            canvas.renderAll();
        }
    }

    function duplicateActiveObject() {
        const active = canvas.getActiveObject();
        if (active && !active.isPhotoBackground) {
            active.clone((cloned) => {
                cloned.set({ left: active.left + 20, top: active.top + 20 });
                canvas.add(cloned);
                canvas.setActiveObject(cloned);
                canvas.renderAll();
                recordHistoryState();
            });
        }
    }

    function deleteActiveObject() {
        const active = canvas.getActiveObject();
        if (active && !active.isPhotoBackground) {
            canvas.remove(active);
            canvas.renderAll();
            recordHistoryState();
        }
    }

    /**
     * ZOOM & VIEWPORT
     */
    function adjustCanvasZoom(delta) {
        currentZoom = Math.max(0.3, Math.min(2.5, currentZoom + delta));
        document.getElementById('zoomPercentText').innerText = Math.round(currentZoom * 100) + '%';
        document.getElementById('photoCanvasContainer').style.transform = `scale(${currentZoom})`;
    }

    function fitCanvasToScreen() {
        currentZoom = 1.0;
        document.getElementById('zoomPercentText').innerText = '100%';
        document.getElementById('photoCanvasContainer').style.transform = `scale(1.0)`;
    }

    /**
     * HISTORY (Undo / Redo)
     */
    function recordHistoryState() {
        if (!isRecordingHistory) return;
        const state = {
            photoParams: JSON.parse(JSON.stringify(photoParams)),
            canvasJson: canvas.toJSON(['isPhotoBackground'])
        };
        historyStack = historyStack.slice(0, historyIndex + 1);
        historyStack.push(state);
        historyIndex++;
    }

    function undoAction() {
        if (historyIndex > 0) {
            historyIndex--;
            loadHistoryState(historyStack[historyIndex]);
        }
    }

    function redoAction() {
        if (historyIndex < historyStack.length - 1) {
            historyIndex++;
            loadHistoryState(historyStack[historyIndex]);
        }
    }

    function loadHistoryState(state) {
        isRecordingHistory = false;
        photoParams = JSON.parse(JSON.stringify(state.photoParams));
        canvas.loadFromJSON(state.canvasJson, () => {
            renderWorkingPhotoToCanvas();
            isRecordingHistory = true;
        });
    }

    function handleGlobalKeydown(e) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;

        if (e.key === 'Delete' || e.key === 'Backspace') {
            deleteActiveObject();
        } else if ((e.ctrlKey || e.metaKey) && e.key === 'z') {
            e.preventDefault();
            undoAction();
        } else if ((e.ctrlKey || e.metaKey) && (e.key === 'y' || (e.shiftKey && e.key === 'Z'))) {
            e.preventDefault();
            redoAction();
        } else if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
            e.preventDefault();
            duplicateActiveObject();
        }
    }

    /**
     * DOWNLOAD PHOTO
     */
    function toggleDownloadMenu() {
        document.getElementById('downloadDropdown').classList.toggle('hidden');
    }

    function downloadPhoto(format) {
        document.getElementById('downloadDropdown').classList.add('hidden');
        if (!originalCanvas || !workingCanvas) return;

        canvas.discardActiveObject();
        canvas.renderAll();

        // High resolution export canvas
        const exportCanvas = document.createElement('canvas');
        exportCanvas.width = originalCanvas.width;
        exportCanvas.height = originalCanvas.height;
        const expCtx = exportCanvas.getContext('2d');

        // Draw edited base photo
        expCtx.drawImage(workingCanvas, 0, 0);

        // Draw overlay stickers and text scaled up to full native resolution
        const scale = originalCanvas.width / canvas.width;
        expCtx.save();
        expCtx.scale(scale, scale);

        canvas.getObjects().forEach(obj => {
            if (!obj.isPhotoBackground) {
                obj.render(expCtx);
            }
        });
        expCtx.restore();

        const title = (document.getElementById('photoTitleInput').value.trim() || 'anh_chinh_sua') + '_ziitool';
        const mimeType = format === 'jpg' ? 'image/jpeg' : (format === 'webp' ? 'image/webp' : 'image/png');
        const dataUrl = exportCanvas.toDataURL(mimeType, 0.95);

        const a = document.createElement('a');
        a.href = dataUrl;
        a.download = `${title}.${format}`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);

        showToast(`Đã tải ảnh ${format.toUpperCase()} độ phân giải cao thành công!`, 'success');
    }

    /**
     * GENERATE HIGH QUALITY SAMPLE PHOTOS (No external server needed)
     */
    function loadSamplePhoto(type) {
        const sampleCanvas = document.createElement('canvas');
        const ctx = sampleCanvas.getContext('2d');

        if (type === 'old') {
            // Sample Old Vintage Faded Photo with Scratches
            sampleCanvas.width = 600;
            sampleCanvas.height = 750;

            // Warm sepia base
            const grad = ctx.createLinearGradient(0, 0, 600, 750);
            grad.addColorStop(0, '#5a4632');
            grad.addColorStop(0.5, '#7b6348');
            grad.addColorStop(1, '#483827');
            ctx.fillStyle = grad;
            ctx.fillRect(0, 0, 600, 750);

            // Vintage portrait silhouette
            ctx.fillStyle = '#bfa588';
            ctx.beginPath();
            ctx.arc(300, 310, 115, 0, Math.PI * 2); // Face
            ctx.fill();

            // Hair
            ctx.fillStyle = '#3e2e21';
            ctx.beginPath();
            ctx.arc(300, 260, 125, Math.PI * 0.9, Math.PI * 2.1);
            ctx.fill();

            // Eyes, nose, mouth soft shading
            ctx.fillStyle = '#3a2b1e';
            ctx.beginPath();
            ctx.arc(265, 305, 12, 0, Math.PI * 2); // Left eye
            ctx.arc(335, 305, 12, 0, Math.PI * 2); // Right eye
            ctx.fill();

            // Lips
            ctx.fillStyle = '#6b4334';
            ctx.beginPath();
            ctx.ellipse(300, 365, 26, 12, 0, 0, Math.PI * 2);
            ctx.fill();

            // Shoulders & Shirt
            ctx.fillStyle = '#2f2419';
            ctx.beginPath();
            ctx.ellipse(300, 580, 220, 160, 0, 0, Math.PI * 2);
            ctx.fill();

            // Realistic old paper noise & yellowing
            ctx.fillStyle = 'rgba(215, 185, 110, 0.28)';
            ctx.fillRect(0, 0, 600, 750);

            // Scratches & Tear lines (vết xước ảnh cũ)
            ctx.strokeStyle = 'rgba(255, 255, 255, 0.75)';
            ctx.lineWidth = 1.8;
            ctx.beginPath();
            ctx.moveTo(160, 110);
            ctx.lineTo(220, 270);
            ctx.moveTo(380, 240);
            ctx.lineTo(430, 480);
            ctx.moveTo(270, 320);
            ctx.lineTo(320, 345);
            ctx.stroke();

            // Dark crease line
            ctx.strokeStyle = 'rgba(40, 25, 15, 0.7)';
            ctx.lineWidth = 2.2;
            ctx.beginPath();
            ctx.moveTo(110, 410);
            ctx.lineTo(490, 430);
            ctx.stroke();

            document.getElementById('photoTitleInput').value = 'anh_cu_phuc_dung';
        } else if (type === 'portrait') {
            // Sample Portrait Photo for Beauty Retouching
            sampleCanvas.width = 600;
            sampleCanvas.height = 750;

            // Studio backdrop
            const grad = ctx.createRadialGradient(300, 320, 60, 300, 320, 450);
            grad.addColorStop(0, '#475569');
            grad.addColorStop(1, '#0f172a');
            ctx.fillStyle = grad;
            ctx.fillRect(0, 0, 600, 750);

            // Natural face
            ctx.fillStyle = '#f8d2b8';
            ctx.beginPath();
            ctx.arc(300, 320, 125, 0, Math.PI * 2);
            ctx.fill();

            // Neck
            ctx.fillStyle = '#e4bc9f';
            ctx.fillRect(260, 420, 80, 100);

            // Hair
            ctx.fillStyle = '#261b14';
            ctx.beginPath();
            ctx.arc(300, 280, 140, Math.PI * 0.85, Math.PI * 2.15);
            ctx.fill();

            // Eyes
            ctx.fillStyle = '#ffffff';
            ctx.beginPath();
            ctx.arc(260, 315, 18, 0, Math.PI * 2);
            ctx.arc(340, 315, 18, 0, Math.PI * 2);
            ctx.fill();
            ctx.fillStyle = '#3b2518';
            ctx.beginPath();
            ctx.arc(260, 315, 10, 0, Math.PI * 2);
            ctx.arc(340, 315, 10, 0, Math.PI * 2);
            ctx.fill();

            // Blemishes / Freckles (để thử xóa mụn)
            ctx.fillStyle = '#b57958';
            ctx.beginPath();
            ctx.arc(245, 360, 4, 0, Math.PI * 2); // Pimple 1
            ctx.arc(350, 355, 3.5, 0, Math.PI * 2); // Pimple 2
            ctx.arc(295, 385, 3, 0, Math.PI * 2); // Pimple 3
            ctx.arc(235, 340, 2.5, 0, Math.PI * 2);
            ctx.fill();

            // Lips
            ctx.fillStyle = '#e07a7a';
            ctx.beginPath();
            ctx.ellipse(300, 385, 30, 14, 0, 0, Math.PI * 2);
            ctx.fill();

            // Clothes
            ctx.fillStyle = '#ec4899';
            ctx.beginPath();
            ctx.ellipse(300, 600, 230, 160, 0, 0, Math.PI * 2);
            ctx.fill();

            document.getElementById('photoTitleInput').value = 'anh_chan_dung_lam_dep';
        } else {
            // Sample Landscape Photo
            sampleCanvas.width = 800;
            sampleCanvas.height = 540;

            // Sky
            const skyGrad = ctx.createLinearGradient(0, 0, 0, 320);
            skyGrad.addColorStop(0, '#1e3a8a');
            skyGrad.addColorStop(0.6, '#f97316');
            skyGrad.addColorStop(1, '#fde047');
            ctx.fillStyle = skyGrad;
            ctx.fillRect(0, 0, 800, 320);

            // Sun
            ctx.fillStyle = '#ffffff';
            ctx.beginPath();
            ctx.arc(400, 260, 45, 0, Math.PI * 2);
            ctx.fill();

            // Mountains
            ctx.fillStyle = '#1e1b4b';
            ctx.beginPath();
            ctx.moveTo(0, 330);
            ctx.lineTo(240, 150);
            ctx.lineTo(460, 330);
            ctx.fill();

            ctx.fillStyle = '#312e81';
            ctx.beginPath();
            ctx.moveTo(320, 330);
            ctx.lineTo(560, 180);
            ctx.lineTo(800, 330);
            ctx.fill();

            // Lake water
            const waterGrad = ctx.createLinearGradient(0, 330, 0, 540);
            waterGrad.addColorStop(0, '#0369a1');
            waterGrad.addColorStop(1, '#082f49');
            ctx.fillStyle = waterGrad;
            ctx.fillRect(0, 330, 800, 210);

            document.getElementById('photoTitleInput').value = 'anh_phong_canh_ziitool';
        }

        loadPhotoFromDataUrl(sampleCanvas.toDataURL('image/jpeg', 0.95));
    }
</script>
@endpush
@endsection
