@extends('layouts.app')

@section('content')
<!-- ZiiSlide Studio: Bài Thuyết Trình Chuẩn Canva Presentation (1920x1080) -->
<div id="canvaPresentationRoot" class="fixed inset-0 z-50 w-screen h-screen overflow-hidden bg-[#0e1318] text-slate-100 flex flex-col font-sans">
    
    <!-- 1. TOP BAR (Thanh Điều Hướng Canva) -->
    <header class="h-14 bg-[#14171b] border-b border-[#242930] px-3 sm:px-4 flex items-center justify-between shrink-0 z-30 select-none">
        <!-- Left: Logo, Menu (Tệp, Đổi cỡ), Undo/Redo -->
        <div class="flex items-center gap-2 sm:gap-3">
            <a href="{{ route('home') }}" class="flex items-center gap-2 group mr-1" title="Quay về Trang Chủ ZiiTool">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-purple-600 via-indigo-600 to-pink-500 flex items-center justify-center text-white font-black text-sm shadow-md group-hover:scale-105 transition">
                    Z
                </div>
                <div class="hidden md:flex items-center gap-1.5">
                    <span class="font-extrabold text-base tracking-tight text-white">ZiiTool</span>
                    <span class="text-[10px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded-md bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-sm">
                        Canva Slide
                    </span>
                    <h1 class="sr-only">{{ $tool['title'] }}</h1>
                </div>
            </a>

            <div class="h-5 w-[1px] bg-[#282e37] hidden sm:block"></div>

            <!-- Menu Tệp (File Dropdown) -->
            <div class="relative">
                <button type="button" onclick="toggleMenuDropdown('fileMenuDropdown')" class="px-2.5 py-1 rounded-lg hover:bg-[#20252d] text-slate-300 hover:text-white transition flex items-center gap-1 text-xs font-semibold cursor-pointer">
                    <span>Tệp</span>
                    <i data-lucide="chevron-down" class="w-3 h-3 text-slate-400"></i>
                </button>
                <div id="fileMenuDropdown" class="hidden absolute left-0 mt-1.5 w-56 rounded-2xl bg-[#191c22] border border-[#343b46] shadow-2xl p-1.5 space-y-1 z-50 text-xs text-white">
                    <button type="button" onclick="createNewPresentation()" class="w-full text-left px-3 py-2 rounded-xl hover:bg-[#252a33] flex items-center gap-2 transition cursor-pointer">
                        <i data-lucide="file-plus" class="w-4 h-4 text-purple-400"></i>
                        <span>Tạo bài mới (Slide trắng)</span>
                    </button>
                    <button type="button" onclick="document.getElementById('pptxFileInput').click()" class="w-full text-left px-3 py-2 rounded-xl hover:bg-[#252a33] flex items-center gap-2 transition cursor-pointer">
                        <i data-lucide="file-up" class="w-4 h-4 text-amber-400"></i>
                        <span>Nạp file PowerPoint cũ (.pptx)</span>
                    </button>
                    <button type="button" onclick="duplicateCurrentSlide()" class="w-full text-left px-3 py-2 rounded-xl hover:bg-[#252a33] flex items-center gap-2 transition cursor-pointer">
                        <i data-lucide="copy" class="w-4 h-4 text-sky-400"></i>
                        <span>Nhân bản trang slide này</span>
                    </button>
                    <div class="h-[1px] bg-[#242930] my-1"></div>
                    <button type="button" onclick="exportPresentation('pptx')" class="w-full text-left px-3 py-2 rounded-xl hover:bg-[#252a33] flex items-center gap-2 transition cursor-pointer">
                        <i data-lucide="download" class="w-4 h-4 text-emerald-400"></i>
                        <span>Tải về PowerPoint (.pptx)</span>
                    </button>
                    <button type="button" onclick="exportPresentation('pdf')" class="w-full text-left px-3 py-2 rounded-xl hover:bg-[#252a33] flex items-center gap-2 transition cursor-pointer">
                        <i data-lucide="file-text" class="w-4 h-4 text-pink-400"></i>
                        <span>Tải về file PDF</span>
                    </button>
                </div>
            </div>

            <!-- Menu Đổi Cỡ (Resize Aspect Ratio) -->
            <div class="relative">
                <button type="button" onclick="toggleMenuDropdown('resizeMenuDropdown')" class="px-2.5 py-1 rounded-lg hover:bg-[#20252d] text-slate-300 hover:text-white transition flex items-center gap-1 text-xs font-semibold cursor-pointer">
                    <span>Đổi cỡ</span>
                    <i data-lucide="crown" class="w-3 h-3 text-amber-400"></i>
                </button>
                <div id="resizeMenuDropdown" class="hidden absolute left-0 mt-1.5 w-60 rounded-2xl bg-[#191c22] border border-[#343b46] shadow-2xl p-2 space-y-1.5 z-50 text-xs text-white">
                    <p class="text-[10px] uppercase font-bold text-slate-400 px-2 py-1">Tỉ lệ khung hình slide</p>
                    <button type="button" onclick="changePresentationRatio('16:9')" class="w-full text-left px-3 py-2 rounded-xl hover:bg-[#252a33] flex items-center justify-between transition cursor-pointer">
                        <div>
                            <span class="font-bold text-white block">16:9 Màn hình rộng</span>
                            <span class="text-[10px] text-slate-400">1920 × 1080 px (Chuẩn)</span>
                        </div>
                        <span id="ratioTag16_9" class="text-purple-400 font-bold">✓</span>
                    </button>
                    <button type="button" onclick="changePresentationRatio('4:3')" class="w-full text-left px-3 py-2 rounded-xl hover:bg-[#252a33] flex items-center justify-between transition cursor-pointer">
                        <div>
                            <span class="font-bold text-white block">4:3 Màn hình tiêu chuẩn</span>
                            <span class="text-[10px] text-slate-400">1440 × 1080 px</span>
                        </div>
                        <span id="ratioTag4_3" class="text-purple-400 font-bold hidden">✓</span>
                    </button>
                </div>
            </div>

            <!-- Undo / Redo Thật (Working History Stack) -->
            <div class="flex items-center gap-0.5 ml-1">
                <button type="button" onclick="undoSlideAction()" class="p-1.5 rounded-lg hover:bg-[#20252d] text-slate-300 hover:text-white transition cursor-pointer" title="Hoàn tác (Ctrl+Z)">
                    <i data-lucide="undo-2" class="w-4 h-4"></i>
                </button>
                <button type="button" onclick="redoSlideAction()" class="p-1.5 rounded-lg hover:bg-[#20252d] text-slate-300 hover:text-white transition cursor-pointer" title="Làm lại (Ctrl+Y)">
                    <i data-lucide="redo-2" class="w-4 h-4"></i>
                </button>
                <div class="hidden xl:flex items-center gap-1.5 ml-2 text-[11px] text-slate-400">
                    <i data-lucide="cloud-check" class="w-3.5 h-3.5 text-emerald-400"></i>
                    <span>Tự động lưu</span>
                </div>
            </div>
        </div>

        <!-- Center: Editable Presentation Title -->
        <div class="flex items-center justify-center">
            <input type="text" id="presentationTitleInput" value="Thiết kế không tên - Bài thuyết trình" class="bg-transparent hover:bg-[#1e232b] focus:bg-[#1e232b] border border-transparent hover:border-[#38404d] focus:border-purple-500 px-3 py-1 rounded-lg text-xs sm:text-sm font-semibold text-white focus:outline-none transition max-w-[170px] sm:max-w-[320px] text-center truncate" title="Nhấp để đổi tên bài thuyết trình">
        </div>

        <!-- Right: Nạp PPTX, Nút Thuyết Trình, Nút Chia Sẻ -->
        <div class="flex items-center gap-2">
            <!-- Nút Nạp PPTX Cũ -->
            <button type="button" onclick="document.getElementById('pptxFileInput').click()" class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#1e232b] hover:bg-[#292f3a] text-slate-200 hover:text-white border border-[#343b47] text-xs font-bold transition shadow-sm cursor-pointer" title="Tải file PowerPoint (.pptx) cũ từ máy lên để chỉnh sửa">
                <i data-lucide="file-up" class="w-3.5 h-3.5 text-amber-400"></i>
                <span>Nạp PPTX Cũ</span>
            </button>
            <input type="file" id="pptxFileInput" accept=".pptx" class="hidden" onchange="handlePptxFileUpload(this.files)">

            <!-- Nút Thuyết Trình (F5) -->
            <button type="button" onclick="startPresentationMode()" class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-extrabold text-xs sm:text-sm shadow-md shadow-purple-950/40 transition group cursor-pointer" title="Bắt đầu trình chiếu toàn màn hình (Phím F5)">
                <i data-lucide="play" class="w-4 h-4 fill-white group-hover:scale-110 transition-transform"></i>
                <span>Thuyết trình</span>
            </button>

            <!-- Nút Chia Sẻ / Tải Về Dropdown -->
            <div class="relative">
                <button type="button" onclick="toggleMenuDropdown('downloadDropdown')" class="py-1.5 px-3.5 rounded-xl bg-white hover:bg-slate-100 text-slate-900 font-extrabold text-xs sm:text-sm shadow-md transition flex items-center gap-1.5 cursor-pointer">
                    <span>Chia sẻ</span>
                    <i data-lucide="download" class="w-3.5 h-3.5 text-slate-700"></i>
                </button>

                <div id="downloadDropdown" class="hidden absolute right-0 mt-1.5 w-64 rounded-2xl bg-[#191c22] border border-[#343b46] shadow-2xl p-3 space-y-2 z-50 text-white">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 px-2 pb-1 border-b border-[#242930]">
                        Tải xuống bài thuyết trình
                    </div>
                    
                    <!-- PPTX Real File -->
                    <button type="button" onclick="exportPresentation('pptx')" class="w-full text-left p-2.5 rounded-xl hover:bg-[#252a33] flex items-center justify-between transition group cursor-pointer">
                        <div>
                            <p class="text-xs font-bold text-white group-hover:text-purple-400">Tệp PowerPoint (.pptx)</p>
                            <p class="text-[10px] text-slate-400">Mở và sửa được trên PowerPoint & Google Slides</p>
                        </div>
                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-purple-900/60 text-purple-300">Khuyên dùng</span>
                    </button>

                    <!-- PDF Document -->
                    <button type="button" onclick="exportPresentation('pdf')" class="w-full text-left p-2.5 rounded-xl hover:bg-[#252a33] flex items-center justify-between transition group cursor-pointer">
                        <div>
                            <p class="text-xs font-bold text-white group-hover:text-pink-400">Tài Liệu PDF Tiêu Chuẩn</p>
                            <p class="text-[10px] text-slate-400">Đầy đủ tất cả các trang slide</p>
                        </div>
                        <span class="text-[10px] text-slate-400">PDF</span>
                    </button>

                    <!-- Current Slide PNG -->
                    <button type="button" onclick="exportPresentation('png')" class="w-full text-left p-2.5 rounded-xl hover:bg-[#252a33] flex items-center justify-between transition group cursor-pointer">
                        <div>
                            <p class="text-xs font-bold text-white group-hover:text-teal-400">Hình Ảnh Trang Hiện Tại (PNG)</p>
                            <p class="text-[10px] text-slate-400">Độ phân giải siêu nét 1080p</p>
                        </div>
                        <span class="text-[10px] text-teal-400">PNG</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- 2. MAIN WORKSPACE (Dock + Drawer + Canvas Artboard) -->
    <div class="flex-1 flex overflow-hidden relative">

        <!-- 2A. LEFT DOCK (Canva 72px Vertical Bar) -->
        <aside class="w-[72px] bg-[#121417] border-r border-[#20242b] flex flex-col items-center py-2.5 gap-1 shrink-0 z-20 select-none">
            <!-- Mẫu -->
            <button type="button" onclick="selectDockTab('templates')" id="dockBtnTemplates" class="dock-btn w-14 h-14 rounded-2xl flex flex-col items-center justify-center gap-1 text-white bg-[#20252d] transition text-[10px] font-bold cursor-pointer">
                <i data-lucide="layout-template" class="w-5 h-5 text-purple-400"></i>
                <span>Mẫu</span>
            </button>

            <!-- Văn bản -->
            <button type="button" onclick="selectDockTab('text')" id="dockBtnText" class="dock-btn w-14 h-14 rounded-2xl flex flex-col items-center justify-center gap-1 text-[#868c98] hover:text-white hover:bg-[#20252d] transition text-[10px] font-semibold cursor-pointer">
                <i data-lucide="type" class="w-5 h-5"></i>
                <span>Văn bản</span>
            </button>

            <!-- Thành phần -->
            <button type="button" onclick="selectDockTab('elements')" id="dockBtnElements" class="dock-btn w-14 h-14 rounded-2xl flex flex-col items-center justify-center gap-1 text-[#868c98] hover:text-white hover:bg-[#20252d] transition text-[10px] font-semibold cursor-pointer">
                <i data-lucide="shapes" class="w-5 h-5"></i>
                <span>Thành phần</span>
            </button>

            <!-- Tải lên -->
            <button type="button" onclick="selectDockTab('uploads')" id="dockBtnUploads" class="dock-btn w-14 h-14 rounded-2xl flex flex-col items-center justify-center gap-1 text-[#868c98] hover:text-white hover:bg-[#20252d] transition text-[10px] font-semibold cursor-pointer">
                <i data-lucide="upload-cloud" class="w-5 h-5"></i>
                <span>Tải lên</span>
            </button>

            <!-- Nền -->
            <button type="button" onclick="selectDockTab('background')" id="dockBtnBackground" class="dock-btn w-14 h-14 rounded-2xl flex flex-col items-center justify-center gap-1 text-[#868c98] hover:text-white hover:bg-[#20252d] transition text-[10px] font-semibold cursor-pointer">
                <i data-lucide="palette" class="w-5 h-5"></i>
                <span>Nền</span>
            </button>

            <!-- Vẽ -->
            <button type="button" onclick="selectDockTab('draw')" id="dockBtnDraw" class="dock-btn w-14 h-14 rounded-2xl flex flex-col items-center justify-center gap-1 text-[#868c98] hover:text-white hover:bg-[#20252d] transition text-[10px] font-semibold cursor-pointer">
                <i data-lucide="pen-tool" class="w-5 h-5"></i>
                <span>Vẽ</span>
            </button>
        </aside>

        <!-- 2B. LEFT EXPANDING DRAWER (360px Canva Panel) -->
        <div id="drawerPanel" class="w-[360px] bg-[#16181d] border-r border-[#242930] flex flex-col shrink-0 z-10 transition-all duration-200">
            <!-- Drawer Header -->
            <div class="h-11 px-4 border-b border-[#242930] flex items-center justify-between shrink-0 select-none">
                <div class="flex items-center gap-2">
                    <span id="drawerTitle" class="text-xs font-bold text-slate-200 truncate">Kho Mẫu Bài Thuyết Trình</span>
                </div>
                <button type="button" onclick="toggleDrawer(false)" class="p-1 rounded-lg hover:bg-[#22262e] text-slate-400 hover:text-white transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Drawer Dynamic Content Body -->
            <div class="flex-1 overflow-y-auto p-3.5 space-y-3.5 text-xs">
                
                <!-- 1. TAB: TEMPLATES (Kho Mẫu Slide Đa Dạng 8+ Chủ Đề) -->
                <div id="drawerTabTemplates" class="drawer-tab space-y-3">
                    <!-- Search Bar in Drawer -->
                    <div class="relative">
                        <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-2.5"></i>
                        <input type="text" id="templateSearchInput" oninput="filterTemplateDecks(this.value)" placeholder="Tìm kiếm mẫu theo từ khóa..." class="w-full pl-9 pr-3 py-2 rounded-xl bg-[#20242b] border border-[#2d333e] text-xs text-white placeholder-slate-400 focus:outline-none focus:border-purple-500">
                    </div>

                    <!-- Category Pills -->
                    <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar py-0.5 select-none">
                        <button type="button" onclick="filterTemplateCategory('all')" class="cat-pill px-2.5 py-1 rounded-lg text-[11px] font-bold transition whitespace-nowrap bg-purple-600 text-white cursor-pointer" data-cat="all">Tất cả (8)</button>
                        <button type="button" onclick="filterTemplateCategory('business')" class="cat-pill px-2.5 py-1 rounded-lg text-[11px] font-bold transition whitespace-nowrap bg-[#20242b] text-slate-300 hover:text-white cursor-pointer" data-cat="business">Kinh doanh</button>
                        <button type="button" onclick="filterTemplateCategory('pitch')" class="cat-pill px-2.5 py-1 rounded-lg text-[11px] font-bold transition whitespace-nowrap bg-[#20242b] text-slate-300 hover:text-white cursor-pointer" data-cat="pitch">Pitch Deck</button>
                        <button type="button" onclick="filterTemplateCategory('academic')" class="cat-pill px-2.5 py-1 rounded-lg text-[11px] font-bold transition whitespace-nowrap bg-[#20242b] text-slate-300 hover:text-white cursor-pointer" data-cat="academic">Giáo dục</button>
                        <button type="button" onclick="filterTemplateCategory('fun')" class="cat-pill px-2.5 py-1 rounded-lg text-[11px] font-bold transition whitespace-nowrap bg-[#20242b] text-slate-300 hover:text-white cursor-pointer" data-cat="fun">Vui nhộn</button>
                    </div>

                    <!-- Templates List Container -->
                    <div id="templatesDecksList" class="space-y-3 pt-1">
                        <!-- Dynamic Templates will be rendered here -->
                    </div>
                </div>

                <!-- 2. TAB: TEXT (Văn bản) -->
                <div id="drawerTabText" class="drawer-tab hidden space-y-3">
                    <span class="text-xs font-bold text-slate-300 block">Nhấp để thêm hộp văn bản vào slide:</span>
                    <button type="button" onclick="addTextToCanvas('Thêm Tiêu Đề Lớn', { fontSize: 44, fontWeight: 'bold', fill: '#1e1b4b' })" class="w-full p-3 rounded-xl bg-[#20242b] hover:bg-[#292e37] border border-[#2d333e] text-left transition cursor-pointer">
                        <span class="text-lg font-black text-white block">Thêm tiêu đề lớn</span>
                        <span class="text-[10px] text-slate-400">Heading 1 - Cỡ 44px</span>
                    </button>
                    <button type="button" onclick="addTextToCanvas('Thêm tiêu đề phụ của trang', { fontSize: 28, fontWeight: '600', fill: '#334155' })" class="w-full p-3 rounded-xl bg-[#20242b] hover:bg-[#292e37] border border-[#2d333e] text-left transition cursor-pointer">
                        <span class="text-sm font-bold text-slate-200 block">Thêm tiêu đề phụ của trang</span>
                        <span class="text-[10px] text-slate-400">Heading 2 - Cỡ 28px</span>
                    </button>
                    <button type="button" onclick="addTextToCanvas('Nội dung chi tiết giải thích cho ý tưởng bài thuyết trình.', { fontSize: 18, fill: '#475569' })" class="w-full p-3 rounded-xl bg-[#20242b] hover:bg-[#292e37] border border-[#2d333e] text-left transition cursor-pointer">
                        <span class="text-xs text-slate-300 block">Thêm văn bản nội dung chi tiết</span>
                        <span class="text-[10px] text-slate-400">Body text - Cỡ 18px</span>
                    </button>
                    <button type="button" onclick="addTextToCanvas('• Điểm trọng tâm thứ nhất\n• Điểm trọng tâm thứ hai\n• Điểm trọng tâm thứ ba', { fontSize: 18, fill: '#334155' })" class="w-full p-3 rounded-xl bg-[#20242b] hover:bg-[#292e37] border border-[#2d333e] text-left transition cursor-pointer">
                        <span class="text-xs font-semibold text-slate-200 block">• Danh sách gạch đầu dòng</span>
                        <span class="text-[10px] text-slate-400">Bullet points - 3 ý chính</span>
                    </button>
                </div>

                <!-- 3. TAB: ELEMENTS (Thành phần đồ họa) -->
                <div id="drawerTabElements" class="drawer-tab hidden space-y-4">
                    <span class="text-xs font-bold text-slate-300 block">Hình khối & Khung thẻ:</span>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" onclick="addShapeToCanvas('rect')" class="p-3 rounded-xl bg-[#20242b] hover:bg-[#292e37] border border-[#2d333e] flex flex-col items-center gap-1.5 transition cursor-pointer">
                            <div class="w-8 h-8 rounded-lg bg-purple-500"></div>
                            <span class="text-[10px] text-slate-300">Chữ nhật</span>
                        </button>
                        <button type="button" onclick="addShapeToCanvas('circle')" class="p-3 rounded-xl bg-[#20242b] hover:bg-[#292e37] border border-[#2d333e] flex flex-col items-center gap-1.5 transition cursor-pointer">
                            <div class="w-8 h-8 rounded-full bg-sky-500"></div>
                            <span class="text-[10px] text-slate-300">Hình tròn</span>
                        </button>
                        <button type="button" onclick="addShapeToCanvas('cloud')" class="p-3 rounded-xl bg-[#20242b] hover:bg-[#292e37] border border-[#2d333e] flex flex-col items-center gap-1.5 transition cursor-pointer">
                            <span class="text-2xl">☁️</span>
                            <span class="text-[10px] text-slate-300">Đám mây</span>
                        </button>
                        <button type="button" onclick="addShapeToCanvas('star')" class="p-3 rounded-xl bg-[#20242b] hover:bg-[#292e37] border border-[#2d333e] flex flex-col items-center gap-1.5 transition cursor-pointer">
                            <span class="text-2xl text-amber-400">★</span>
                            <span class="text-[10px] text-slate-300">Ngôi sao</span>
                        </button>
                        <button type="button" onclick="addShapeToCanvas('arrow')" class="p-3 rounded-xl bg-[#20242b] hover:bg-[#292e37] border border-[#2d333e] flex flex-col items-center gap-1.5 transition cursor-pointer">
                            <span class="text-2xl text-emerald-400">➔</span>
                            <span class="text-[10px] text-slate-300">Mũi tên</span>
                        </button>
                        <button type="button" onclick="addShapeToCanvas('heart')" class="p-3 rounded-xl bg-[#20242b] hover:bg-[#292e37] border border-[#2d333e] flex flex-col items-center gap-1.5 transition cursor-pointer">
                            <span class="text-2xl">❤️</span>
                            <span class="text-[10px] text-slate-300">Trái tim</span>
                        </button>
                    </div>

                    <!-- Stickers & Emojis -->
                    <span class="text-xs font-bold text-slate-300 block pt-1">Nhãn dán minh họa:</span>
                    <div class="grid grid-cols-4 gap-2 text-center">
                        <button type="button" onclick="addEmojiToCanvas('🐱')" class="p-2 rounded-xl bg-[#20242b] hover:bg-[#292e37] text-2xl transition cursor-pointer">🐱</button>
                        <button type="button" onclick="addEmojiToCanvas('🕶️')" class="p-2 rounded-xl bg-[#20242b] hover:bg-[#292e37] text-2xl transition cursor-pointer">🕶️</button>
                        <button type="button" onclick="addEmojiToCanvas('🦖')" class="p-2 rounded-xl bg-[#20242b] hover:bg-[#292e37] text-2xl transition cursor-pointer">🦖</button>
                        <button type="button" onclick="addEmojiToCanvas('🚀')" class="p-2 rounded-xl bg-[#20242b] hover:bg-[#292e37] text-2xl transition cursor-pointer">🚀</button>
                        <button type="button" onclick="addEmojiToCanvas('💡')" class="p-2 rounded-xl bg-[#20242b] hover:bg-[#292e37] text-2xl transition cursor-pointer">💡</button>
                        <button type="button" onclick="addEmojiToCanvas('📊')" class="p-2 rounded-xl bg-[#20242b] hover:bg-[#292e37] text-2xl transition cursor-pointer">📊</button>
                        <button type="button" onclick="addEmojiToCanvas('🏆')" class="p-2 rounded-xl bg-[#20242b] hover:bg-[#292e37] text-2xl transition cursor-pointer">🏆</button>
                        <button type="button" onclick="addEmojiToCanvas('🤝')" class="p-2 rounded-xl bg-[#20242b] hover:bg-[#292e37] text-2xl transition cursor-pointer">🤝</button>
                    </div>
                </div>

                <!-- 4. TAB: UPLOADS (Tải ảnh từ máy) -->
                <div id="drawerTabUploads" class="drawer-tab hidden space-y-3">
                    <span class="text-xs font-bold text-slate-300 block">Tải ảnh lên bài thuyết trình:</span>
                    <button type="button" onclick="document.getElementById('slideImgUploadInput').click()" class="w-full py-4 rounded-xl border-2 border-dashed border-[#343b47] hover:border-purple-500 bg-[#20242b] hover:bg-[#262c35] text-center space-y-1 transition cursor-pointer">
                        <i data-lucide="image-plus" class="w-6 h-6 text-purple-400 mx-auto"></i>
                        <span class="text-xs font-bold text-white block">Chọn tệp ảnh từ máy tính</span>
                        <span class="text-[10px] text-slate-400">Hỗ trợ JPG, PNG, WebP, SVG</span>
                    </button>
                    <input type="file" id="slideImgUploadInput" accept="image/*" class="hidden" onchange="handleImageUpload(this.files)">
                </div>

                <!-- 5. TAB: BACKGROUND (Màu nền slide) -->
                <div id="drawerTabBackground" class="drawer-tab hidden space-y-3">
                    <span class="text-xs font-bold text-slate-300 block">Màu nền trang slide:</span>
                    <div class="grid grid-cols-4 gap-2">
                        <button type="button" onclick="setCanvasBg('#ffffff')" class="h-10 rounded-xl bg-white border border-slate-300 cursor-pointer" title="Trắng tinh"></button>
                        <button type="button" onclick="setCanvasBg('#e0d7f5')" class="h-10 rounded-xl bg-[#e0d7f5] border border-slate-400 cursor-pointer" title="Tím pastel"></button>
                        <button type="button" onclick="setCanvasBg('#e2f0d9')" class="h-10 rounded-xl bg-[#e2f0d9] border border-slate-400 cursor-pointer" title="Xanh mint"></button>
                        <button type="button" onclick="setCanvasBg('#fce5cd')" class="h-10 rounded-xl bg-[#fce5cd] border border-slate-400 cursor-pointer" title="Cam đào"></button>
                        <button type="button" onclick="setCanvasBg('#d9e1f2')" class="h-10 rounded-xl bg-[#d9e1f2] border border-slate-400 cursor-pointer" title="Xanh dương nhạt"></button>
                        <button type="button" onclick="setCanvasBg('#f4cccc')" class="h-10 rounded-xl bg-[#f4cccc] border border-slate-400 cursor-pointer" title="Hồng phấn"></button>
                        <button type="button" onclick="setCanvasBg('#0f172a')" class="h-10 rounded-xl bg-[#0f172a] border border-slate-600 cursor-pointer" title="Xanh đen"></button>
                        <button type="button" onclick="setCanvasBg('#18181b')" class="h-10 rounded-xl bg-[#18181b] border border-slate-600 cursor-pointer" title="Đen tuyền"></button>
                    </div>
                </div>

                <!-- 6. TAB: DRAW (Vẽ tay) -->
                <div id="drawerTabDraw" class="drawer-tab hidden space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-300">Bút vẽ tay trực tiếp</span>
                        <button type="button" id="btnToggleDraw" onclick="toggleFreeDraw()" class="text-xs font-bold px-3 py-1.5 rounded-xl bg-purple-600 text-white hover:bg-purple-700 transition cursor-pointer">
                            Bật bút vẽ
                        </button>
                    </div>
                    <div id="drawControlsBox" class="hidden space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-400">Màu nét vẽ:</span>
                            <input type="color" id="drawColorPicker" value="#8b5cf6" onchange="updateDrawColor(this.value)" class="w-8 h-8 rounded cursor-pointer bg-transparent border-0">
                        </div>
                        <div class="space-y-1">
                            <div class="flex justify-between text-xs text-slate-400">
                                <span>Độ dày nét:</span>
                                <span id="drawSizeText" class="text-purple-400 font-bold">6 px</span>
                            </div>
                            <input type="range" id="drawSizeSlider" min="2" max="40" value="6" oninput="updateDrawSize(this.value)" class="w-full accent-purple-500 cursor-pointer">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- 2C. CENTRAL WORKSPACE (Contextual Property Bar + Canvas + Bottom Filmstrip) -->
        <main class="flex-1 flex flex-col overflow-hidden bg-[#0a0c0e] relative">
            
            <!-- CONTEXTUAL PROPERTY BAR (Canva Top Toolbar above Artboard) -->
            <div id="contextualBar" class="h-11 bg-[#14171b] border-b border-[#242930] px-4 flex items-center justify-between shrink-0 z-10 select-none">
                <div id="dynamicControls" class="flex items-center gap-2 overflow-x-auto no-scrollbar">
                    
                    <!-- Text Selected Toolbar -->
                    <div id="textControls" class="hidden flex items-center gap-2">
                        <!-- Quick text edit input -->
                        <div class="flex items-center gap-1.5 bg-[#1e232b] border border-[#343b47] rounded-lg px-2.5 py-1">
                            <span class="text-[10px] text-purple-400 font-bold whitespace-nowrap">Sửa chữ:</span>
                            <input type="text" id="activeTextQuickEdit" oninput="updateActiveTextContent(this.value)" placeholder="Nhập nội dung chữ..." class="bg-transparent text-xs text-white focus:outline-none w-36 sm:w-56 truncate" title="Sửa nội dung chữ trực tiếp tại đây hoặc nhấp đúp vào chữ trên slide">
                        </div>

                        <select id="fontFamilySelect" onchange="changeFontFamily(this.value)" class="bg-[#1e232b] border border-[#343b47] rounded-lg px-2.5 py-1 text-xs text-white focus:outline-none">
                            <option value="Inter, sans-serif">Inter</option>
                            <option value="Montserrat, sans-serif">Montserrat</option>
                            <option value="'Playfair Display', serif">Playfair</option>
                            <option value="Pacifico, cursive">Pacifico</option>
                            <option value="'Plus Jakarta Sans', sans-serif">Jakarta Sans</option>
                        </select>
                        <div class="flex items-center bg-[#1e232b] border border-[#343b47] rounded-lg px-1">
                            <button type="button" onclick="adjustFontSize(-4)" class="p-1 hover:text-purple-400 cursor-pointer"><i data-lucide="minus" class="w-3 h-3"></i></button>
                            <input type="text" id="fontSizeInput" value="38" onchange="setFontSize(this.value)" class="w-8 text-center bg-transparent text-xs font-bold text-white focus:outline-none">
                            <button type="button" onclick="adjustFontSize(4)" class="p-1 hover:text-purple-400 cursor-pointer"><i data-lucide="plus" class="w-3 h-3"></i></button>
                        </div>
                        <input type="color" id="textColorPicker" value="#1e1b4b" onchange="changeTextColor(this.value)" class="w-6 h-6 rounded cursor-pointer bg-transparent border-0" title="Màu chữ">
                        <button type="button" onclick="toggleBold()" class="p-1.5 rounded-lg hover:bg-[#262c36] text-slate-200 font-bold text-xs cursor-pointer" title="In đậm">B</button>
                        <button type="button" onclick="toggleItalic()" class="p-1.5 rounded-lg hover:bg-[#262c36] text-slate-200 italic text-xs cursor-pointer" title="In nghiêng">I</button>
                        <button type="button" onclick="setTextAlign('left')" class="p-1.5 rounded-lg hover:bg-[#262c36] text-slate-400 hover:text-white cursor-pointer" title="Căn trái"><i data-lucide="align-left" class="w-3.5 h-3.5"></i></button>
                        <button type="button" onclick="setTextAlign('center')" class="p-1.5 rounded-lg hover:bg-[#262c36] text-slate-400 hover:text-white cursor-pointer" title="Căn giữa"><i data-lucide="align-center" class="w-3.5 h-3.5"></i></button>
                        <button type="button" onclick="setTextAlign('right')" class="p-1.5 rounded-lg hover:bg-[#262c36] text-slate-400 hover:text-white cursor-pointer" title="Căn phải"><i data-lucide="align-right" class="w-3.5 h-3.5"></i></button>
                    </div>

                    <!-- Shape Selected Toolbar -->
                    <div id="shapeControls" class="hidden flex items-center gap-2">
                        <div class="flex items-center gap-1.5 bg-[#1e232b] border border-[#343b47] rounded-lg px-2 py-1">
                            <span class="text-[11px] text-slate-400">Màu tô:</span>
                            <input type="color" id="shapeFillPicker" value="#8b5cf6" onchange="changeShapeFill(this.value)" class="w-5 h-5 rounded cursor-pointer bg-transparent border-0">
                        </div>
                        <button type="button" onclick="flipActive('x')" class="p-1.5 rounded-lg hover:bg-[#262c36] text-slate-300 text-xs flex items-center gap-1 cursor-pointer" title="Lật ngang">
                            <i data-lucide="flip-horizontal" class="w-3.5 h-3.5"></i>
                            <span class="text-[11px]">Lật X</span>
                        </button>
                        <button type="button" onclick="flipActive('y')" class="p-1.5 rounded-lg hover:bg-[#262c36] text-slate-300 text-xs flex items-center gap-1 cursor-pointer" title="Lật dọc">
                            <i data-lucide="flip-vertical" class="w-3.5 h-3.5"></i>
                            <span class="text-[11px]">Lật Y</span>
                        </button>
                    </div>

                    <!-- Canva Contextual Default Tag -->
                    <div id="defaultControlsTag" class="flex items-center gap-2 text-xs text-slate-400">
                        <span class="px-2.5 py-0.5 rounded-md bg-[#1e232b] text-slate-300 text-[11px] font-semibold flex items-center gap-1">
                            <i data-lucide="mouse-pointer-click" class="w-3.5 h-3.5 text-purple-400"></i> Nhấp đúp vào chữ trên slide để sửa nội dung
                        </span>
                        <span class="px-2.5 py-0.5 rounded-md bg-[#1e232b] text-slate-300 text-[11px] font-semibold flex items-center gap-1">
                            <i data-lucide="clock" class="w-3 h-3 text-purple-400"></i> 5.0s
                        </span>
                    </div>

                </div>

                <!-- Universal Actions (Position, Duplicate, Delete) -->
                <div id="universalActions" class="hidden flex items-center gap-1.5">
                    <button type="button" onclick="duplicateActive()" class="p-1.5 rounded-lg hover:bg-[#262c36] text-slate-300 cursor-pointer" title="Nhân bản (Ctrl+D)">
                        <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                    </button>
                    <button type="button" onclick="deleteActive()" class="p-1.5 rounded-lg hover:bg-rose-900/40 text-slate-400 hover:text-rose-400 cursor-pointer" title="Xóa (Delete)">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    </button>
                </div>
            </div>

            <!-- CANVAS ARTBOARD VIEWPORT (16:9 Widescreen) -->
            <div id="slideViewport" class="flex-1 overflow-auto p-4 sm:p-6 flex items-center justify-center relative">
                <!-- 16:9 Slide Canvas Artboard with Drop Shadow & Purple Canva Bounding Box -->
                <div id="slideCanvasWrapper" class="relative rounded-sm shadow-[0_20px_60px_rgba(0,0,0,0.85)] border border-slate-700/60 overflow-hidden" style="width: 960px; height: 540px;">
                    <canvas id="canvaSlideCanvas"></canvas>
                </div>
            </div>

            <!-- 3. BOTTOM CAROUSEL FILMSTRIP (Chuẩn Canva Presentation ở Đáy Màn Hình!) -->
            <div id="bottomFilmstripWrapper" class="bg-[#14171b] border-t border-[#242930] flex flex-col shrink-0 z-20 transition-all duration-200 select-none">
                
                <!-- Thumbnails Horizontal Scroll Strip -->
                <div id="filmstripScrollStrip" class="h-28 px-4 py-2.5 flex items-center gap-3 overflow-x-auto no-scrollbar">
                    
                    <!-- Thumbnails list dynamically rendered -->
                    <div id="bottomThumbnailsList" class="flex items-center gap-3 shrink-0">
                        <!-- Dynamic Thumbnails -->
                    </div>

                    <!-- Add New Page Button (+) -->
                    <button type="button" onclick="addNewSlidePage()" class="w-36 h-20 rounded-xl border-2 border-dashed border-[#343c48] hover:border-purple-500 bg-[#1b1f26] hover:bg-[#222730] flex flex-col items-center justify-center gap-1 text-slate-400 hover:text-white transition shrink-0 group cursor-pointer" title="Thêm trang slide mới">
                        <i data-lucide="plus" class="w-5 h-5 text-purple-400 group-hover:scale-125 transition-transform"></i>
                        <span class="text-[10px] font-bold">Thêm trang</span>
                    </button>
                </div>

                <!-- Bottom Bar Controls (Ghi chú, Đếm ngược, Zoom, Trang x / y, Toàn màn hình) -->
                <div class="h-9 px-4 border-t border-[#20242a] flex items-center justify-between text-xs text-slate-400 bg-[#101215]">
                    <!-- Left: Notes & Timer -->
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="toggleSpeakerNotesModal()" class="flex items-center gap-1 hover:text-white transition text-[11px] font-semibold cursor-pointer" title="Xem và soạn ghi chú cho slide này">
                            <i data-lucide="file-text" class="w-3.5 h-3.5 text-slate-400"></i>
                            <span>Ghi chú</span>
                        </button>
                        <button type="button" onclick="toggleCountdownTimerModal()" class="flex items-center gap-1 hover:text-white transition text-[11px] font-semibold cursor-pointer" title="Đồng hồ hẹn giờ thuyết trình">
                            <i data-lucide="timer" class="w-3.5 h-3.5 text-slate-400"></i>
                            <span id="countdownTimerLabel">Đếm ngược</span>
                        </button>
                        <button type="button" onclick="duplicateCurrentSlide()" class="flex items-center gap-1 hover:text-white transition text-[11px] font-semibold cursor-pointer" title="Nhân bản trang slide hiện tại">
                            <i data-lucide="copy" class="w-3.5 h-3.5 text-slate-400"></i>
                            <span>Nhân bản</span>
                        </button>
                        <button type="button" onclick="deleteCurrentSlide()" class="flex items-center gap-1 hover:text-rose-400 transition text-[11px] font-semibold cursor-pointer" title="Xóa trang slide hiện tại">
                            <i data-lucide="trash" class="w-3.5 h-3.5 text-slate-400"></i>
                            <span>Xóa trang</span>
                        </button>
                    </div>

                    <!-- Center: Filmstrip Toggle (^) -->
                    <button type="button" onclick="toggleBottomFilmstrip()" class="p-1 rounded hover:bg-[#20252d] text-slate-400 hover:text-white transition flex items-center gap-1 text-[11px] cursor-pointer" title="Ẩn/Hiện thanh trang">
                        <i id="filmstripToggleIcon" data-lucide="chevron-down" class="w-4 h-4"></i>
                    </button>

                    <!-- Right: Zoom, Page counter, Grid view, Fullscreen -->
                    <div class="flex items-center gap-3">
                        <!-- Zoom Slider -->
                        <div class="flex items-center gap-1.5">
                            <button type="button" onclick="adjustZoom(-0.1)" class="p-0.5 hover:text-white cursor-pointer"><i data-lucide="minus" class="w-3 h-3"></i></button>
                            <input type="range" id="zoomRangeSlider" min="30" max="150" value="100" oninput="setZoomBySlider(this.value)" class="w-16 accent-purple-500 cursor-pointer">
                            <button type="button" onclick="adjustZoom(0.1)" class="p-0.5 hover:text-white cursor-pointer"><i data-lucide="plus" class="w-3 h-3"></i></button>
                            <span id="zoomPercentText" class="w-9 text-center text-[10px] font-bold text-slate-300">100%</span>
                        </div>

                        <button type="button" onclick="fitSlideToScreen()" class="px-2 py-0.5 rounded bg-[#1e232b] hover:bg-[#282f3a] text-[10px] font-semibold text-slate-300 hover:text-white transition cursor-pointer">Vừa khung</button>

                        <div class="h-3 w-[1px] bg-[#242930]"></div>

                        <!-- Page Indicator -->
                        <span id="pageIndicatorText" class="font-extrabold text-[11px] text-white">Trang 1 / 10</span>

                        <!-- Fullscreen Presentation Icon -->
                        <button type="button" onclick="startPresentationMode()" class="p-1 rounded hover:bg-[#1e232b] text-slate-400 hover:text-white transition cursor-pointer" title="Toàn màn hình (F5)">
                            <i data-lucide="maximize" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>
                </div>

            </div>
        </main>
    </div>

</div>

<!-- 4. SPEAKER NOTES MODAL / DRAWER -->
<div id="speakerNotesModal" class="hidden fixed bottom-14 left-4 z-40 w-80 sm:w-96 rounded-2xl bg-[#191c22] border border-[#343b46] shadow-2xl p-4 text-xs space-y-2.5">
    <div class="flex items-center justify-between border-b border-[#242930] pb-2">
        <div class="flex items-center gap-1.5 font-bold text-white">
            <i data-lucide="file-text" class="w-4 h-4 text-purple-400"></i>
            <span>Ghi Chú Người Thuyết Trình</span>
        </div>
        <button type="button" onclick="toggleSpeakerNotesModal()" class="text-slate-400 hover:text-white"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>
    <p class="text-[10px] text-slate-400">Ghi chú riêng cho trang slide hiện tại (chỉ bạn nhìn thấy khi thuyết trình):</p>
    <textarea id="speakerNotesTextarea" oninput="saveCurrentSlideNotes(this.value)" rows="5" placeholder="Nhập ghi chú cho trang slide này..." class="w-full p-2.5 rounded-xl bg-[#20242b] border border-[#2d333e] text-xs text-white placeholder-slate-500 focus:outline-none focus:border-purple-500 resize-none"></textarea>
</div>

<!-- 5. COUNTDOWN TIMER MODAL -->
<div id="countdownTimerModal" class="hidden fixed bottom-14 left-24 z-40 w-72 rounded-2xl bg-[#191c22] border border-[#343b46] shadow-2xl p-4 text-xs space-y-3">
    <div class="flex items-center justify-between border-b border-[#242930] pb-2">
        <div class="flex items-center gap-1.5 font-bold text-white">
            <i data-lucide="timer" class="w-4 h-4 text-amber-400"></i>
            <span>Đếm Ngược Thời Gian</span>
        </div>
        <button type="button" onclick="toggleCountdownTimerModal()" class="text-slate-400 hover:text-white"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>
    <div class="text-center py-2">
        <span id="countdownDisplay" class="text-3xl font-black text-amber-400 font-mono tracking-wider">05:00</span>
    </div>
    <div class="grid grid-cols-3 gap-1.5 text-center">
        <button type="button" onclick="setTimerMinutes(5)" class="py-1 rounded-lg bg-[#20242b] hover:bg-[#282f3a] text-slate-300 font-bold">5 phút</button>
        <button type="button" onclick="setTimerMinutes(10)" class="py-1 rounded-lg bg-[#20242b] hover:bg-[#282f3a] text-slate-300 font-bold">10 phút</button>
        <button type="button" onclick="setTimerMinutes(15)" class="py-1 rounded-lg bg-[#20242b] hover:bg-[#282f3a] text-slate-300 font-bold">15 phút</button>
    </div>
    <div class="flex gap-2">
        <button type="button" id="btnToggleTimer" onclick="toggleTimerRunning()" class="flex-1 py-1.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold transition">Bắt đầu</button>
        <button type="button" onclick="resetTimer()" class="px-3 py-1.5 rounded-xl bg-[#20242b] hover:bg-[#292f3a] text-slate-300 font-bold transition">Đặt lại</button>
    </div>
</div>

<!-- 6. PRESENTATION FULLSCREEN OVERLAY (Chế Độ Trình Chiếu F5) -->
<div id="presentationOverlay" class="hidden fixed inset-0 z-50 bg-black flex flex-col items-center justify-center select-none overflow-hidden">
    <button type="button" onclick="exitPresentationMode()" class="absolute top-4 right-4 z-50 p-2.5 rounded-full bg-black/60 hover:bg-black/90 text-white/80 hover:text-white transition cursor-pointer" title="Thoát trình chiếu (Esc)">
        <i data-lucide="x" class="w-6 h-6"></i>
    </button>

    <div id="presentationViewport" class="relative max-w-[95vw] max-h-[90vh] shadow-2xl flex items-center justify-center">
        <canvas id="presenterCanvas"></canvas>
    </div>

    <!-- Floating Navigation Bar at Bottom -->
    <div class="absolute bottom-6 px-4 py-2 rounded-2xl bg-black/75 backdrop-blur-md border border-white/20 flex items-center gap-4 text-white text-sm shadow-2xl z-50">
        <button type="button" onclick="presentPrevSlide()" class="p-1.5 rounded-lg hover:bg-white/20 transition cursor-pointer" title="Trang trước (←)">
            <i data-lucide="chevron-left" class="w-5 h-5"></i>
        </button>
        <span id="presenterCounter" class="font-bold text-xs tracking-wider min-w-[50px] text-center">1 / 10</span>
        <button type="button" onclick="presentNextSlide()" class="p-1.5 rounded-lg hover:bg-white/20 transition cursor-pointer" title="Trang sau (→ hoặc Phím cách)">
            <i data-lucide="chevron-right" class="w-5 h-5"></i>
        </button>
    </div>
</div>

@push('scripts')
<script src="{{ asset('vendor/fabric.min.js') }}"></script>
<script src="{{ asset('vendor/jszip.min.js') }}"></script>
<script src="{{ asset('vendor/pptxgen.bundle.js') }}"></script>
<script src="{{ asset('vendor/jspdf.umd.min.js') }}"></script>
<script>
    if (typeof fabric === 'undefined') {
        document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"><\/script>');
    }
    if (typeof JSZip === 'undefined') {
        document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"><\/script>');
    }
</script>

<script>
    /**
     * ZIISLIDE STUDIO: CANVA PRESENTATION ARCHITECTURE
     */
    let canvas = null;
    let presenterCanvas = null;
    let currentSlideIndex = 0;
    let slidesDeck = []; // Array of slide states { background, duration, notes, objects }
    let currentZoom = 1.0;
    let isPresenting = false;
    let isFilmstripVisible = true;
    let isDrawingMode = false;
    let currentRatio = '16:9'; // '16:9' or '4:3'

    // Base Canvas Dimensions (0.5x of 1920x1080)
    let BASE_WIDTH = 960;
    let BASE_HEIGHT = 540;

    // Undo / Redo History Stack
    let historyStack = [];
    let historyIndex = -1;
    let isRecordingHistory = true;

    // Timer state
    let timerTotalSeconds = 300; // 5 mins
    let timerRemainingSeconds = 300;
    let timerInterval = null;
    let isTimerRunning = false;

    // Global Pre-defined Templates Library (8+ Complete Themed Decks)
    const TEMPLATES_LIBRARY = [
        {
            id: 'fun_best_friends',
            category: 'fun',
            title: 'Nhiều màu sắc Bạn thân nhất Vui nhộn',
            desc: 'Bộ mẫu vui nhộn kỷ niệm bạn thân với minh họa chú mèo xanh đeo kính râm nằm trên mây, khủng long và khung ảnh dán băng dính.',
            badge: '10 trang • Vui nhộn',
            bg: '#e0d7f5',
            previewIcon: '🐱☁️',
            slides: [
                {
                    background: '#e0d7f5',
                    objects: [
                        { type: 'i-text', text: 'Cuộc sống sẽ hoàn\ntoàn khác nếu không\ncó cậu bên cạnh tớ.', left: 80, top: 120, fontSize: 38, fontWeight: 'bold', fill: '#1e1b4b', lineHeight: 1.3, fontFamily: 'Montserrat, sans-serif' },
                        { type: 'i-text', text: '☁️', left: 540, top: 110, fontSize: 110, originX: 'center' },
                        { type: 'i-text', text: '🐱', left: 530, top: 270, fontSize: 150, originX: 'center' },
                        { type: 'i-text', text: '🕶️', left: 530, top: 250, fontSize: 55, originX: 'center' },
                        { type: 'i-text', text: '✨', left: 660, top: 260, fontSize: 36 },
                        { type: 'i-text', text: '★', left: 640, top: 100, fontSize: 30, fill: '#64748b' },
                        { type: 'i-text', text: '✦', left: 780, top: 170, fontSize: 32, fill: '#64748b' },
                        { type: 'i-text', text: 'Chúng ta song hành\ncùng nhau như bơ đậu\nphộng và mứt trái\ncây vậy.', left: 730, top: 280, fontSize: 18, fill: '#334155', lineHeight: 1.4, fontFamily: 'Inter, sans-serif' }
                    ]
                },
                {
                    background: '#e2f0d9',
                    objects: [
                        { type: 'i-text', text: '🦖', left: 190, top: 250, fontSize: 130, originX: 'center' },
                        { type: 'i-text', text: 'Hẹn gặp lại\nvào lần tới!', left: 480, top: 150, fontSize: 48, fontWeight: 'bold', fill: '#14532d', originX: 'center', fontFamily: 'Montserrat, sans-serif' },
                        { type: 'i-text', text: 'Mọi khoảnh khắc bên bạn đều ngập tràn tiếng cười và niềm vui.', left: 480, top: 280, fontSize: 20, fill: '#166534', originX: 'center', fontFamily: 'Inter, sans-serif' },
                        { type: 'i-text', text: '🌸', left: 780, top: 250, fontSize: 110, originX: 'center' }
                    ]
                },
                {
                    background: '#fce5cd',
                    objects: [
                        { type: 'i-text', text: 'Chúng ta chia sẻ những\ncâu chuyện cười hài hước\nnhất và làm cho nhau cười.', left: 90, top: 160, fontSize: 34, fontWeight: 'bold', fill: '#7c2d12', lineHeight: 1.3, fontFamily: 'Montserrat, sans-serif' },
                        { type: 'i-text', text: '🐸', left: 720, top: 260, fontSize: 140, originX: 'center' },
                        { type: 'i-text', text: '🎉', left: 620, top: 140, fontSize: 60 },
                        { type: 'i-text', text: '🎈', left: 810, top: 150, fontSize: 60 }
                    ]
                },
                {
                    background: '#d9e1f2',
                    objects: [
                        { type: 'i-text', text: 'KỶ NIỆM TÌNH BẠN ĐÁNG NHỚ', left: 480, top: 70, fontSize: 34, fontWeight: 'bold', fill: '#1e3a8a', originX: 'center', fontFamily: 'Montserrat, sans-serif' },
                        { type: 'rect', left: 160, top: 150, width: 280, height: 210, rx: 16, ry: 16, fill: '#ffffff' },
                        { type: 'i-text', text: '📸', left: 300, top: 230, fontSize: 70, originX: 'center' },
                        { type: 'rect', left: 520, top: 150, width: 280, height: 210, rx: 16, ry: 16, fill: '#ffffff' },
                        { type: 'i-text', text: '👭', left: 660, top: 230, fontSize: 70, originX: 'center' },
                        { type: 'i-text', text: 'Những chuyến đi cùng nhau luôn là ký ức đẹp nhất.', left: 480, top: 410, fontSize: 18, fill: '#334155', originX: 'center', fontFamily: 'Inter, sans-serif' }
                    ]
                },
                {
                    background: '#f4cccc',
                    objects: [
                        { type: 'i-text', text: 'Chúng ta yêu thích\nnhững thứ giống nhau.', left: 120, top: 170, fontSize: 38, fontWeight: 'bold', fill: '#881337', lineHeight: 1.3, fontFamily: 'Montserrat, sans-serif' },
                        { type: 'i-text', text: '🍓', left: 620, top: 180, fontSize: 80 },
                        { type: 'i-text', text: '☕', left: 730, top: 180, fontSize: 80 },
                        { type: 'i-text', text: '🍰', left: 670, top: 290, fontSize: 80 }
                    ]
                },
                {
                    background: '#f3e8fd',
                    objects: [
                        { type: 'i-text', text: 'Chúng ta luôn đồng hành\nvà hỗ trợ lẫn nhau.', left: 480, top: 120, fontSize: 38, fontWeight: 'bold', fill: '#581c87', originX: 'center', fontFamily: 'Montserrat, sans-serif' },
                        { type: 'i-text', text: '🤝', left: 480, top: 260, fontSize: 120, originX: 'center' },
                        { type: 'i-text', text: 'Dù ở bất cứ nơi đâu, chỉ cần một cuộc gọi là có mặt.', left: 480, top: 400, fontSize: 20, fill: '#6b21a8', originX: 'center', fontFamily: 'Inter, sans-serif' }
                    ]
                },
                {
                    background: '#e0f4ff',
                    objects: [
                        { type: 'i-text', text: 'CÙNG NHAU CHINH PHỤC ƯỚC MƠ', left: 480, top: 90, fontSize: 36, fontWeight: 'bold', fill: '#0369a1', originX: 'center', fontFamily: 'Montserrat, sans-serif' },
                        { type: 'i-text', text: '🚀', left: 480, top: 240, fontSize: 110, originX: 'center' },
                        { type: 'i-text', text: 'Mục tiêu lớn hơn mỗi ngày khi có người bạn đáng tin cậy bên cạnh.', left: 480, top: 380, fontSize: 19, fill: '#0c4a6e', originX: 'center', fontFamily: 'Inter, sans-serif' }
                    ]
                },
                {
                    background: '#fff2cc',
                    objects: [
                        { type: 'i-text', text: 'CẢM ƠN VÌ ĐÃ LUÔN Ở ĐÂY', left: 480, top: 130, fontSize: 38, fontWeight: 'bold', fill: '#854d0e', originX: 'center', fontFamily: 'Montserrat, sans-serif' },
                        { type: 'i-text', text: '💖 💌', left: 480, top: 260, fontSize: 80, originX: 'center' },
                        { type: 'i-text', text: 'Tình bạn của chúng ta là món quà tuyệt vời nhất.', left: 480, top: 380, fontSize: 20, fill: '#713f12', originX: 'center', fontFamily: 'Inter, sans-serif' }
                    ]
                },
                {
                    background: '#f1f5f9',
                    objects: [
                        { type: 'i-text', text: 'MÃI MÃI LÀ BẠN THÂN NHÉ!', left: 480, top: 140, fontSize: 44, fontWeight: '900', fill: '#0f172a', originX: 'center', fontFamily: 'Montserrat, sans-serif' },
                        { type: 'i-text', text: '🦄', left: 480, top: 280, fontSize: 100, originX: 'center' }
                    ]
                },
                {
                    background: '#e6fffa',
                    objects: [
                        { type: 'i-text', text: 'TRANG TÀI NGUYÊN & BIỂU TƯỢNG', left: 480, top: 80, fontSize: 30, fontWeight: 'bold', fill: '#115e59', originX: 'center', fontFamily: 'Montserrat, sans-serif' },
                        { type: 'i-text', text: '🐱  🦖  🐸  📸  🍓  💖  🚀  🦄', left: 480, top: 200, fontSize: 50, originX: 'center' },
                        { type: 'i-text', text: 'Sử dụng các biểu tượng trên để trang trí cho bài thuyết trình của bạn.', left: 480, top: 320, fontSize: 19, fill: '#134e4a', originX: 'center', fontFamily: 'Inter, sans-serif' }
                    ]
                }
            ]
        },
        {
            id: 'corporate_kpi',
            category: 'business',
            title: 'Báo Cáo Doanh Nghiệp & KPI 2026',
            desc: 'Thiết kế sang trọng cho báo cáo tài chính, tổng kết năm và chiến lược tăng trưởng doanh thu.',
            badge: '5 trang • Kinh doanh',
            bg: '#0f172a',
            previewIcon: '🏢📊',
            slides: [
                {
                    background: '#0f172a',
                    objects: [
                        { type: 'i-text', text: 'BÁO CÁO HOẠT ĐỘNG DOANH NGHIỆP', left: 80, top: 150, fontSize: 42, fontWeight: 'bold', fill: '#38bdf8', fontFamily: 'Montserrat, sans-serif' },
                        { type: 'i-text', text: 'Chiến Lược Tăng Trưởng & Tối Ưu Hóa Vận Hành 2026', left: 80, top: 230, fontSize: 22, fill: '#94a3b8', fontFamily: 'Inter, sans-serif' },
                        { type: 'i-text', text: 'Người trình bày: Ban Giám Đốc • Ngày: Quý I/2026', left: 80, top: 350, fontSize: 16, fill: '#64748b' }
                    ]
                },
                {
                    background: '#0f172a',
                    objects: [
                        { type: 'i-text', text: 'MỤC TIÊU & CHỈ SỐ KPI CHÍNH', left: 80, top: 80, fontSize: 36, fontWeight: 'bold', fill: '#38bdf8', fontFamily: 'Montserrat, sans-serif' },
                        { type: 'rect', left: 80, top: 170, width: 240, height: 190, rx: 16, ry: 16, fill: '#1e293b' },
                        { type: 'i-text', text: '+185%', left: 200, top: 210, fontSize: 44, fontWeight: 'bold', fill: '#10b981', originX: 'center' },
                        { type: 'i-text', text: 'Tăng trưởng doanh thu', left: 200, top: 290, fontSize: 16, fill: '#94a3b8', originX: 'center' },
                        { type: 'rect', left: 360, top: 170, width: 240, height: 190, rx: 16, ry: 16, fill: '#1e293b' },
                        { type: 'i-text', text: '99.9%', left: 480, top: 210, fontSize: 44, fontWeight: 'bold', fill: '#38bdf8', originX: 'center' },
                        { type: 'i-text', text: 'Độ hài lòng người dùng', left: 480, top: 290, fontSize: 16, fill: '#94a3b8', originX: 'center' },
                        { type: 'rect', left: 640, top: 170, width: 240, height: 190, rx: 16, ry: 16, fill: '#1e293b' },
                        { type: 'i-text', text: '0đ', left: 760, top: 210, fontSize: 44, fontWeight: 'bold', fill: '#f59e0b', originX: 'center' },
                        { type: 'i-text', text: 'Chi phí hạ tầng máy chủ', left: 760, top: 290, fontSize: 16, fill: '#94a3b8', originX: 'center' }
                    ]
                },
                {
                    background: '#0f172a',
                    objects: [
                        { type: 'i-text', text: 'PHÂN TÍCH THỊ TRƯỜNG & ĐỐI THỦ', left: 80, top: 80, fontSize: 36, fontWeight: 'bold', fill: '#38bdf8', fontFamily: 'Montserrat, sans-serif' },
                        { type: 'i-text', text: '1. Mở rộng tệp khách hàng trực tuyến 100% tự động\n2. Cắt giảm 80% thời gian xử lý thủ công\n3. Tối ưu hóa chi phí vận hành với nền tảng Client-Side', left: 80, top: 180, fontSize: 22, fill: '#cbd5e1', lineHeight: 1.6 }
                    ]
                },
                {
                    background: '#0f172a',
                    objects: [
                        { type: 'i-text', text: 'KẾ HOẠCH HÀNH ĐỘNG QUÝ TIẾP THEO', left: 80, top: 80, fontSize: 36, fontWeight: 'bold', fill: '#38bdf8', fontFamily: 'Montserrat, sans-serif' },
                        { type: 'rect', left: 80, top: 180, width: 800, height: 100, rx: 16, ry: 16, fill: '#1e293b' },
                        { type: 'i-text', text: 'Tháng 1 - 2: Hoàn thiện nâng cấp giao diện chuẩn Canva Presentation', left: 120, top: 215, fontSize: 20, fill: '#f8fafc' },
                        { type: 'rect', left: 80, top: 310, width: 800, height: 100, rx: 16, ry: 16, fill: '#1e293b' },
                        { type: 'i-text', text: 'Tháng 3 - 4: Ra mắt thị trường và tiếp cận 100,000 người dùng', left: 120, top: 345, fontSize: 20, fill: '#38bdf8' }
                    ]
                },
                {
                    background: '#0f172a',
                    objects: [
                        { type: 'i-text', text: 'XIN CẢM ƠN QUÝ ĐỐI TÁC', left: 480, top: 190, fontSize: 46, fontWeight: 'bold', fill: '#38bdf8', originX: 'center' },
                        { type: 'i-text', text: 'Phiên hỏi đáp & Thảo luận chi tiết (Q&A)', left: 480, top: 270, fontSize: 22, fill: '#94a3b8', originX: 'center' }
                    ]
                }
            ]
        },
        {
            id: 'startup_pitch',
            category: 'pitch',
            title: 'Khởi Nghiệp & Pitch Deck Gọi Vốn',
            desc: 'Slide thuyết trình gọi vốn chuyên nghiệp với cấu trúc Vấn đề, Giải pháp, Thị trường và Đội ngũ.',
            badge: '5 trang • Pitch Deck',
            bg: '#18181b',
            previewIcon: '🚀💡',
            slides: [
                {
                    background: '#18181b',
                    objects: [
                        { type: 'i-text', text: 'STARTUP PITCH DECK 2026', left: 480, top: 150, fontSize: 48, fontWeight: '900', fill: '#ec4899', originX: 'center' },
                        { type: 'i-text', text: 'Giải pháp Đột Phá Tiện Ích Trực Tuyến 0đ Máy Chủ', left: 480, top: 230, fontSize: 22, fill: '#f1f5f9', originX: 'center' },
                        { type: 'i-text', text: 'Seeking $500,000 Seed Round', left: 480, top: 330, fontSize: 18, fill: '#f472b6', originX: 'center' }
                    ]
                },
                {
                    background: '#18181b',
                    objects: [
                        { type: 'i-text', text: 'VẤN ĐỀ CỦA THỊ TRƯỜNG', left: 80, top: 80, fontSize: 36, fontWeight: 'bold', fill: '#ec4899' },
                        { type: 'i-text', text: '• Phần mềm chỉnh sửa phức tạp, nặng máy và tốn phí cao.\n• Người dùng lo ngại bị lộ tài liệu nhạy cảm khi upload lên server.\n• Tốc độ xử lý phụ thuộc vào mạng và hàng đợi máy chủ.', left: 80, top: 180, fontSize: 22, fill: '#e2e8f0', lineHeight: 1.6 }
                    ]
                },
                {
                    background: '#18181b',
                    objects: [
                        { type: 'i-text', text: 'GIẢI PHÁP ĐỘT PHÁ CỦA CHÚNG TÔI', left: 80, top: 80, fontSize: 36, fontWeight: 'bold', fill: '#34d399' },
                        { type: 'rect', left: 80, top: 170, width: 380, height: 230, rx: 16, ry: 16, fill: '#27272a' },
                        { type: 'i-text', text: '⚡ 100% Client-Side', left: 120, top: 210, fontSize: 24, fontWeight: 'bold', fill: '#34d399' },
                        { type: 'i-text', text: 'Xử lý hoàn toàn trong trình duyệt, bảo mật dữ liệu tuyệt đối.', left: 120, top: 260, fontSize: 16, fill: '#94a3b8' },
                        { type: 'rect', left: 500, top: 170, width: 380, height: 230, rx: 16, ry: 16, fill: '#27272a' },
                        { type: 'i-text', text: '💎 Chi Phí 0đ', left: 540, top: 210, fontSize: 24, fontWeight: 'bold', fill: '#38bdf8' },
                        { type: 'i-text', text: 'Biên lợi nhuận cực lớn vì không tốn GPU hay hạ tầng server.', left: 540, top: 260, fontSize: 16, fill: '#94a3b8' }
                    ]
                },
                {
                    background: '#18181b',
                    objects: [
                        { type: 'i-text', text: 'QUY MÔ THỊ TRƯỜNG (TAM / SAM / SOM)', left: 80, top: 80, fontSize: 36, fontWeight: 'bold', fill: '#ec4899' },
                        { type: 'i-text', text: '$15 Tỷ', left: 200, top: 200, fontSize: 50, fontWeight: '900', fill: '#f43f5e', originX: 'center' },
                        { type: 'i-text', text: 'Thị trường công cụ web', left: 200, top: 270, fontSize: 16, fill: '#94a3b8', originX: 'center' },
                        { type: 'i-text', text: '$2.5 Tỷ', left: 480, top: 200, fontSize: 50, fontWeight: '900', fill: '#fbbf24', originX: 'center' },
                        { type: 'i-text', text: 'Khu vực Đông Nam Á', left: 480, top: 270, fontSize: 16, fill: '#94a3b8', originX: 'center' },
                        { type: 'i-text', text: '$350 Triệu', left: 760, top: 200, fontSize: 50, fontWeight: '900', fill: '#10b981', originX: 'center' },
                        { type: 'i-text', text: 'Mục tiêu 3 năm tới', left: 760, top: 270, fontSize: 16, fill: '#94a3b8', originX: 'center' }
                    ]
                },
                {
                    background: '#18181b',
                    objects: [
                        { type: 'i-text', text: 'HÃY CÙNG CHÚNG TÔI KIẾN TẠO TƯƠNG LAI', left: 480, top: 190, fontSize: 40, fontWeight: 'bold', fill: '#ec4899', originX: 'center' },
                        { type: 'i-text', text: 'Email: contact@ziitool.pro • Hotline: 0988.xxx.xxx', left: 480, top: 270, fontSize: 20, fill: '#e2e8f0', originX: 'center' }
                    ]
                }
            ]
        },
        {
            id: 'academic_thesis',
            category: 'academic',
            title: 'Bảo Vệ Luận Văn & Đề Tài Học Thuật',
            desc: 'Slide khoa học, trang nhã dành cho sinh viên, giảng viên bảo vệ khóa luận, thạc sĩ và báo cáo môn.',
            badge: '5 trang • Giáo dục',
            bg: '#f8fafc',
            previewIcon: '🎓📚',
            slides: [
                {
                    background: '#f8fafc',
                    objects: [
                        { type: 'i-text', text: 'BẢO VỆ LUẬN VĂN TỐT NGHIỆP', left: 480, top: 130, fontSize: 42, fontWeight: 'bold', fill: '#1e3a8a', originX: 'center' },
                        { type: 'i-text', text: 'Đề tài: Nghiên cứu và xây dựng nền tảng vi tiện ích thông minh', left: 480, top: 210, fontSize: 22, fill: '#334155', originX: 'center' },
                        { type: 'i-text', text: 'Học viên: Nguyễn Văn A • GVHD: TS. Trần Văn B', left: 480, top: 320, fontSize: 18, fill: '#64748b', originX: 'center' }
                    ]
                },
                {
                    background: '#f8fafc',
                    objects: [
                        { type: 'i-text', text: 'LÝ DO CHỌN ĐỀ TÀI & TÍNH CẤP THIẾT', left: 80, top: 80, fontSize: 34, fontWeight: 'bold', fill: '#1e3a8a' },
                        { type: 'i-text', text: '1. Nhu cầu số hóa tài liệu học tập tăng trưởng mạnh mẽ.\n2. Các giải pháp hiện hữu có độ trễ cao và chi phí bản quyền lớn.\n3. Cần một công nghệ mới chạy trực tiếp tại biên (Edge Client-Side).', left: 80, top: 180, fontSize: 22, fill: '#1e293b', lineHeight: 1.6 }
                    ]
                },
                {
                    background: '#f8fafc',
                    objects: [
                        { type: 'i-text', text: 'PHƯƠNG PHÁP NGHIÊN CỨU & MÔ HÌNH', left: 80, top: 80, fontSize: 34, fontWeight: 'bold', fill: '#1e3a8a' },
                        { type: 'rect', left: 80, top: 160, width: 380, height: 220, rx: 16, ry: 16, fill: '#e0e7ff' },
                        { type: 'i-text', text: 'Phương Pháp Lý Thuyết', left: 120, top: 190, fontSize: 22, fontWeight: 'bold', fill: '#3730a3' },
                        { type: 'i-text', text: 'Nghiên cứu cấu trúc OpenXML và Canvas Rendering Pipeline.', left: 120, top: 240, fontSize: 16, fill: '#4338ca' },
                        { type: 'rect', left: 500, top: 160, width: 380, height: 220, rx: 16, ry: 16, fill: '#dcfce7' },
                        { type: 'i-text', text: 'Thực Nghiệm Ứng Dụng', left: 540, top: 190, fontSize: 22, fontWeight: 'bold', fill: '#166534' },
                        { type: 'i-text', text: 'Đo kiểm độ trễ và độ chính xác trên 1,000 bài kiểm thử.', left: 540, top: 240, fontSize: 16, fill: '#15803d' }
                    ]
                },
                {
                    background: '#f8fafc',
                    objects: [
                        { type: 'i-text', text: 'KẾT QUẢ ĐẠT ĐƯỢC & ĐÓNG GÓP', left: 80, top: 80, fontSize: 34, fontWeight: 'bold', fill: '#1e3a8a' },
                        { type: 'i-text', text: '• Hoàn thiện 18 công cụ vi tiện ích hoạt động ổn định.\n• Tối ưu thời gian chuyển đổi dưới 1.5 giây.\n• Vượt qua toàn bộ 45 bài kiểm thử tự động đạt 100%.', left: 80, top: 180, fontSize: 22, fill: '#1e293b', lineHeight: 1.6 }
                    ]
                },
                {
                    background: '#f8fafc',
                    objects: [
                        { type: 'i-text', text: 'KÍNH CHÚC HỘI ĐỒNG SỨC KHỎE', left: 480, top: 190, fontSize: 40, fontWeight: 'bold', fill: '#1e3a8a', originX: 'center' },
                        { type: 'i-text', text: 'Em xin trân trọng lắng nghe ý kiến đóng góp của Quý Thầy Cô!', left: 480, top: 270, fontSize: 20, fill: '#475569', originX: 'center' }
                    ]
                }
            ]
        },
        {
            id: 'product_marketing',
            category: 'business',
            title: 'Giới Thiệu Sản Phẩm & Marketing',
            desc: 'Slide màu sắc hiện đại cho chiến dịch quảng bá, ra mắt tính năng và thu hút khách hàng tiềm năng.',
            badge: '5 trang • Marketing',
            bg: '#fff7ed',
            previewIcon: '🛒🔥',
            slides: [
                {
                    background: '#fff7ed',
                    objects: [
                        { type: 'i-text', text: 'RA MẮT SẢN PHẨM MỚI 2026', left: 480, top: 140, fontSize: 46, fontWeight: '900', fill: '#ea580c', originX: 'center' },
                        { type: 'i-text', text: 'Khám Phá Sức Mạnh Đột Phá Cùng ZiiTool Pro', left: 480, top: 220, fontSize: 22, fill: '#7c2d12', originX: 'center' },
                        { type: 'i-text', text: 'Ưu đãi đặt sớm: Giảm 50% trọn đời', left: 480, top: 320, fontSize: 18, fill: '#c2410c', originX: 'center' }
                    ]
                },
                {
                    background: '#fff7ed',
                    objects: [
                        { type: 'i-text', text: '3 ĐẶC QUYỀN NỔI BẬT NHẤT', left: 80, top: 80, fontSize: 34, fontWeight: 'bold', fill: '#ea580c' },
                        { type: 'rect', left: 80, top: 160, width: 240, height: 210, rx: 16, ry: 16, fill: '#ffedd5' },
                        { type: 'i-text', text: '⚡ Siêu Tốc', left: 200, top: 200, fontSize: 24, fontWeight: 'bold', fill: '#c2410c', originX: 'center' },
                        { type: 'i-text', text: 'Không phải xếp hàng chờ đợi máy chủ.', left: 200, top: 260, fontSize: 15, fill: '#7c2d12', originX: 'center' },
                        { type: 'rect', left: 360, top: 160, width: 240, height: 210, rx: 16, ry: 16, fill: '#ffedd5' },
                        { type: 'i-text', text: '🔒 Bảo Mật', left: 480, top: 200, fontSize: 24, fontWeight: 'bold', fill: '#c2410c', originX: 'center' },
                        { type: 'i-text', text: 'Dữ liệu không bao giờ rời khỏi máy tính bạn.', left: 480, top: 260, fontSize: 15, fill: '#7c2d12', originX: 'center' },
                        { type: 'rect', left: 640, top: 160, width: 240, height: 210, rx: 16, ry: 16, fill: '#ffedd5' },
                        { type: 'i-text', text: '🎯 Tiện Lợi', left: 760, top: 200, fontSize: 24, fontWeight: 'bold', fill: '#c2410c', originX: 'center' },
                        { type: 'i-text', text: 'Sử dụng mọi lúc trên mọi thiết bị.', left: 760, top: 260, fontSize: 15, fill: '#7c2d12', originX: 'center' }
                    ]
                },
                {
                    background: '#fff7ed',
                    objects: [
                        { type: 'i-text', text: 'BẢNG GIÁ & CHÍNH SÁCH BẢO HÀNH', left: 80, top: 80, fontSize: 34, fontWeight: 'bold', fill: '#ea580c' },
                        { type: 'i-text', text: '• Bản Tiêu Chuẩn: Miễn phí trọn đời 100%\n• Bản Chuyên Nghiệp (Pro): Mở khóa xuất PPTX siêu phân giải\n• Hoàn tiền trong 30 ngày nếu không hài lòng', left: 80, top: 180, fontSize: 22, fill: '#431407', lineHeight: 1.6 }
                    ]
                },
                {
                    background: '#fff7ed',
                    objects: [
                        { type: 'i-text', text: 'ĐÁNH GIÁ TỪ 10,000+ KHÁCH HÀNG', left: 80, top: 80, fontSize: 34, fontWeight: 'bold', fill: '#ea580c' },
                        { type: 'i-text', text: '⭐⭐⭐⭐⭐ "Giao diện làm slide đẹp như Canva, tiện gấp 10 lần PowerPoint cài máy!"\n— Trưởng phòng Marketing, TechCorp', left: 80, top: 190, fontSize: 22, fill: '#7c2d12', lineHeight: 1.5 }
                    ]
                },
                {
                    background: '#fff7ed',
                    objects: [
                        { type: 'i-text', text: 'ĐĂNG KÝ TRẢI NGHIỆM NGAY HÔM NAY', left: 480, top: 190, fontSize: 40, fontWeight: 'bold', fill: '#ea580c', originX: 'center' },
                        { type: 'i-text', text: 'Truy cập: ziitool.pro/tool/bai-thuyet-trinh', left: 480, top: 270, fontSize: 22, fill: '#7c2d12', originX: 'center' }
                    ]
                }
            ]
        },
        {
            id: 'tech_ai',
            category: 'academic',
            title: 'Công Nghệ & Trí Tuệ Nhân Tạo (AI)',
            desc: 'Slide hiện đại phong cách Dark Mode công nghệ cho các đề tài AI, Big Data, Blockchain.',
            badge: '5 trang • Công nghệ',
            bg: '#090d16',
            previewIcon: '⚡🤖',
            slides: [
                {
                    background: '#090d16',
                    objects: [
                        { type: 'i-text', text: 'AI & NEXT-GEN COMPUTING', left: 480, top: 140, fontSize: 46, fontWeight: '900', fill: '#a855f7', originX: 'center' },
                        { type: 'i-text', text: 'Ứng Dụng Học Máy & Xử Lý Dữ Liệu Lớn Tại Trình Duyệt', left: 480, top: 220, fontSize: 22, fill: '#e2e8f0', originX: 'center' },
                        { type: 'i-text', text: 'Kỷ Nguyên Trí Tuệ Nhân Tạo 2026', left: 480, top: 320, fontSize: 18, fill: '#c084fc', originX: 'center' }
                    ]
                },
                {
                    background: '#090d16',
                    objects: [
                        { type: 'i-text', text: 'KIẾN TRÚC MÔ HÌNH HỆ THỐNG', left: 80, top: 80, fontSize: 34, fontWeight: 'bold', fill: '#a855f7' },
                        { type: 'i-text', text: '• WebAssembly & WebGPU: Khai thác sức mạnh phần cứng máy trạm.\n• Client-Side Segmentation: Tách phông ảnh tức thì dưới 50ms.\n• Vector Engine: Dựng slide 60 FPS mượt mà không độ trễ.', left: 80, top: 180, fontSize: 22, fill: '#cbd5e1', lineHeight: 1.6 }
                    ]
                },
                {
                    background: '#090d16',
                    objects: [
                        { type: 'i-text', text: 'BẢNG SO SÁNH HIỆU NĂNG TÍNH TOÁN', left: 80, top: 80, fontSize: 34, fontWeight: 'bold', fill: '#a855f7' },
                        { type: 'rect', left: 80, top: 160, width: 380, height: 220, rx: 16, ry: 16, fill: '#1e1b4b' },
                        { type: 'i-text', text: 'Mô Hình Server AI Truyền Thống', left: 120, top: 190, fontSize: 20, fontWeight: 'bold', fill: '#f43f5e' },
                        { type: 'i-text', text: 'Độ trễ: 3.5s • Chi phí: $0.05/lượt\nBảo mật: Phải upload file lên mây', left: 120, top: 240, fontSize: 16, fill: '#fda4af' },
                        { type: 'rect', left: 500, top: 160, width: 380, height: 220, rx: 16, ry: 16, fill: '#1e1b4b' },
                        { type: 'i-text', text: 'Mô Hình Client AI ZiiTool', left: 540, top: 190, fontSize: 20, fontWeight: 'bold', fill: '#10b981' },
                        { type: 'i-text', text: 'Độ trễ: 0.1s • Chi phí: 0đ\nBảo mật: Riêng tư 100% tại máy', left: 540, top: 240, fontSize: 16, fill: '#6ee7b7' }
                    ]
                },
                {
                    background: '#090d16',
                    objects: [
                        { type: 'i-text', text: 'LỘ TRÌNH PHÁT TRIỂN CÔNG NGHỆ', left: 80, top: 80, fontSize: 34, fontWeight: 'bold', fill: '#a855f7' },
                        { type: 'i-text', text: '1. Tích hợp AI sinh tự động slide từ dàn ý văn bản (Text to Deck).\n2. Xuất bản định dạng video thuyết trình MP4 có phụ đề.\n3. Hợp tác thời gian thực nhiều người cùng biên tập.', left: 80, top: 180, fontSize: 22, fill: '#e2e8f0', lineHeight: 1.6 }
                    ]
                },
                {
                    background: '#090d16',
                    objects: [
                        { type: 'i-text', text: 'XIN CẢM ƠN QUÝ KHÁN GIẢ', left: 480, top: 190, fontSize: 44, fontWeight: 'bold', fill: '#a855f7', originX: 'center' },
                        { type: 'i-text', text: 'Let\'s build intelligent systems together!', left: 480, top: 270, fontSize: 20, fill: '#94a3b8', originX: 'center' }
                    ]
                }
            ]
        }
    ];

    document.addEventListener('DOMContentLoaded', () => {
        initCanvaStudio();
    });

    function initCanvaStudio() {
        if (!window.fabric) {
            console.warn('Fabric.js is loading...');
            setTimeout(initCanvaStudio, 100);
            return;
        }

        // Initialize Fabric.js Canvas
        canvas = new fabric.Canvas('canvaSlideCanvas', {
            width: BASE_WIDTH,
            height: BASE_HEIGHT,
            backgroundColor: '#e0d7f5',
            preserveObjectStacking: true,
            selection: true
        });

        // Presenter static canvas
        presenterCanvas = new fabric.StaticCanvas('presenterCanvas', {
            width: 1280,
            height: 720,
            backgroundColor: '#e0d7f5'
        });

        // Canva Selection Styling (Signature Purple Bounding Box & Circles)
        fabric.Object.prototype.transparentCorners = false;
        fabric.Object.prototype.cornerColor = '#ffffff';
        fabric.Object.prototype.cornerStrokeColor = '#8b5cf6';
        fabric.Object.prototype.borderColor = '#8b5cf6';
        fabric.Object.prototype.cornerSize = 10;
        fabric.Object.prototype.cornerStyle = 'circle';
        fabric.Object.prototype.borderScaleFactor = 1.6;

        // Selection & Object events
        canvas.on('selection:created', onObjectSelected);
        canvas.on('selection:updated', onObjectSelected);
        canvas.on('selection:cleared', onObjectDeselected);
        canvas.on('object:modified', () => {
            pushHistoryState();
            onSlideContentChanged();
        });
        canvas.on('text:changed', () => {
            onSlideContentChanged();
        });

        // Global shortcuts
        window.addEventListener('keydown', handleGlobalKeydown);

        // Render Templates Library in Drawer
        renderTemplatesDrawerList('all');

        // Apply first template deck (Fun Best Friends) by default
        applyTemplateDeck('fun_best_friends', false);

        // Auto-fit to screen
        setTimeout(fitSlideToScreen, 120);
        window.addEventListener('resize', () => {
            if (!isPresenting) fitSlideToScreen();
        });

        // Global click to close menus
        document.addEventListener('click', (e) => {
            const dropdowns = ['fileMenuDropdown', 'resizeMenuDropdown', 'downloadDropdown'];
            dropdowns.forEach(id => {
                const el = document.getElementById(id);
                if (el && !el.classList.contains('hidden') && !el.parentElement.contains(e.target)) {
                    el.classList.add('hidden');
                }
            });
        });
    }

    /**
     * TEMPLATE DECK APPLICATION & FILMSTRIP SYNC (CRITICAL FIX)
     */
    function applyTemplateDeck(deckId, showToastNotice = true) {
        const template = TEMPLATES_LIBRARY.find(t => t.id === deckId);
        if (!template) return;

        // Set presentation title
        document.getElementById('presentationTitleInput').value = template.title.replace(/\s+/g, '_');

        // Replace entire slides deck cleanly
        slidesDeck = JSON.parse(JSON.stringify(template.slides));
        currentSlideIndex = 0;

        // Load first slide onto canvas
        loadSlidePage(0);

        // Re-render the bottom filmstrip carousel to match the new deck IMMEDIATELY!
        renderBottomThumbnails();

        // Reset history for new deck
        historyStack = [];
        historyIndex = -1;
        pushHistoryState();

        if (showToastNotice) {
            showToast(`Đã áp dụng mẫu "${template.title}" (${slidesDeck.length} trang)!`, 'success');
        }
    }

    function insertSingleSlideFromTemplate(deckId, slideIndex) {
        const template = TEMPLATES_LIBRARY.find(t => t.id === deckId);
        if (!template || !template.slides[slideIndex]) return;

        saveCurrentSlideState();
        const newSlide = JSON.parse(JSON.stringify(template.slides[slideIndex]));
        
        // Insert right after current slide
        slidesDeck.splice(currentSlideIndex + 1, 0, newSlide);
        currentSlideIndex++;

        loadSlidePage(currentSlideIndex);
        renderBottomThumbnails();
        showToast(`Đã chèn thêm trang mẫu vào bài thuyết trình!`, 'success');
    }

    function renderTemplatesDrawerList(category = 'all', searchQuery = '') {
        const container = document.getElementById('templatesDecksList');
        if (!container) return;

        let filtered = TEMPLATES_LIBRARY;
        if (category !== 'all') {
            filtered = filtered.filter(t => t.category === category);
        }
        if (searchQuery.trim()) {
            const q = searchQuery.toLowerCase().trim();
            filtered = filtered.filter(t => t.title.toLowerCase().includes(q) || t.desc.toLowerCase().includes(q));
        }

        if (filtered.length === 0) {
            container.innerHTML = `
                <div class="py-8 text-center text-slate-400 space-y-2">
                    <i data-lucide="search-x" class="w-8 h-8 mx-auto text-slate-500"></i>
                    <p>Không tìm thấy mẫu phù hợp với từ khóa.</p>
                </div>
            `;
            if (window.lucide) lucide.createIcons();
            return;
        }

        let html = '';
        filtered.forEach(deck => {
            html += `
                <div class="p-3.5 rounded-2xl bg-[#1b1f26] border border-[#2c3340] hover:border-purple-500/50 transition space-y-2.5">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <span class="text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-purple-950 text-purple-300 border border-purple-800/40">
                                ${deck.badge}
                            </span>
                            <h4 class="text-xs font-bold text-white mt-1 leading-snug">${deck.title}</h4>
                        </div>
                        <span class="text-2xl">${deck.previewIcon}</span>
                    </div>
                    <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed">${deck.desc}</p>
                    
                    <!-- Big Apply Entire Deck Button -->
                    <button type="button" onclick="applyTemplateDeck('${deck.id}')" class="w-full py-2 px-3 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-extrabold text-xs shadow-md shadow-purple-950/40 transition flex items-center justify-center gap-1.5 cursor-pointer">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                        <span>Áp dụng mẫu này (${deck.slides.length} trang)</span>
                    </button>

                    <!-- Expand / Individual Page selector toggle -->
                    <div class="pt-1">
                        <div class="flex items-center justify-between text-[10px] text-slate-400 font-semibold mb-1.5">
                            <span>Bấm để chèn từng trang:</span>
                            <span class="text-slate-500">${deck.slides.length} trang con</span>
                        </div>
                        <div class="grid grid-cols-5 gap-1.5">
            `;

            deck.slides.forEach((s, sIdx) => {
                let textExcerpt = `Trang ${sIdx + 1}`;
                if (s.objects && s.objects.length) {
                    const txt = s.objects.find(o => o.type === 'i-text' || o.type === 'text');
                    if (txt && txt.text) textExcerpt = txt.text.slice(0, 10);
                }
                html += `
                    <button type="button" onclick="insertSingleSlideFromTemplate('${deck.id}', ${sIdx})" class="h-10 rounded-lg flex flex-col items-center justify-center p-1 border border-white/10 hover:border-purple-400 transition cursor-pointer group" style="background-color: ${s.background || '#ffffff'}" title="Chèn Trang ${sIdx + 1}: ${textExcerpt}">
                        <span class="text-[8px] font-black text-slate-900 leading-none truncate">${sIdx + 1}</span>
                    </button>
                `;
            });

            html += `
                        </div>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
        if (window.lucide) lucide.createIcons();
    }

    function filterTemplateCategory(cat) {
        document.querySelectorAll('.cat-pill').forEach(btn => {
            if (btn.getAttribute('data-cat') === cat) {
                btn.className = 'cat-pill px-2.5 py-1 rounded-lg text-[11px] font-bold transition whitespace-nowrap bg-purple-600 text-white cursor-pointer';
            } else {
                btn.className = 'cat-pill px-2.5 py-1 rounded-lg text-[11px] font-bold transition whitespace-nowrap bg-[#20242b] text-slate-300 hover:text-white cursor-pointer';
            }
        });
        const query = document.getElementById('templateSearchInput') ? document.getElementById('templateSearchInput').value : '';
        renderTemplatesDrawerList(cat, query);
    }

    function filterTemplateDecks(query) {
        const activePill = document.querySelector('.cat-pill.bg-purple-600');
        const cat = activePill ? activePill.getAttribute('data-cat') : 'all';
        renderTemplatesDrawerList(cat, query);
    }

    /**
     * SLIDE CANVAS RENDERING & FILMSTRIP
     */
    function saveCurrentSlideState() {
        if (!canvas || !slidesDeck[currentSlideIndex]) return;
        
        const currentObjs = canvas.getObjects().map(obj => {
            if (obj instanceof fabric.IText || obj.type === 'i-text' || obj.type === 'text') {
                return {
                    type: 'i-text',
                    text: obj.text || '',
                    left: Math.round(obj.left),
                    top: Math.round(obj.top),
                    fontSize: obj.fontSize || 28,
                    fontWeight: obj.fontWeight || 'normal',
                    fontFamily: obj.fontFamily || 'Montserrat, sans-serif',
                    fill: obj.fill || '#1e1b4b',
                    originX: obj.originX || 'left',
                    originY: obj.originY || 'top',
                    lineHeight: obj.lineHeight || 1.3,
                    scaleX: obj.scaleX || 1,
                    scaleY: obj.scaleY || 1,
                    angle: obj.angle || 0
                };
            } else if (obj instanceof fabric.Rect || obj.type === 'rect') {
                return {
                    type: 'rect',
                    left: Math.round(obj.left),
                    top: Math.round(obj.top),
                    width: obj.width || 100,
                    height: obj.height || 60,
                    rx: obj.rx || 0,
                    ry: obj.ry || 0,
                    fill: obj.fill || '#8b5cf6',
                    scaleX: obj.scaleX || 1,
                    scaleY: obj.scaleY || 1,
                    angle: obj.angle || 0
                };
            } else if (obj instanceof fabric.Circle || obj.type === 'circle') {
                return {
                    type: 'circle',
                    left: Math.round(obj.left),
                    top: Math.round(obj.top),
                    radius: obj.radius || 40,
                    fill: obj.fill || '#0ea5e9',
                    scaleX: obj.scaleX || 1,
                    scaleY: obj.scaleY || 1,
                    angle: obj.angle || 0
                };
            }
            return {
                type: 'i-text',
                text: obj.text || '',
                left: Math.round(obj.left || 100),
                top: Math.round(obj.top || 100),
                fontSize: obj.fontSize || 28,
                fill: obj.fill || '#1e1b4b'
            };
        });

        slidesDeck[currentSlideIndex].background = canvas.backgroundColor || '#e0d7f5';
        slidesDeck[currentSlideIndex].objects = currentObjs;
    }

    function switchSlidePage(index) {
        if (index < 0 || index >= slidesDeck.length || index === currentSlideIndex) return;
        saveCurrentSlideState();
        currentSlideIndex = index;
        loadSlidePage(currentSlideIndex);
        updateActiveThumbnailUI();
    }

    function loadSlidePage(index) {
        if (!canvas) return;
        const slide = slidesDeck[index];
        if (!slide) return;

        isRecordingHistory = false;
        canvas.clear();
        canvas.backgroundColor = slide.background || '#ffffff';

        if (slide.objects && Array.isArray(slide.objects)) {
            slide.objects.forEach(objData => {
                let obj = null;
                if (objData.type === 'i-text' || objData.type === 'text') {
                    obj = new fabric.IText(objData.text || '', {
                        left: objData.left,
                        top: objData.top,
                        fontSize: objData.fontSize || 28,
                        fontWeight: objData.fontWeight || 'normal',
                        fontFamily: objData.fontFamily || 'Montserrat, sans-serif',
                        fill: objData.fill || '#1e1b4b',
                        originX: objData.originX || 'left',
                        originY: objData.originY || 'top',
                        lineHeight: objData.lineHeight || 1.3,
                        scaleX: objData.scaleX || 1,
                        scaleY: objData.scaleY || 1,
                        angle: objData.angle || 0,
                        editable: true,
                        selectable: true,
                        hasControls: true,
                        hasBorders: true,
                        cursorDelay: 250
                    });
                } else if (objData.type === 'rect') {
                    obj = new fabric.Rect({
                        left: objData.left,
                        top: objData.top,
                        width: objData.width || 100,
                        height: objData.height || 60,
                        rx: objData.rx || 0,
                        ry: objData.ry || 0,
                        fill: objData.fill || '#8b5cf6',
                        scaleX: objData.scaleX || 1,
                        scaleY: objData.scaleY || 1,
                        angle: objData.angle || 0,
                        selectable: true,
                        hasControls: true,
                        hasBorders: true
                    });
                } else if (objData.type === 'circle') {
                    obj = new fabric.Circle({
                        left: objData.left,
                        top: objData.top,
                        radius: objData.radius || 40,
                        fill: objData.fill || '#0ea5e9',
                        scaleX: objData.scaleX || 1,
                        scaleY: objData.scaleY || 1,
                        angle: objData.angle || 0,
                        selectable: true,
                        hasControls: true,
                        hasBorders: true
                    });
                } else if (objData.type === 'image' && objData.src) {
                    fabric.Image.fromURL(objData.src, (img) => {
                        img.set({
                            left: objData.left,
                            top: objData.top,
                            scaleX: objData.scaleX || 1,
                            scaleY: objData.scaleY || 1,
                            angle: objData.angle || 0,
                            selectable: true
                        });
                        canvas.add(img);
                        canvas.renderAll();
                    });
                    return;
                }

                if (obj) canvas.add(obj);
            });
        }

        canvas.renderAll();
        isRecordingHistory = true;
        updateFooterIndicators();

        // Update speaker notes textarea if open
        const notesBox = document.getElementById('speakerNotesTextarea');
        if (notesBox) {
            notesBox.value = slide.notes || '';
        }
    }

    function renderBottomThumbnails() {
        const list = document.getElementById('bottomThumbnailsList');
        if (!list) return;
        list.innerHTML = '';

        slidesDeck.forEach((slide, idx) => {
            const isActive = idx === currentSlideIndex;
            const card = document.createElement('div');
            card.id = `thumbCard_${idx}`;
            card.className = `w-36 h-20 rounded-xl overflow-hidden cursor-pointer relative transition shadow-md shrink-0 select-none ${
                isActive ? 'ring-2 ring-purple-500 scale-[1.02]' : 'ring-1 ring-[#2c3340] opacity-80 hover:opacity-100 hover:scale-[1.01]'
            }`;
            card.style.backgroundColor = (slide.background && typeof slide.background === 'string') ? slide.background : '#ffffff';
            card.onclick = () => switchSlidePage(idx);

            // Thumbnail preview excerpt
            let excerpt = `Trang ${idx + 1}`;
            if (slide.objects && Array.isArray(slide.objects)) {
                const textObj = slide.objects.find(o => o.type === 'i-text' || o.type === 'text');
                if (textObj && textObj.text) {
                    excerpt = textObj.text.replace(/\n/g, ' ').slice(0, 18) + '...';
                }
            }

            card.innerHTML = `
                <div class="absolute inset-0 p-2 flex flex-col justify-between pointer-events-none">
                    <span id="thumbExcerpt_${idx}" class="text-[9px] font-black text-slate-800 truncate leading-tight drop-shadow-sm">${excerpt}</span>
                    <div class="flex items-center justify-between">
                        <span class="px-1.5 py-0.5 rounded-md bg-black/60 text-white text-[8px] font-bold">${idx + 1}</span>
                        <span class="text-[8px] text-slate-700 font-bold">${slide.duration || 5}s</span>
                    </div>
                </div>
            `;
            list.appendChild(card);
        });

        updateFooterIndicators();
    }

    function updateActiveThumbnailUI() {
        slidesDeck.forEach((_, idx) => {
            const card = document.getElementById(`thumbCard_${idx}`);
            if (card) {
                if (idx === currentSlideIndex) {
                    card.className = 'w-36 h-20 rounded-xl overflow-hidden cursor-pointer relative transition shadow-md shrink-0 select-none ring-2 ring-purple-500 scale-[1.02]';
                } else {
                    card.className = 'w-36 h-20 rounded-xl overflow-hidden cursor-pointer relative transition shadow-md shrink-0 select-none ring-1 ring-[#2c3340] opacity-80 hover:opacity-100 hover:scale-[1.01]';
                }
            }
        });
        updateFooterIndicators();
    }

    function updateBottomThumbnailsExcerpt(idx) {
        const slide = slidesDeck[idx];
        if (!slide) return;
        const excerptSpan = document.getElementById(`thumbExcerpt_${idx}`);
        const card = document.getElementById(`thumbCard_${idx}`);
        if (card && slide.background) card.style.backgroundColor = slide.background;
        if (excerptSpan && slide.objects) {
            const textObj = slide.objects.find(o => o.type === 'i-text' || o.type === 'text');
            if (textObj && textObj.text) {
                excerptSpan.innerText = textObj.text.replace(/\n/g, ' ').slice(0, 18) + '...';
            }
        }
    }

    function updateFooterIndicators() {
        const indicator = document.getElementById('pageIndicatorText');
        if (indicator) {
            indicator.innerText = `Trang ${currentSlideIndex + 1} / ${slidesDeck.length}`;
        }
    }

    function addNewSlidePage() {
        saveCurrentSlideState();
        const newPage = {
            background: '#ffffff',
            duration: 5.0,
            objects: [
                {
                    type: 'i-text',
                    text: 'Nhấp đúp chuột để nhập tiêu đề slide mới',
                    left: BASE_WIDTH / 2,
                    top: BASE_HEIGHT / 2,
                    fontSize: 32,
                    fontWeight: 'bold',
                    fill: '#1e1b4b',
                    originX: 'center',
                    originY: 'center',
                    fontFamily: 'Montserrat, sans-serif'
                }
            ]
        };
        slidesDeck.push(newPage);
        currentSlideIndex = slidesDeck.length - 1;
        loadSlidePage(currentSlideIndex);
        renderBottomThumbnails();
        pushHistoryState();
        showToast('Đã thêm 1 trang slide mới!', 'success');
    }

    function createNewPresentation() {
        document.getElementById('fileMenuDropdown').classList.add('hidden');
        if (!confirm('Bạn có chắc muốn tạo bài thuyết trình mới? (Nội dung chưa lưu sẽ được làm mới)')) return;
        
        document.getElementById('presentationTitleInput').value = 'Bai_Thuyet_Trinh_Moi';
        slidesDeck = [
            {
                background: '#ffffff',
                duration: 5.0,
                objects: [
                    {
                        type: 'i-text',
                        text: 'TIÊU ĐỀ BÀI THUYẾT TRÌNH MỚI',
                        left: BASE_WIDTH / 2,
                        top: 200,
                        fontSize: 42,
                        fontWeight: 'bold',
                        fill: '#1e1b4b',
                        originX: 'center',
                        fontFamily: 'Montserrat, sans-serif'
                    },
                    {
                        type: 'i-text',
                        text: 'Nhấp đúp để chỉnh sửa phụ đề hoặc chọn mẫu bên trái',
                        left: BASE_WIDTH / 2,
                        top: 280,
                        fontSize: 20,
                        fill: '#64748b',
                        originX: 'center',
                        fontFamily: 'Inter, sans-serif'
                    }
                ]
            }
        ];
        currentSlideIndex = 0;
        loadSlidePage(0);
        renderBottomThumbnails();
        showToast('Đã tạo bài thuyết trình mới!', 'success');
    }

    function duplicateCurrentSlide() {
        const fileMenu = document.getElementById('fileMenuDropdown');
        if (fileMenu) fileMenu.classList.add('hidden');

        saveCurrentSlideState();
        const current = slidesDeck[currentSlideIndex];
        if (!current) return;
        const cloned = JSON.parse(JSON.stringify(current));
        slidesDeck.splice(currentSlideIndex + 1, 0, cloned);
        currentSlideIndex++;
        loadSlidePage(currentSlideIndex);
        renderBottomThumbnails();
        pushHistoryState();
        showToast('Đã nhân bản trang slide!', 'success');
    }

    function deleteCurrentSlide() {
        if (slidesDeck.length <= 1) {
            showToast('Bài thuyết trình phải có ít nhất 1 trang!', 'warning');
            return;
        }
        slidesDeck.splice(currentSlideIndex, 1);
        if (currentSlideIndex >= slidesDeck.length) {
            currentSlideIndex = slidesDeck.length - 1;
        }
        loadSlidePage(currentSlideIndex);
        renderBottomThumbnails();
        pushHistoryState();
        showToast('Đã xóa trang slide!', 'info');
    }

    function toggleBottomFilmstrip() {
        const strip = document.getElementById('filmstripScrollStrip');
        const icon = document.getElementById('filmstripToggleIcon');
        isFilmstripVisible = !isFilmstripVisible;
        if (isFilmstripVisible) {
            strip.classList.remove('hidden');
            if (icon) icon.setAttribute('data-lucide', 'chevron-down');
        } else {
            strip.classList.add('hidden');
            if (icon) icon.setAttribute('data-lucide', 'chevron-up');
        }
        if (window.lucide) lucide.createIcons();
        setTimeout(fitSlideToScreen, 60);
    }

    /**
     * ASPECT RATIO CHANGER (16:9 vs 4:3)
     */
    function changePresentationRatio(ratio) {
        document.getElementById('resizeMenuDropdown').classList.add('hidden');
        currentRatio = ratio;

        const tag16_9 = document.getElementById('ratioTag16_9');
        const tag4_3 = document.getElementById('ratioTag4_3');

        if (ratio === '16:9') {
            BASE_WIDTH = 960;
            BASE_HEIGHT = 540;
            if (tag16_9) tag16_9.classList.remove('hidden');
            if (tag4_3) tag4_3.classList.add('hidden');
            showToast('Đã chuyển sang tỉ lệ 16:9 Màn hình rộng!', 'info');
        } else {
            BASE_WIDTH = 720;
            BASE_HEIGHT = 540;
            if (tag16_9) tag16_9.classList.add('hidden');
            if (tag4_3) tag4_3.classList.remove('hidden');
            showToast('Đã chuyển sang tỉ lệ 4:3 Tiêu chuẩn!', 'info');
        }

        fitSlideToScreen();
    }

    /**
     * TEXT & SHAPES CREATION
     */
    function addTextToCanvas(str, opts = {}) {
        const text = new fabric.IText(str, {
            left: BASE_WIDTH / 2,
            top: BASE_HEIGHT / 2,
            fontSize: opts.fontSize || 32,
            fill: opts.fill || '#1e1b4b',
            fontWeight: opts.fontWeight || 'normal',
            originX: 'center',
            originY: 'center',
            fontFamily: 'Montserrat, sans-serif',
            editable: true,
            selectable: true,
            hasControls: true,
            hasBorders: true,
            cursorDelay: 250
        });
        canvas.add(text);
        canvas.setActiveObject(text);
        canvas.renderAll();
        pushHistoryState();
        onSlideContentChanged();
        showToast('Đã thêm chữ! Nhấp đúp vào chữ trên slide để sửa nội dung', 'info');
    }

    function addShapeToCanvas(type) {
        let shape = null;
        const cx = BASE_WIDTH / 2;
        const cy = BASE_HEIGHT / 2;

        if (type === 'rect') {
            shape = new fabric.Rect({ left: cx - 120, top: cy - 70, width: 240, height: 140, rx: 16, ry: 16, fill: '#8b5cf6', selectable: true });
        } else if (type === 'circle') {
            shape = new fabric.Circle({ left: cx - 60, top: cy - 60, radius: 60, fill: '#0ea5e9', selectable: true });
        } else if (type === 'cloud') {
            shape = new fabric.IText('☁️', { left: cx, top: cy, fontSize: 110, originX: 'center', originY: 'center', editable: true, selectable: true });
        } else if (type === 'star') {
            shape = new fabric.IText('★', { left: cx, top: cy, fontSize: 90, fill: '#f59e0b', originX: 'center', originY: 'center', editable: true, selectable: true });
        } else if (type === 'arrow') {
            shape = new fabric.IText('➔', { left: cx, top: cy, fontSize: 80, fill: '#10b981', originX: 'center', originY: 'center', editable: true, selectable: true });
        } else if (type === 'heart') {
            shape = new fabric.IText('❤️', { left: cx, top: cy, fontSize: 90, originX: 'center', originY: 'center', editable: true, selectable: true });
        }

        if (shape) {
            canvas.add(shape);
            canvas.setActiveObject(shape);
            canvas.renderAll();
            pushHistoryState();
            onSlideContentChanged();
        }
    }

    function addEmojiToCanvas(emoji) {
        const textObj = new fabric.IText(emoji, {
            left: BASE_WIDTH / 2,
            top: BASE_HEIGHT / 2,
            fontSize: 90,
            originX: 'center',
            originY: 'center',
            editable: true,
            selectable: true
        });
        canvas.add(textObj);
        canvas.setActiveObject(textObj);
        canvas.renderAll();
        pushHistoryState();
        onSlideContentChanged();
    }

    function handleImageUpload(files) {
        if (!files || !files.length) return;
        const file = files[0];
        const reader = new FileReader();
        reader.onload = (e) => {
            fabric.Image.fromURL(e.target.result, (img) => {
                const maxDim = 380;
                const scale = Math.min(maxDim / img.width, maxDim / img.height, 1);
                img.set({
                    left: BASE_WIDTH / 2 - (img.width * scale) / 2,
                    top: BASE_HEIGHT / 2 - (img.height * scale) / 2,
                    scaleX: scale,
                    scaleY: scale,
                    selectable: true,
                    hasControls: true,
                    hasBorders: true
                });
                canvas.add(img);
                canvas.setActiveObject(img);
                canvas.renderAll();
                pushHistoryState();
                onSlideContentChanged();
                showToast('Đã chèn ảnh vào slide!', 'success');
            });
        };
        reader.readAsDataURL(file);
    }

    function setCanvasBg(color) {
        canvas.backgroundColor = color;
        canvas.renderAll();
        pushHistoryState();
        onSlideContentChanged();
    }

    function toggleFreeDraw() {
        isDrawingMode = !isDrawingMode;
        const btn = document.getElementById('btnToggleDraw');
        const box = document.getElementById('drawControlsBox');

        if (isDrawingMode) {
            btn.innerText = 'Tắt bút vẽ';
            btn.className = 'text-xs font-bold px-3 py-1.5 rounded-xl bg-rose-600 text-white hover:bg-rose-700 transition cursor-pointer';
            box.classList.remove('hidden');
            canvas.isDrawingMode = true;
            canvas.freeDrawingBrush.color = document.getElementById('drawColorPicker').value;
            canvas.freeDrawingBrush.width = parseInt(document.getElementById('drawSizeSlider').value, 10);
        } else {
            btn.innerText = 'Bật bút vẽ';
            btn.className = 'text-xs font-bold px-3 py-1.5 rounded-xl bg-purple-600 text-white hover:bg-purple-700 transition cursor-pointer';
            box.classList.add('hidden');
            canvas.isDrawingMode = false;
        }
    }

    function updateDrawColor(color) {
        if (canvas && canvas.freeDrawingBrush) canvas.freeDrawingBrush.color = color;
    }

    function updateDrawSize(size) {
        document.getElementById('drawSizeText').innerText = `${size} px`;
        if (canvas && canvas.freeDrawingBrush) canvas.freeDrawingBrush.width = parseInt(size, 10);
    }

    /**
     * IMPORT OLD PPTX FILE (Client-Side)
     */
    async function handlePptxFileUpload(files) {
        if (!files || !files.length) return;
        const file = files[0];
        if (!file.name.endsWith('.pptx')) {
            showToast('Vui lòng chọn file PowerPoint định dạng .pptx!', 'error');
            return;
        }

        showToast('Đang phân tích file PowerPoint cũ...', 'info');

        try {
            const zip = await JSZip.loadAsync(file);
            const slideFiles = [];

            zip.forEach((path) => {
                if (path.match(/^ppt\/slides\/slide[0-9]+\.xml$/)) {
                    slideFiles.push(path);
                }
            });

            if (slideFiles.length === 0) {
                showToast('Không tìm thấy trang slide nào trong file này!', 'warning');
                return;
            }

            slideFiles.sort((a, b) => {
                const numA = parseInt(a.match(/[0-9]+/)[0], 10);
                const numB = parseInt(b.match(/[0-9]+/)[0], 10);
                return numA - numB;
            });

            slidesDeck = [];
            const parser = new DOMParser();

            for (let i = 0; i < slideFiles.length; i++) {
                const xmlStr = await zip.file(slideFiles[i]).async('string');
                const doc = parser.parseFromString(xmlStr, 'application/xml');
                const textNodes = doc.getElementsByTagName('a:t');
                const objects = [];
                let topY = 80;

                for (let t = 0; t < textNodes.length; t++) {
                    const txt = textNodes[t].textContent.trim();
                    if (txt) {
                        const isHeading = (t === 0 && txt.length < 80);
                        objects.push({
                            type: 'i-text',
                            text: txt,
                            left: 80,
                            top: topY,
                            fontSize: isHeading ? 36 : 20,
                            fontWeight: isHeading ? 'bold' : 'normal',
                            fill: isHeading ? '#1e1b4b' : '#334155',
                            fontFamily: 'Montserrat, sans-serif'
                        });
                        topY += (isHeading ? 65 : 35);
                    }
                }

                slidesDeck.push({
                    background: '#ffffff',
                    duration: 5.0,
                    objects: objects
                });
            }

            document.getElementById('presentationTitleInput').value = file.name.replace(/\.pptx$/, '');
            currentSlideIndex = 0;
            loadSlidePage(0);
            renderBottomThumbnails();
            showToast(`Đã bóc tách ${slideFiles.length} slide từ PowerPoint cũ thành công!`, 'success');
        } catch (e) {
            console.error(e);
            showToast('Lỗi khi mở file PowerPoint!', 'error');
        }
    }

    /**
     * EXPORT REAL .PPTX & PDF
     */
    function toggleMenuDropdown(id) {
        const el = document.getElementById(id);
        if (!el) return;
        const isClosed = el.classList.contains('hidden');
        ['fileMenuDropdown', 'resizeMenuDropdown', 'downloadDropdown'].forEach(dId => {
            const d = document.getElementById(dId);
            if (d) d.classList.add('hidden');
        });
        if (isClosed) el.classList.remove('hidden');
    }

    async function exportPresentation(format) {
        document.getElementById('downloadDropdown').classList.add('hidden');
        saveCurrentSlideState();
        const title = document.getElementById('presentationTitleInput').value.trim() || 'Bai_Thuyet_Trinh';

        if (format === 'pptx') {
            showToast('Đang tạo file PowerPoint (.pptx) chuẩn...', 'info');

            try {
                const pptx = new PptxGenJS();
                pptx.layout = (currentRatio === '4:3') ? 'LAYOUT_4x3' : 'LAYOUT_16x9';
                pptx.author = 'ZiiTool Canva Presentation';
                pptx.title = title;

                slidesDeck.forEach(slide => {
                    const pptSlide = pptx.addSlide();
                    const bg = (slide.background && typeof slide.background === 'string' && slide.background.startsWith('#'))
                        ? slide.background.replace('#', '')
                        : 'E0D7F5';
                    pptSlide.background = { color: bg };

                    const objs = slide.objects || [];
                    objs.forEach(obj => {
                        const scaleX = 10 / BASE_WIDTH;
                        const scaleY = 5.625 / BASE_HEIGHT;

                        if (obj.type === 'text' || obj.type === 'i-text') {
                            const hex = (obj.fill && typeof obj.fill === 'string' && obj.fill.startsWith('#'))
                                ? obj.fill.replace('#', '')
                                : '1E1B4B';

                            pptSlide.addText(obj.text || '', {
                                x: (obj.left || 0) * scaleX,
                                y: (obj.top || 0) * scaleY,
                                w: Math.max(3, 400 * scaleX),
                                h: Math.max(0.8, 100 * scaleY),
                                fontSize: Math.round((obj.fontSize || 24) * 0.75),
                                bold: obj.fontWeight === 'bold',
                                italic: obj.fontStyle === 'italic',
                                color: hex,
                                align: obj.textAlign || 'left',
                                fontFace: 'Arial'
                            });
                        }
                    });
                });

                await pptx.writeFile({ fileName: `${title}_ziitool.pptx` });
                showToast('Đã tải xuống file PowerPoint (.pptx) thành công!', 'success');
            } catch (err) {
                console.error(err);
                showToast('Lỗi khi xuất file PPTX!', 'error');
            }
        } else if (format === 'pdf') {
            showToast('Đang tạo file PDF thuyết trình...', 'info');
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF({ orientation: 'landscape', unit: 'px', format: [1920, 1080] });

            for (let i = 0; i < slidesDeck.length; i++) {
                if (i > 0) pdf.addPage([1920, 1080], 'landscape');
                const tempCanvas = document.createElement('canvas');
                tempCanvas.width = 1920;
                tempCanvas.height = 1080;
                const tempFabric = new fabric.StaticCanvas(tempCanvas, { width: 1920, height: 1080 });
                const slide = slidesDeck[i];
                tempFabric.backgroundColor = slide.background || '#e0d7f5';

                const factor = 1920 / BASE_WIDTH;
                const objs = slide.objects || [];

                objs.forEach(objData => {
                    let obj = null;
                    if (objData.type === 'i-text' || objData.type === 'text') {
                        obj = new fabric.Text(objData.text || '', {
                            left: (objData.left || 0) * factor,
                            top: (objData.top || 0) * factor,
                            fontSize: Math.round((objData.fontSize || 28) * factor),
                            fontWeight: objData.fontWeight || 'normal',
                            fontFamily: objData.fontFamily || 'Montserrat, sans-serif',
                            fill: objData.fill || '#1e1b4b',
                            originX: objData.originX || 'left',
                            originY: objData.originY || 'top',
                            lineHeight: objData.lineHeight || 1.3
                        });
                    } else if (objData.type === 'rect') {
                        obj = new fabric.Rect({
                            left: (objData.left || 0) * factor,
                            top: (objData.top || 0) * factor,
                            width: (objData.width || 100) * factor,
                            height: (objData.height || 60) * factor,
                            rx: (objData.rx || 0) * factor,
                            ry: (objData.ry || 0) * factor,
                            fill: objData.fill || '#8b5cf6'
                        });
                    } else if (objData.type === 'circle') {
                        obj = new fabric.Circle({
                            left: (objData.left || 0) * factor,
                            top: (objData.top || 0) * factor,
                            radius: (objData.radius || 40) * factor,
                            fill: objData.fill || '#0ea5e9'
                        });
                    }
                    if (obj) tempFabric.add(obj);
                });

                tempFabric.renderAll();
                pdf.addImage(tempCanvas.toDataURL('image/jpeg', 0.95), 'JPEG', 0, 0, 1920, 1080);
            }

            pdf.save(`${title}_ziitool.pdf`);
            showToast('Đã xuất file PDF thuyết trình thành công!', 'success');
        } else if (format === 'png') {
            const dataUrl = canvas.toDataURL({ format: 'png', multiplier: 2 });
            const link = document.createElement('a');
            link.download = `${title}_trang_${currentSlideIndex + 1}.png`;
            link.href = dataUrl;
            link.click();
            showToast('Đã tải ảnh slide thành công!', 'success');
        }
    }

    /**
     * FULLSCREEN PRESENTATION MODE (F5)
     */
    function startPresentationMode() {
        saveCurrentSlideState();
        isPresenting = true;
        document.getElementById('presentationOverlay').classList.remove('hidden');
        renderPresenterSlide();

        const el = document.getElementById('presentationOverlay');
        if (el.requestFullscreen) {
            el.requestFullscreen().catch(() => {});
        }
    }

    function exitPresentationMode() {
        isPresenting = false;
        document.getElementById('presentationOverlay').classList.add('hidden');
        if (document.exitFullscreen && document.fullscreenElement) {
            document.exitFullscreen().catch(() => {});
        }
    }

    function renderPresenterSlide() {
        const slide = slidesDeck[currentSlideIndex];
        if (!slide) return;

        const maxW = window.innerWidth * 0.95;
        const maxH = window.innerHeight * 0.9;
        let pw = maxW;
        let ph = pw * (9 / 16);
        if (ph > maxH) {
            ph = maxH;
            pw = ph * (16 / 9);
        }

        presenterCanvas.setWidth(pw);
        presenterCanvas.setHeight(ph);
        presenterCanvas.clear();
        presenterCanvas.backgroundColor = slide.background || '#e0d7f5';

        const factor = pw / BASE_WIDTH;
        const objs = slide.objects || [];

        objs.forEach(objData => {
            let obj = null;
            if (objData.type === 'i-text' || objData.type === 'text') {
                obj = new fabric.Text(objData.text || '', {
                    left: (objData.left || 0) * factor,
                    top: (objData.top || 0) * factor,
                    fontSize: Math.round((objData.fontSize || 28) * factor),
                    fontWeight: objData.fontWeight || 'normal',
                    fontFamily: objData.fontFamily || 'Montserrat, sans-serif',
                    fill: objData.fill || '#1e1b4b',
                    originX: objData.originX || 'left',
                    originY: objData.originY || 'top',
                    lineHeight: objData.lineHeight || 1.3
                });
            } else if (objData.type === 'rect') {
                obj = new fabric.Rect({
                    left: (objData.left || 0) * factor,
                    top: (objData.top || 0) * factor,
                    width: (objData.width || 100) * factor,
                    height: (objData.height || 60) * factor,
                    rx: (objData.rx || 0) * factor,
                    ry: (objData.ry || 0) * factor,
                    fill: objData.fill || '#8b5cf6'
                });
            } else if (objData.type === 'circle') {
                obj = new fabric.Circle({
                    left: (objData.left || 0) * factor,
                    top: (objData.top || 0) * factor,
                    radius: (objData.radius || 40) * factor,
                    fill: objData.fill || '#0ea5e9'
                });
            }
            if (obj) presenterCanvas.add(obj);
        });

        presenterCanvas.renderAll();
        document.getElementById('presenterCounter').innerText = `${currentSlideIndex + 1} / ${slidesDeck.length}`;
    }

    function presentNextSlide() {
        if (currentSlideIndex < slidesDeck.length - 1) {
            currentSlideIndex++;
            renderPresenterSlide();
            renderBottomThumbnails();
        }
    }

    function presentPrevSlide() {
        if (currentSlideIndex > 0) {
            currentSlideIndex--;
            renderPresenterSlide();
            renderBottomThumbnails();
        }
    }

    /**
     * CONTEXTUAL PROPERTY TOOLBAR
     */
    function onObjectSelected(e) {
        const active = e.selected ? e.selected[0] : canvas.getActiveObject();
        if (!active) return;

        document.getElementById('defaultControlsTag').classList.add('hidden');
        document.getElementById('universalActions').classList.remove('hidden');

        if (active instanceof fabric.IText || active.type === 'i-text' || active.type === 'text') {
            document.getElementById('textControls').classList.remove('hidden');
            document.getElementById('shapeControls').classList.add('hidden');
            document.getElementById('fontSizeInput').value = Math.round(active.fontSize || 28);
            if (active.fill && typeof active.fill === 'string' && active.fill.startsWith('#')) {
                document.getElementById('textColorPicker').value = active.fill;
            }
            const quickInput = document.getElementById('activeTextQuickEdit');
            if (quickInput) quickInput.value = active.text || '';
        } else {
            document.getElementById('textControls').classList.add('hidden');
            document.getElementById('shapeControls').classList.remove('hidden');
            if (active.fill && typeof active.fill === 'string' && active.fill.startsWith('#')) {
                document.getElementById('shapeFillPicker').value = active.fill;
            }
        }
    }

    function onObjectDeselected() {
        document.getElementById('textControls').classList.add('hidden');
        document.getElementById('shapeControls').classList.add('hidden');
        document.getElementById('universalActions').classList.add('hidden');
        document.getElementById('defaultControlsTag').classList.remove('hidden');
    }

    function onSlideContentChanged() {
        saveCurrentSlideState();
        updateBottomThumbnailsExcerpt(currentSlideIndex);
    }

    function updateActiveTextContent(val) {
        const active = canvas.getActiveObject();
        if (active && (active instanceof fabric.IText || active.type === 'i-text' || active.type === 'text')) {
            active.set('text', val);
            canvas.renderAll();
            onSlideContentChanged();
        }
    }

    function changeFontFamily(font) {
        const active = canvas.getActiveObject();
        if (active && (active instanceof fabric.IText || active.type === 'i-text' || active.type === 'text')) {
            active.set('fontFamily', font);
            canvas.renderAll();
            pushHistoryState();
            onSlideContentChanged();
        }
    }

    function adjustFontSize(delta) {
        const active = canvas.getActiveObject();
        if (active && (active instanceof fabric.IText || active.type === 'i-text' || active.type === 'text')) {
            const newSize = Math.max(10, Math.min(180, (active.fontSize || 28) + delta));
            active.set('fontSize', newSize);
            document.getElementById('fontSizeInput').value = newSize;
            canvas.renderAll();
            pushHistoryState();
            onSlideContentChanged();
        }
    }

    function setFontSize(val) {
        const active = canvas.getActiveObject();
        if (active && (active instanceof fabric.IText || active.type === 'i-text' || active.type === 'text')) {
            active.set('fontSize', parseInt(val, 10) || 28);
            canvas.renderAll();
            pushHistoryState();
            onSlideContentChanged();
        }
    }

    function changeTextColor(color) {
        const active = canvas.getActiveObject();
        if (active && (active instanceof fabric.IText || active.type === 'i-text' || active.type === 'text')) {
            active.set('fill', color);
            canvas.renderAll();
            pushHistoryState();
            onSlideContentChanged();
        }
    }

    function toggleBold() {
        const active = canvas.getActiveObject();
        if (active && (active instanceof fabric.IText || active.type === 'i-text' || active.type === 'text')) {
            active.set('fontWeight', active.fontWeight === 'bold' ? 'normal' : 'bold');
            canvas.renderAll();
            pushHistoryState();
            onSlideContentChanged();
        }
    }

    function toggleItalic() {
        const active = canvas.getActiveObject();
        if (active && (active instanceof fabric.IText || active.type === 'i-text' || active.type === 'text')) {
            active.set('fontStyle', active.fontStyle === 'italic' ? 'normal' : 'italic');
            canvas.renderAll();
            pushHistoryState();
            onSlideContentChanged();
        }
    }

    function setTextAlign(align) {
        const active = canvas.getActiveObject();
        if (active && (active instanceof fabric.IText || active.type === 'i-text' || active.type === 'text')) {
            active.set('textAlign', align);
            canvas.renderAll();
            pushHistoryState();
            onSlideContentChanged();
        }
    }

    function changeShapeFill(color) {
        const active = canvas.getActiveObject();
        if (active) {
            active.set('fill', color);
            canvas.renderAll();
            pushHistoryState();
            onSlideContentChanged();
        }
    }

    function flipActive(axis) {
        const active = canvas.getActiveObject();
        if (active) {
            if (axis === 'x') active.set('flipX', !active.flipX);
            if (axis === 'y') active.set('flipY', !active.flipY);
            canvas.renderAll();
            pushHistoryState();
            onSlideContentChanged();
        }
    }

    function duplicateActive() {
        const active = canvas.getActiveObject();
        if (active) {
            active.clone((cloned) => {
                cloned.set({
                    left: active.left + 25,
                    top: active.top + 25,
                    editable: true,
                    selectable: true,
                    hasControls: true,
                    hasBorders: true
                });
                canvas.add(cloned);
                canvas.setActiveObject(cloned);
                canvas.renderAll();
                pushHistoryState();
                onSlideContentChanged();
                showToast('Đã nhân bản phần tử!', 'info');
            });
        }
    }

    function deleteActive() {
        const active = canvas.getActiveObject();
        if (active) {
            canvas.remove(active);
            canvas.discardActiveObject();
            canvas.renderAll();
            onObjectDeselected();
            pushHistoryState();
            onSlideContentChanged();
        }
    }

    /**
     * DOCK TABS SELECTION
     */
    function selectDockTab(tabKey) {
        const tabs = ['templates', 'text', 'elements', 'uploads', 'background', 'draw'];
        tabs.forEach(k => {
            const btn = document.getElementById(`dockBtn${k.charAt(0).toUpperCase() + k.slice(1)}`);
            const panel = document.getElementById(`drawerTab${k.charAt(0).toUpperCase() + k.slice(1)}`);
            if (k === tabKey) {
                if (btn) btn.className = 'dock-btn w-14 h-14 rounded-2xl flex flex-col items-center justify-center gap-1 text-white bg-[#20252d] transition text-[10px] font-bold cursor-pointer';
                if (panel) panel.classList.remove('hidden');
            } else {
                if (btn) btn.className = 'dock-btn w-14 h-14 rounded-2xl flex flex-col items-center justify-center gap-1 text-[#868c98] hover:text-white hover:bg-[#20252d] transition text-[10px] font-semibold cursor-pointer';
                if (panel) panel.classList.add('hidden');
            }
        });

        const titles = {
            templates: 'Kho Mẫu Bài Thuyết Trình',
            text: 'Thêm Văn Bản & Chữ',
            elements: 'Thành Phần & Đồ Họa',
            uploads: 'Tải Lên Hình Ảnh',
            background: 'Màu Nền Trang Slide',
            draw: 'Bút Vẽ Trực Tiếp'
        };
        const titleEl = document.getElementById('drawerTitle');
        if (titleEl) titleEl.innerText = titles[tabKey] || 'Tùy chọn';
        toggleDrawer(true);
    }

    function toggleDrawer(forceState) {
        const panel = document.getElementById('drawerPanel');
        if (!panel) return;
        if (forceState !== undefined) {
            panel.classList.toggle('hidden', !forceState);
        } else {
            panel.classList.toggle('hidden');
        }
        setTimeout(fitSlideToScreen, 100);
    }

    /**
     * ZOOM & VIEWPORT CONTROL (Native Fabric Zoom)
     */
    function applyZoom(zoom) {
        if (!canvas) return;
        currentZoom = Math.max(0.3, Math.min(2.0, zoom));
        const percent = Math.round(currentZoom * 100);
        document.getElementById('zoomPercentText').innerText = percent + '%';
        document.getElementById('zoomRangeSlider').value = percent;

        const targetW = Math.round(BASE_WIDTH * currentZoom);
        const targetH = Math.round(BASE_HEIGHT * currentZoom);

        canvas.setDimensions({ width: targetW, height: targetH });
        canvas.setZoom(currentZoom);

        const wrapper = document.getElementById('slideCanvasWrapper');
        if (wrapper) {
            wrapper.style.width = targetW + 'px';
            wrapper.style.height = targetH + 'px';
            wrapper.style.transform = 'none';
        }

        canvas.renderAll();
    }

    function adjustZoom(delta) {
        applyZoom(currentZoom + delta);
    }

    function setZoomBySlider(val) {
        applyZoom(parseInt(val, 10) / 100);
    }

    function fitSlideToScreen() {
        const viewport = document.getElementById('slideViewport');
        if (!viewport) return;
        const availW = viewport.clientWidth - 48;
        const availH = viewport.clientHeight - 48;
        if (availW > 200 && availH > 200) {
            const scaleW = availW / BASE_WIDTH;
            const scaleH = availH / BASE_HEIGHT;
            const bestZoom = Math.min(scaleW, scaleH);
            applyZoom(Math.max(0.4, Math.min(1.15, bestZoom)));
        } else {
            applyZoom(0.85);
        }
    }

    /**
     * UNDO / REDO HISTORY STACK (REAL WORKING IMPLEMENTATION)
     */
    function pushHistoryState() {
        if (!isRecordingHistory || !canvas) return;
        saveCurrentSlideState();
        const stateJSON = JSON.stringify(slidesDeck[currentSlideIndex]);

        // Truncate future states if we were in the middle of history
        if (historyIndex < historyStack.length - 1) {
            historyStack = historyStack.slice(0, historyIndex + 1);
        }

        historyStack.push(stateJSON);
        if (historyStack.length > 25) historyStack.shift();
        historyIndex = historyStack.length - 1;
    }

    function undoSlideAction() {
        if (historyIndex > 0) {
            historyIndex--;
            const prevState = JSON.parse(historyStack[historyIndex]);
            slidesDeck[currentSlideIndex] = prevState;
            loadSlidePage(currentSlideIndex);
            updateBottomThumbnailsExcerpt(currentSlideIndex);
            showToast('Đã hoàn tác thao tác!', 'info');
        } else {
            showToast('Không còn bước nào để hoàn tác!', 'warning');
        }
    }

    function redoSlideAction() {
        if (historyIndex < historyStack.length - 1) {
            historyIndex++;
            const nextState = JSON.parse(historyStack[historyIndex]);
            slidesDeck[currentSlideIndex] = nextState;
            loadSlidePage(currentSlideIndex);
            updateBottomThumbnailsExcerpt(currentSlideIndex);
            showToast('Đã làm lại thao tác!', 'info');
        } else {
            showToast('Không còn bước nào để làm lại!', 'warning');
        }
    }

    /**
     * SPEAKER NOTES & TIMER MODALS
     */
    function toggleSpeakerNotesModal() {
        const modal = document.getElementById('speakerNotesModal');
        if (!modal) return;
        const isHidden = modal.classList.contains('hidden');
        modal.classList.toggle('hidden', !isHidden);
        if (isHidden) {
            const current = slidesDeck[currentSlideIndex];
            document.getElementById('speakerNotesTextarea').value = (current && current.notes) ? current.notes : '';
        }
    }

    function saveCurrentSlideNotes(val) {
        if (slidesDeck[currentSlideIndex]) {
            slidesDeck[currentSlideIndex].notes = val;
        }
    }

    function toggleCountdownTimerModal() {
        const modal = document.getElementById('countdownTimerModal');
        if (!modal) return;
        modal.classList.toggle('hidden');
    }

    function setTimerMinutes(mins) {
        pauseTimer();
        timerTotalSeconds = mins * 60;
        timerRemainingSeconds = timerTotalSeconds;
        updateTimerDisplay();
    }

    function toggleTimerRunning() {
        if (isTimerRunning) {
            pauseTimer();
        } else {
            startTimer();
        }
    }

    function startTimer() {
        if (isTimerRunning) return;
        isTimerRunning = true;
        const btn = document.getElementById('btnToggleTimer');
        if (btn) {
            btn.innerText = 'Tạm dừng';
            btn.className = 'flex-1 py-1.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold transition';
        }
        timerInterval = setInterval(() => {
            if (timerRemainingSeconds > 0) {
                timerRemainingSeconds--;
                updateTimerDisplay();
            } else {
                pauseTimer();
                showToast('Hết thời gian thuyết trình!', 'warning');
            }
        }, 1000);
    }

    function pauseTimer() {
        isTimerRunning = false;
        clearInterval(timerInterval);
        const btn = document.getElementById('btnToggleTimer');
        if (btn) {
            btn.innerText = 'Bắt đầu';
            btn.className = 'flex-1 py-1.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold transition';
        }
    }

    function resetTimer() {
        pauseTimer();
        timerRemainingSeconds = timerTotalSeconds;
        updateTimerDisplay();
    }

    function updateTimerDisplay() {
        const m = Math.floor(timerRemainingSeconds / 60);
        const s = timerRemainingSeconds % 60;
        const str = `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
        const display = document.getElementById('countdownDisplay');
        const label = document.getElementById('countdownTimerLabel');
        if (display) display.innerText = str;
        if (label) label.innerText = isTimerRunning ? str : 'Đếm ngược';
    }

    function handleGlobalKeydown(e) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;

        if (e.key === 'F5') {
            e.preventDefault();
            startPresentationMode();
        } else if (isPresenting) {
            if (e.key === 'ArrowRight' || e.key === ' ' || e.key === 'PageDown') {
                e.preventDefault();
                presentNextSlide();
            } else if (e.key === 'ArrowLeft' || e.key === 'PageUp') {
                e.preventDefault();
                presentPrevSlide();
            } else if (e.key === 'Escape') {
                exitPresentationMode();
            }
        } else {
            if ((e.ctrlKey || e.metaKey) && e.key === 'z') {
                e.preventDefault();
                undoSlideAction();
            } else if ((e.ctrlKey || e.metaKey) && e.key === 'y') {
                e.preventDefault();
                redoSlideAction();
            } else if (e.key === 'Delete' || e.key === 'Backspace') {
                deleteActive();
            } else if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
                e.preventDefault();
                duplicateActive();
            }
        }
    }
</script>
@endpush
@endsection
