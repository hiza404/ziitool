@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Trang chủ</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>{{ $categoryInfo['name'] ?? 'Xử lý Ảnh & Tệp' }}</span>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="text-slate-800 dark:text-slate-200 font-semibold">{{ $tool['title'] }}</span>
    </nav>

    <!-- Hero Header Multi-Platform Style -->
    <div class="text-center max-w-3xl mx-auto mb-10">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-50 dark:bg-cyan-950/60 border border-cyan-200 dark:border-cyan-800/50 text-cyan-700 dark:text-cyan-300 text-xs font-semibold mb-4">
            <span class="w-2 h-2 rounded-full bg-cyan-500 animate-ping"></span>
            <span>SnapTik & All-In-One Downloader • TikTok • YouTube • Facebook</span>
        </div>
        <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-3">
            Tải Video Đa Nền Tảng (TikTok, YouTube, Facebook)
        </h1>
        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 max-w-2xl mx-auto leading-relaxed">
            Bóc tách video TikTok không logo (Watermark), tải video YouTube Full HD / 4K / MP3 và video Facebook / Reels công khai siêu tốc, miễn phí 100%.
        </p>

        <!-- Platform Badges -->
        <div class="flex items-center justify-center gap-2 mt-4">
            <span class="px-3 py-1 rounded-full bg-slate-900 text-white dark:bg-slate-800 text-[11px] font-semibold flex items-center gap-1.5 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-cyan-400"></span> TikTok (Không Logo)
            </span>
            <span class="px-3 py-1 rounded-full bg-red-600 text-white text-[11px] font-semibold flex items-center gap-1.5 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-white"></span> YouTube (HD & MP3)
            </span>
            <span class="px-3 py-1 rounded-full bg-blue-600 text-white text-[11px] font-semibold flex items-center gap-1.5 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-blue-200"></span> Facebook (Reels & Video)
            </span>
        </div>
    </div>

    <!-- Top Leaderboard Ad Banner -->
    <x-ad-banner slot="top_leaderboard" class="mb-6" />

    <!-- Main Input Box Area -->
    <div class="max-w-3xl mx-auto mb-10">
        <div class="p-3 sm:p-4 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xl shadow-cyan-500/5">
            <form id="tiktokDownloadForm" onsubmit="handleTiktokSubmit(event)" class="space-y-3">
                <div class="relative flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                    <div class="relative flex-1 flex items-center">
                        <i data-lucide="link" class="w-5 h-5 text-slate-400 absolute left-4 pointer-events-none"></i>
                        <input 
                            type="text" 
                            id="tiktokUrlInput" 
                            placeholder="Dán liên kết TikTok, YouTube hoặc Facebook vào đây..." 
                            required
                            autocomplete="off"
                            class="w-full pl-12 pr-24 py-3.5 sm:py-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:bg-white dark:focus:bg-slate-900 transition"
                        >
                        <!-- Paste & Clear Buttons inside input -->
                        <div class="absolute right-2 flex items-center gap-1">
                            <button 
                                type="button" 
                                onclick="clearInput()" 
                                id="btnClearInput" 
                                class="hidden p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition"
                                title="Xóa đường dẫn"
                            >
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                            <button 
                                type="button" 
                                onclick="pasteClipboard()" 
                                class="px-2.5 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:text-cyan-600 dark:hover:text-cyan-400 hover:border-cyan-400 text-xs font-semibold flex items-center gap-1 shadow-sm transition"
                                title="Dán từ bộ nhớ tạm"
                            >
                                <i data-lucide="clipboard-paste" class="w-3.5 h-3.5"></i>
                                <span class="hidden sm:inline">Dán</span>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        id="btnSubmit" 
                        class="px-6 py-3.5 sm:py-4 rounded-2xl bg-gradient-to-r from-cyan-500 via-blue-600 to-indigo-600 hover:from-cyan-600 hover:to-indigo-700 text-white font-bold text-xs sm:text-sm flex items-center justify-center gap-2 shadow-lg shadow-cyan-500/25 hover:shadow-cyan-500/35 active:scale-95 transition cursor-pointer shrink-0"
                    >
                        <i data-lucide="download" class="w-4 h-4"></i>
                        <span>Tải Xuống</span>
                    </button>
                </div>
            </form>

            <!-- Quick Example Pills -->
            <div class="pt-3 border-t border-slate-100 dark:border-slate-800/80 flex flex-wrap items-center gap-2 text-xs">
                <span class="text-slate-400 text-[11px] font-medium">Thử nhanh link mẫu:</span>
                <button type="button" onclick="useSampleUrl('https://www.tiktok.com/@scout2015/video/6718335390845095173')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-cyan-500 text-[11px] transition flex items-center gap-1">
                    <span>🎵</span> TikTok Không Logo
                </button>
                <button type="button" onclick="useSampleUrl('https://www.youtube.com/watch?v=dQw4w9WgXcQ')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-red-500 text-[11px] transition flex items-center gap-1">
                    <span>▶️</span> YouTube HD Video
                </button>
                <button type="button" onclick="useSampleUrl('https://www.facebook.com/watch/?v=10153231379946729')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-blue-500 text-[11px] transition flex items-center gap-1">
                    <span>📘</span> Facebook Video
                </button>
            </div>
        </div>
    </div>

    <!-- Loading Skeleton Card -->
    <div id="loadingCard" class="hidden max-w-3xl mx-auto mb-10 p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-center animate-pulse">
        <div class="w-12 h-12 rounded-2xl bg-cyan-100 dark:bg-cyan-950/60 text-cyan-600 dark:text-cyan-400 flex items-center justify-center mx-auto mb-3 animate-spin">
            <i data-lucide="loader-2" class="w-6 h-6"></i>
        </div>
        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 mb-1" id="loadingStatusText">Đang bóc tách video từ máy chủ...</h4>
        <p class="text-xs text-slate-400">Vui lòng chờ trong giây lát (thường mất 1 - 3 giây)</p>
    </div>

    <!-- Error Alert Card -->
    <div id="errorCard" class="hidden max-w-3xl mx-auto mb-10 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs sm:text-sm flex items-start gap-3">
        <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-0.5 text-rose-500"></i>
        <div class="flex-1">
            <p id="errorMessage" class="font-semibold">Không tìm thấy video. Vui lòng kiểm tra lại liên kết video công khai.</p>
        </div>
    </div>

    <!-- Video Result Card (SnapTik Layout) -->
    <div id="resultCard" class="hidden max-w-3xl mx-auto mb-12 p-5 sm:p-7 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xl shadow-cyan-500/5">
        
        <div class="flex flex-col md:flex-row gap-6 items-start">
            
            <!-- Left: Video Preview & Thumbnail Player -->
            <div class="w-full md:w-56 shrink-0 space-y-3">
                <div class="relative rounded-2xl overflow-hidden bg-black aspect-[9/16] shadow-md border border-slate-200 dark:border-slate-800 flex items-center justify-center">
                    <video 
                        id="videoPlayer" 
                        controls 
                        playsinline 
                        preload="metadata" 
                        class="w-full h-full object-contain"
                    ></video>
                    <iframe 
                        id="youtubePlayer" 
                        src="" 
                        class="hidden w-full h-full border-0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen
                    ></iframe>
                    <img id="videoCoverImg" src="" alt="Thumbnail" class="hidden absolute inset-0 w-full h-full object-cover">
                </div>
                
                <div class="text-center text-[11px] text-slate-400">
                    <span id="videoDurationText">--:--</span> • <span id="videoQualityText">Full HD 1080p</span>
                </div>
            </div>

            <!-- Right: Details & Download Action Buttons -->
            <div class="flex-1 min-w-0 space-y-4 w-full">
                
                <!-- Author Header -->
                <div class="flex items-center gap-3 pb-3 border-b border-slate-100 dark:border-slate-800">
                    <img id="authorAvatar" src="" alt="Avatar" class="w-11 h-11 rounded-full border border-slate-200 dark:border-slate-700 object-cover bg-slate-100 dark:bg-slate-800">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <h3 id="authorNickname" class="text-sm font-bold text-slate-900 dark:text-white truncate">Tác giả</h3>
                            <span id="platformBadge" class="px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-100 dark:bg-cyan-900/60 text-cyan-700 dark:text-cyan-300">TikTok</span>
                        </div>
                        <p id="authorUniqueId" class="text-xs text-slate-400 truncate">@username</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold shrink-0 flex items-center gap-1">
                        <i data-lucide="check-circle" class="w-3 h-3"></i> Tải Miễn Phí
                    </span>
                </div>

                <!-- Video Title / Caption -->
                <div>
                    <p id="videoTitle" class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 font-medium line-clamp-3 leading-relaxed">
                        Tiêu đề video
                    </p>
                </div>

                <!-- Video Statistics -->
                <div class="flex flex-wrap items-center gap-2.5 text-xs text-slate-500 dark:text-slate-400">
                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800/80 flex items-center gap-1.5 font-medium">
                        <i data-lucide="heart" class="w-3.5 h-3.5 text-rose-500"></i>
                        <span id="diggCount">0</span>
                    </span>
                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800/80 flex items-center gap-1.5 font-medium">
                        <i data-lucide="message-circle" class="w-3.5 h-3.5 text-blue-500"></i>
                        <span id="commentCount">0</span>
                    </span>
                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800/80 flex items-center gap-1.5 font-medium">
                        <i data-lucide="share-2" class="w-3.5 h-3.5 text-emerald-500"></i>
                        <span id="shareCount">0</span>
                    </span>
                </div>

                <!-- Action Download Buttons Grid -->
                <div class="pt-2 space-y-2.5">
                    
                    <!-- 1. Tải Video Không Logo (HD) -->
                    <a 
                        id="btnDownloadHD" 
                        href="#" 
                        class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm flex items-center justify-between shadow-md shadow-emerald-500/20 active:scale-[0.99] transition cursor-pointer"
                    >
                        <div class="flex items-center gap-2">
                            <i data-lucide="video" class="w-4 h-4"></i>
                            <span id="btnDownloadHDLabel">Tải Video Không Logo (HD)</span>
                        </div>
                        <span class="text-[11px] px-2 py-0.5 rounded bg-emerald-700/60 font-semibold" id="hdSizeBadge">Bản HD</span>
                    </a>

                    <!-- 2. Tải Video Bản Chuẩn (SD) -->
                    <a 
                        id="btnDownloadSD" 
                        href="#" 
                        class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs sm:text-sm flex items-center justify-between shadow-md shadow-blue-500/15 active:scale-[0.99] transition cursor-pointer"
                    >
                        <div class="flex items-center gap-2">
                            <i data-lucide="download" class="w-4 h-4"></i>
                            <span id="btnDownloadSDLabel">Tải Video Tiêu Chuẩn (SD)</span>
                        </div>
                        <span class="text-[11px] px-2 py-0.5 rounded bg-blue-700/60 font-semibold" id="sdSizeBadge">Bản SD</span>
                    </a>

                    <!-- 3. Tải Nhạc MP3 -->
                    <a 
                        id="btnDownloadMP3" 
                        href="#" 
                        class="w-full py-2.5 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-200 font-semibold text-xs flex items-center justify-between border border-slate-700 transition cursor-pointer"
                    >
                        <div class="flex items-center gap-2">
                            <i data-lucide="music" class="w-4 h-4 text-purple-400"></i>
                            <span>Tải Âm Thanh Gốc (MP3)</span>
                        </div>
                        <span class="text-[11px] text-purple-300">Âm thanh</span>
                    </a>

                    <!-- 4. Tải Ảnh Bìa & Link Gốc -->
                    <div class="flex gap-2">
                        <a 
                            id="btnDownloadCover" 
                            href="#" 
                            class="flex-1 py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800/60 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-xs flex items-center justify-center gap-1.5 transition cursor-pointer"
                        >
                            <i data-lucide="image" class="w-3.5 h-3.5 text-amber-500"></i>
                            <span>Tải Ảnh Bìa (Cover)</span>
                        </a>

                        <a 
                            id="btnDirectLink" 
                            href="#" 
                            target="_blank" 
                            rel="noopener noreferrer" 
                            class="py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800/60 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-xs flex items-center justify-center gap-1.5 transition cursor-pointer"
                            title="Mở liên kết trực tiếp trong tab mới"
                        >
                            <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                            <span>Link Gốc</span>
                        </a>

                        <button 
                            type="button" 
                            onclick="copyVideoLink()" 
                            class="py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800/60 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-xs flex items-center justify-center gap-1.5 transition"
                            title="Sao chép tiêu đề"
                        >
                            <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                            <span>Copy Caption</span>
                        </button>
                    </div>

                    <!-- Reset to search another -->
                    <button 
                        type="button" 
                        onclick="resetToSearch()" 
                        class="w-full py-2 text-center text-xs text-slate-400 hover:text-cyan-500 transition font-medium flex items-center justify-center gap-1"
                    >
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        <span>Tải video khác</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Extra: Photo Slideshow Gallery (If TikTok is Photo Post) -->
        <div id="photoSlideshowSection" class="hidden mt-8 pt-6 border-t border-slate-200 dark:border-slate-800">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="images" class="w-4 h-4 text-cyan-500"></i>
                    <span>Tải Toàn Bộ Ảnh Trong Bài Đăng Slideshow (<span id="photoCount">0</span> ảnh)</span>
                </h4>
            </div>
            <div id="photoGrid" class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <!-- Dynamically rendered photos -->
            </div>
        </div>

    </div>

    <!-- In-page Mid Ad Banner -->
    <x-ad-banner slot="in_tool" class="mb-12" />

    <!-- 3 Steps Visual Guide (SnapTik Style) -->
    <div class="mb-16">
        <div class="text-center max-w-xl mx-auto mb-8">
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white mb-2">
                Cách Tải Video TikTok, YouTube & Facebook Đơn Giản Nhất
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                Chỉ 3 bước đơn giản, không cần cài đặt phần mềm hay đăng nhập tài khoản.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-center">
                <div class="w-12 h-12 rounded-2xl bg-cyan-100 dark:bg-cyan-950/60 text-cyan-600 dark:text-cyan-400 flex items-center justify-center mx-auto mb-4 font-extrabold text-lg">
                    1
                </div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-2">Sao Chép Liên Kết</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                    Mở ứng dụng hoặc trang web TikTok, YouTube hoặc Facebook. Chọn video cần tải, nhấn nút <strong>Chia sẻ</strong> và chọn <strong>Sao chép liên kết</strong>.
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-center">
                <div class="w-12 h-12 rounded-2xl bg-indigo-100 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto mb-4 font-extrabold text-lg">
                    2
                </div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-2">Dán Vào ZiiTool</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                    Dán liên kết vừa sao chép vào ô tìm kiếm ở trên và nhấn nút <strong>Tải Xuống</strong>. Hệ thống tự nhận diện TikTok, YouTube hay Facebook.
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-center">
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-4 font-extrabold text-lg">
                    3
                </div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-2">Lưu Video Về Máy</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                    Chọn tải bản <strong>Video HD (Không logo)</strong>, bản chuẩn SD, hoặc tách riêng file âm thanh <strong>MP3</strong> về thiết bị của bạn.
                </p>
            </div>
        </div>
    </div>

    <!-- Feature Highlights -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-16">
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
            <div class="w-10 h-10 rounded-xl bg-cyan-100 dark:bg-cyan-950 text-cyan-600 dark:text-cyan-400 flex items-center justify-center mb-3">
                <i data-lucide="shield-check" class="w-5 h-5"></i>
            </div>
            <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Sạch 100% Watermark</h4>
            <p class="text-xs text-slate-500 dark:text-slate-400">Loại bỏ hoàn toàn logo TikTok chuyển động và ID người dùng ở góc video.</p>
        </div>

        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-950 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-3">
                <i data-lucide="sparkles" class="w-5 h-5"></i>
            </div>
            <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Chất Lượng Gốc HD / 4K</h4>
            <p class="text-xs text-slate-500 dark:text-slate-400">Giữ nguyên độ sắc nét cao nhất của video gốc như trên máy chủ YouTube, TikTok.</p>
        </div>

        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
            <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-950 text-purple-600 dark:text-purple-400 flex items-center justify-center mb-3">
                <i data-lucide="music" class="w-5 h-5"></i>
            </div>
            <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Tách Âm Thanh MP3</h4>
            <p class="text-xs text-slate-500 dark:text-slate-400">Dễ dàng trích xuất riêng bài hát hoặc nhạc nền làm nhạc chuông điện thoại.</p>
        </div>

        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-3">
                <i data-lucide="infinity" class="w-5 h-5"></i>
            </div>
            <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Tải Không Giới Hạn</h4>
            <p class="text-xs text-slate-500 dark:text-slate-400">Miễn phí trọn đời, không cần đăng ký tài khoản và không giới hạn số lượt tải.</p>
        </div>
    </div>

    <!-- FAQ Accordion -->
    <div class="max-w-3xl mx-auto mb-12">
        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white text-center mb-8">
            Câu Hỏi Thường Gặp (FAQ)
        </h2>
        <div class="space-y-4">
            @foreach($tool['faq'] ?? [] as $faq)
                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                        <span class="text-cyan-500">Q:</span> {{ $faq['q'] }}
                    </h3>
                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed pl-5">
                        {{ $faq['a'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>

</div>

@push('scripts')
<script>
    let currentVideoData = null;

    // Show/hide clear button on input
    const urlInput = document.getElementById('tiktokUrlInput');
    urlInput.addEventListener('input', () => {
        document.getElementById('btnClearInput').classList.toggle('hidden', !urlInput.value.trim());
    });

    function clearInput() {
        urlInput.value = '';
        document.getElementById('btnClearInput').classList.add('hidden');
        urlInput.focus();
    }

    async function pasteClipboard() {
        try {
            const text = await navigator.clipboard.readText();
            if (text) {
                urlInput.value = text.trim();
                document.getElementById('btnClearInput').classList.remove('hidden');
                showToast('Đã dán liên kết từ bộ nhớ tạm!', 'info');
                // Automatically submit for great UX
                handleTiktokSubmit(new Event('submit'));
            }
        } catch (e) {
            const pasted = prompt('Vui lòng dán liên kết TikTok, YouTube hoặc Facebook vào đây:');
            if (pasted) {
                urlInput.value = pasted.trim();
                document.getElementById('btnClearInput').classList.remove('hidden');
                handleTiktokSubmit(new Event('submit'));
            }
        }
    }

    function useSampleUrl(url) {
        urlInput.value = url;
        document.getElementById('btnClearInput').classList.remove('hidden');
        handleTiktokSubmit(new Event('submit'));
    }

    function formatNumber(num) {
        if (!num) return '0';
        if (num >= 1000000) return (num / 1000000).toFixed(1) + 'M';
        if (num >= 1000) return (num / 1000).toFixed(1) + 'K';
        return num.toString();
    }

    function formatDuration(sec) {
        if (!sec) return '00:00';
        const m = Math.floor(sec / 60);
        const s = sec % 60;
        return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
    }

    function formatBytes(bytes) {
        if (!bytes || bytes === 0) return '';
        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }

    async function handleTiktokSubmit(e) {
        if (e && e.preventDefault) e.preventDefault();
        
        const rawInput = urlInput.value.trim();
        if (!rawInput) {
            showToast('Vui lòng nhập hoặc dán liên kết video TikTok, YouTube hoặc Facebook!', 'warning');
            urlInput.focus();
            return;
        }

        // Extract http/https link if user copied text containing link from mobile share
        let cleanUrl = rawInput;
        const urlMatch = rawInput.match(/https?:\/\/[^\s]+/i);
        if (urlMatch) {
            cleanUrl = urlMatch[0].replace(/[.,;!?)>"'\s]+$/, '');
        }

        // Dynamic status text
        if (cleanUrl.includes('youtube.com') || cleanUrl.includes('youtu.be')) {
            document.getElementById('loadingStatusText').innerText = 'Đang phân tích video YouTube & chuẩn bị luồng tải Full HD...';
        } else if (cleanUrl.includes('facebook.com') || cleanUrl.includes('fb.watch')) {
            document.getElementById('loadingStatusText').innerText = 'Đang bóc tách video Facebook Reels / Watch chất lượng cao...';
        } else {
            document.getElementById('loadingStatusText').innerText = 'Đang bóc tách video TikTok không logo từ máy chủ...';
        }

        // Hide previous cards
        document.getElementById('errorCard').classList.add('hidden');
        document.getElementById('resultCard').classList.add('hidden');
        document.getElementById('loadingCard').classList.remove('hidden');

        const btnSubmit = document.getElementById('btnSubmit');
        btnSubmit.disabled = true;
        btnSubmit.classList.add('opacity-70', 'cursor-not-allowed');

        try {
            const response = await fetch("{{ route('tool.video.parse') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ url: cleanUrl })
            });

            const data = await response.json();

            document.getElementById('loadingCard').classList.add('hidden');
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('opacity-70', 'cursor-not-allowed');

            if (!response.ok || !data.success) {
                document.getElementById('errorMessage').innerText = data.message || 'Không thể bóc tách video. Vui lòng kiểm tra lại liên kết video công khai.';
                document.getElementById('errorCard').classList.remove('hidden');
                showToast(data.message || 'Lỗi bóc tách video', 'error');
                return;
            }

            // Display results
            currentVideoData = data.data;
            renderVideoResult(data.data);
            showToast('Bóc tách video thành công!', 'success');

        } catch (err) {
            console.error(err);
            document.getElementById('loadingCard').classList.add('hidden');
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('opacity-70', 'cursor-not-allowed');

            document.getElementById('errorMessage').innerText = 'Lỗi kết nối mạng máy chủ. Vui lòng thử lại sau vài giây.';
            document.getElementById('errorCard').classList.remove('hidden');
            showToast('Lỗi kết nối máy chủ', 'error');
        }
    }

    function buildDownloadUrl(mediaUrl, ext, type) {
        if (!mediaUrl && !currentVideoData) return '#';
        const params = new URLSearchParams({
            url: mediaUrl || '',
            title: currentVideoData?.title || 'video',
            platform: currentVideoData?.platform || 'video',
            type: type || 'video',
            ext: ext || 'mp4',
            video_id: currentVideoData?.id || '',
        });
        return `{{ route('tool.video.download') }}?${params.toString()}`;
    }

    function renderVideoResult(item) {
        const platform = (item.platform || 'tiktok').toLowerCase();

        // Platform Badge
        const platformBadge = document.getElementById('platformBadge');
        if (platform === 'youtube') {
            platformBadge.className = 'px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 dark:bg-red-900/60 text-red-700 dark:text-red-300';
            platformBadge.innerText = 'YouTube';
        } else if (platform === 'facebook') {
            platformBadge.className = 'px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300';
            platformBadge.innerText = 'Facebook';
        } else {
            platformBadge.className = 'px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-100 dark:bg-cyan-900/60 text-cyan-700 dark:text-cyan-300';
            platformBadge.innerText = 'TikTok';
        }

        // Author details
        document.getElementById('authorAvatar').src = item.author?.avatar || 'https://via.placeholder.com/100';
        document.getElementById('authorNickname').innerText = item.author?.nickname || (platform.toUpperCase() + ' Creator');
        document.getElementById('authorUniqueId').innerText = '@' + (item.author?.unique_id || 'user');

        // Video text
        document.getElementById('videoTitle').innerText = item.title || 'Video không có tiêu đề';
        
        // Stats
        document.getElementById('diggCount').innerText = formatNumber(item.digg_count);
        document.getElementById('commentCount').innerText = formatNumber(item.comment_count);
        document.getElementById('shareCount').innerText = formatNumber(item.share_count);
        document.getElementById('videoDurationText').innerText = formatDuration(item.duration);

        // Video Preview Player
        const player = document.getElementById('videoPlayer');
        const ytPlayer = document.getElementById('youtubePlayer');
        const coverImg = document.getElementById('videoCoverImg');
        const playUrl = item.hdplay || item.play || '';

        if (platform === 'youtube' && item.embed_url) {
            player.classList.add('hidden');
            player.pause();
            player.src = '';
            coverImg.classList.add('hidden');
            ytPlayer.classList.remove('hidden');
            ytPlayer.src = item.embed_url;
        } else if (playUrl) {
            ytPlayer.classList.add('hidden');
            ytPlayer.src = '';
            player.classList.remove('hidden');
            coverImg.classList.add('hidden');
            player.src = playUrl;
            player.poster = item.cover || '';
        } else {
            ytPlayer.classList.add('hidden');
            ytPlayer.src = '';
            player.classList.add('hidden');
            coverImg.classList.remove('hidden');
            coverImg.src = item.cover || '';
        }

        // Action Buttons
        const btnHD = document.getElementById('btnDownloadHD');
        const btnHDLabel = document.getElementById('btnDownloadHDLabel');
        const btnSD = document.getElementById('btnDownloadSD');
        const btnSDLabel = document.getElementById('btnDownloadSDLabel');
        const btnMP3 = document.getElementById('btnDownloadMP3');
        const btnCover = document.getElementById('btnDownloadCover');
        const btnDirect = document.getElementById('btnDirectLink');

        // Dynamic labels based on platform
        if (platform === 'tiktok') {
            btnHDLabel.innerText = 'Tải Video Không Logo (HD)';
            btnSDLabel.innerText = 'Tải Video Tiêu Chuẩn (SD)';
        } else if (platform === 'youtube') {
            btnHDLabel.innerText = 'Tải Video YouTube (HD / MP4)';
            btnSDLabel.innerText = 'Tải Video Dự Phòng (SD)';
        } else {
            btnHDLabel.innerText = 'Tải Video Facebook (HD)';
            btnSDLabel.innerText = 'Tải Video Tiêu Chuẩn (SD)';
        }

        // Clean download links routed through server streaming attachment
        btnHD.href = buildDownloadUrl(item.hdplay || item.play, 'mp4', 'video_hd');
        btnHD.onclick = () => showToast('Đang bắt đầu tải video HD về máy...', 'info');
        if (item.hd_size) {
            document.getElementById('hdSizeBadge').innerText = formatBytes(item.hd_size);
        } else {
            document.getElementById('hdSizeBadge').innerText = 'Bản HD';
        }

        btnSD.href = buildDownloadUrl(item.play || item.wmplay, 'mp4', 'video_sd');
        btnSD.onclick = () => showToast('Đang bắt đầu tải video SD về máy...', 'info');
        if (item.size) {
            document.getElementById('sdSizeBadge').innerText = formatBytes(item.size);
        } else {
            document.getElementById('sdSizeBadge').innerText = 'Bản SD';
        }

        if (item.music) {
            btnMP3.classList.remove('hidden');
            btnMP3.href = buildDownloadUrl(item.music, 'mp3', 'audio');
            btnMP3.onclick = () => showToast('Đang bắt đầu tải âm thanh MP3 về máy...', 'info');
        } else {
            btnMP3.classList.add('hidden');
        }

        btnCover.href = buildDownloadUrl(item.cover || item.origin_cover, 'jpg', 'cover');
        btnCover.onclick = () => showToast('Đang bắt đầu tải ảnh bìa về máy...', 'info');

        // Direct raw stream fallback button
        if (btnDirect) {
            btnDirect.href = item.hdplay || item.play || '#';
        }

        // Check if there are photo slideshow images
        const photoSection = document.getElementById('photoSlideshowSection');
        const photoGrid = document.getElementById('photoGrid');
        photoGrid.innerHTML = '';

        if (item.images && Array.isArray(item.images) && item.images.length > 0) {
            photoSection.classList.remove('hidden');
            document.getElementById('photoCount').innerText = item.images.length;

            item.images.forEach((imgUrl, idx) => {
                const card = document.createElement('div');
                card.className = 'rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex flex-col justify-between';
                const photoDlUrl = buildDownloadUrl(imgUrl, 'jpg', 'photo');
                card.innerHTML = `
                    <div class="aspect-square overflow-hidden bg-black/10">
                        <img src="${imgUrl}" alt="Photo ${idx+1}" class="w-full h-full object-cover hover:scale-105 transition duration-300">
                    </div>
                    <div class="p-2">
                        <a href="${photoDlUrl}" class="w-full py-1.5 px-2 rounded-lg bg-cyan-600 hover:bg-cyan-700 text-white font-semibold text-[11px] flex items-center justify-center gap-1 transition">
                            <i data-lucide="download" class="w-3 h-3"></i> Tải ảnh ${idx+1}
                        </a>
                    </div>
                `;
                photoGrid.appendChild(card);
            });
            lucide.createIcons();
        } else {
            photoSection.classList.add('hidden');
        }

        // Show result card and scroll into view smoothly
        document.getElementById('resultCard').classList.remove('hidden');
        document.getElementById('resultCard').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function resetToSearch() {
        document.getElementById('resultCard').classList.add('hidden');
        const player = document.getElementById('videoPlayer');
        if (player) {
            player.pause();
            player.src = '';
        }
        const ytPlayer = document.getElementById('youtubePlayer');
        if (ytPlayer) {
            ytPlayer.src = '';
        }
        urlInput.value = '';
        document.getElementById('btnClearInput').classList.add('hidden');
        urlInput.focus();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function copyVideoLink() {
        if (!currentVideoData) return;
        const text = currentVideoData.title || '';
        navigator.clipboard.writeText(text).then(() => {
            showToast('Đã sao chép tiêu đề video!', 'success');
        }).catch(() => {
            showToast('Không thể sao chép tự động!', 'error');
        });
    }
</script>
@endpush
@endsection

