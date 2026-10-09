@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-indigo-600">Trang chủ</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>{{ $categoryInfo['name'] ?? 'Giáo dục & Ôn thi AI' }}</span>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="text-slate-800 dark:text-slate-200 font-semibold">{{ $tool['title'] }}</span>
    </nav>

    <!-- Tool Header -->
    <div class="mb-8">
        <div class="flex flex-wrap items-center gap-2 mb-2">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">
                {{ $tool['title'] }}
            </h1>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-violet-100 dark:bg-violet-900/60 text-violet-700 dark:text-violet-300 font-semibold flex items-center gap-1">
                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> AI Gemini Pro
            </span>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 font-semibold flex items-center gap-1">
                <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Chấm Điểm Tự Động
            </span>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-rose-100 dark:bg-rose-900/60 text-rose-700 dark:text-rose-300 font-semibold flex items-center gap-1">
                <i data-lucide="eye" class="w-3.5 h-3.5"></i> Lọc Xem Lại Câu Sai
            </span>
        </div>
        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 max-w-3xl">
            Tự động đọc file PDF, Word (DOCX) hoặc văn bản, nhận diện đáp án in đậm, bôi màu hoặc bảng đáp án. Tạo phòng thi trắc nghiệm trực tuyến có bấm giờ, chấm điểm tức thì và hỗ trợ ôn luyện lại các câu sai.
        </p>
    </div>

    <!-- Top Leaderboard Ad Banner -->
    <x-ad-banner slot="top_leaderboard" class="mb-8" />

    <!-- ========================================== -->
    <!-- PANEL 1: CẤU HÌNH & TẢI FILE TÀI LIỆU -->
    <!-- ========================================== -->
    <div id="setupPanel" class="space-y-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Cột trái: Tải file & Nhập liệu (2 cols) -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Tab chọn phương thức nhập liệu -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-violet-100 dark:bg-violet-950/60 text-violet-600 dark:text-violet-400 flex items-center justify-center">
                                <i data-lucide="file-text" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-slate-800 dark:text-slate-200">1. Tải Lên Tài Liệu Đề Thi</h2>
                                <p class="text-xs text-slate-500">Hỗ trợ file PDF, Word (.docx) hoặc dán văn bản trực tiếp</p>
                            </div>
                        </div>

                        <!-- Switch tabs -->
                        <div class="flex bg-slate-100 dark:bg-slate-800 p-1 rounded-xl text-xs font-medium">
                            <button type="button" id="tabFileBtn" onclick="switchInputTab('file')" class="px-3 py-1.5 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm transition">
                                Tải File
                            </button>
                            <button type="button" id="tabTextBtn" onclick="switchInputTab('text')" class="px-3 py-1.5 rounded-lg text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">
                                Dán Văn Bản
                            </button>
                        </div>
                    </div>

                    <!-- Khu vực tải file -->
                    <div id="inputTabFile">
                        <div id="dropZone" onclick="document.getElementById('fileInput').click()" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)" class="relative p-8 sm:p-10 rounded-2xl border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-violet-500 dark:hover:border-violet-500 bg-slate-50/50 dark:bg-slate-800/40 text-center cursor-pointer transition group">
                            <input type="file" id="fileInput" accept=".pdf,.docx,.txt" class="hidden" onchange="handleFileSelected(this.files)">
                            
                            <div class="w-14 h-14 rounded-2xl bg-violet-50 dark:bg-violet-950/60 text-violet-600 dark:text-violet-400 flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                <i data-lucide="upload-cloud" class="w-7 h-7"></i>
                            </div>
                            
                            <h3 id="fileLabelTitle" class="text-sm font-bold text-slate-800 dark:text-slate-200 mb-1">
                                Kéo thả file PDF, Word hoặc <span class="text-violet-600 dark:text-violet-400 underline">chọn từ thiết bị</span>
                            </h3>
                            <p id="fileLabelDesc" class="text-xs text-slate-400 dark:text-slate-500">
                                Dung lượng tối đa 20MB • Hỗ trợ nhận diện câu hỏi, đáp án bôi màu, in đậm
                            </p>
                        </div>
                    </div>

                    <!-- Khu vực dán văn bản -->
                    <div id="inputTabText" class="hidden space-y-3">
                        <textarea id="rawTextContent" rows="7" placeholder="Dán nội dung câu hỏi trắc nghiệm vào đây...&#10;Ví dụ:&#10;Câu 1: Thủ đô của Việt Nam là gì?&#10;A. Đà Nẵng&#10;B. Hà Nội&#10;C. TP Hồ Chí Minh&#10;D. Cần Thơ&#10;Đáp án: B" class="w-full text-xs sm:text-sm p-4 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none"></textarea>
                    </div>

                    <!-- Quick Sample Exam Banner -->
                    <div class="mt-4 p-4 rounded-2xl bg-violet-50 dark:bg-violet-950/40 border border-violet-200 dark:border-violet-800/60 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-violet-600 text-white flex items-center justify-center flex-shrink-0">
                                <i data-lucide="book-open" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-violet-950 dark:text-violet-200">Đề mẫu có sẵn: 40 Câu Tư Tưởng Hồ Chí Minh</h4>
                                <p class="text-[11px] text-violet-700 dark:text-violet-300">Bộ đề trích xuất từ tài liệu ôn thi Học viện Tài chính kèm đáp án chuẩn.</p>
                            </div>
                        </div>
                        <button type="button" onclick="loadSampleExam()" class="px-3.5 py-1.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-semibold shadow-sm transition flex items-center gap-1.5 whitespace-nowrap self-end sm:self-auto">
                            <i data-lucide="play" class="w-3.5 h-3.5"></i> Thử Đề Mẫu Ngay
                        </button>
                    </div>

                </div>

                <!-- Card: Bộ Đề Đã Lưu & Lịch Sử Đã Đẩy (Saved Exams History) -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                                <i data-lucide="bookmark-check" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-slate-800 dark:text-slate-200">Bộ Đề Đã Lưu & Lịch Sử</h2>
                                <p class="text-xs text-slate-500">Mở lại đề cũ để làm ngay mà không cần tải lại file</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span id="savedExamsCountBadge" class="text-xs px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-bold">
                                0 đề
                            </span>
                            <button type="button" onclick="clearAllSavedExams()" id="clearAllExamsBtn" class="hidden text-xs text-rose-500 hover:text-rose-600 hover:underline transition">
                                Xóa tất cả
                            </button>
                        </div>
                    </div>

                    <!-- Danh sách các đề -->
                    <div id="savedExamsList" class="space-y-3">
                        <!-- Rendered by JS -->
                    </div>
                </div>

            </div>

            <!-- Cột phải: Cài đặt đề thi & Bắt đầu (1 col) -->
            <div class="space-y-6">
                <div id="examSettingsCard" class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-800 shadow-sm space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100 dark:border-slate-800">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                            <i data-lucide="sliders" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800 dark:text-slate-200">2. Cài Đặt Bài Thi</h2>
                            <p class="text-xs text-slate-500">Tùy chỉnh số câu & thời gian</p>
                        </div>
                    </div>

                    <!-- 1. Hình thức thực hiện: Ôn tập vs Thi thử -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Hình thức thực hiện:</label>
                        <div class="grid grid-cols-2 gap-2.5">
                            <button type="button" id="modePracticeBtn" onclick="setQuizMode('practice')" class="p-3 rounded-2xl border-2 border-emerald-500 bg-emerald-50/70 dark:bg-emerald-950/40 text-left transition relative group">
                                <div class="flex items-center gap-2 mb-1">
                                    <div id="modePracticeIcon" class="w-6 h-6 rounded-lg bg-emerald-600 text-white flex items-center justify-center text-xs">
                                        <i data-lucide="book-open" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <span id="modePracticeTitle" class="text-xs font-bold text-slate-900 dark:text-white">Ôn Tập</span>
                                    <span id="modePracticeCheck" class="ml-auto w-2 h-2 rounded-full bg-emerald-500"></span>
                                </div>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-tight">Hiện đáp án đúng ngay khi chọn</p>
                            </button>

                            <button type="button" id="modeExamBtn" onclick="setQuizMode('exam')" class="p-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 text-left hover:border-slate-300 dark:hover:border-slate-700 transition relative group">
                                <div class="flex items-center gap-2 mb-1">
                                    <div id="modeExamIcon" class="w-6 h-6 rounded-lg bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 flex items-center justify-center text-xs">
                                        <i data-lucide="timer" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <span id="modeExamTitle" class="text-xs font-bold text-slate-700 dark:text-slate-300">Thi Thử</span>
                                    <span id="modeExamCheck" class="ml-auto w-2 h-2 rounded-full bg-violet-600 hidden"></span>
                                </div>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-tight">Bấm giờ, nộp bài mới chấm điểm</p>
                            </button>
                        </div>
                    </div>

                    <!-- 2. Số lượng câu hỏi để ôn tập -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 flex items-center justify-between">
                            <span>Số lượng câu hỏi:</span>
                            <span id="detectedQuestionsBadge" class="hidden text-[11px] text-violet-600 dark:text-violet-400 font-bold"></span>
                        </label>
                        <select id="questionLimit" onchange="toggleCustomQuestionInput(this.value)" class="w-full text-xs sm:text-sm p-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
                            <option value="0" selected>Toàn bộ câu hỏi trong tài liệu</option>
                            <option value="10">10 câu</option>
                            <option value="20">20 câu</option>
                            <option value="30">30 câu</option>
                            <option value="40">40 câu (Tiêu chuẩn)</option>
                            <option value="50">50 câu</option>
                            <option value="60">60 câu</option>
                            <option value="100">100 câu</option>
                            <option value="150">150 câu</option>
                            <option value="200">200 câu</option>
                            <option value="custom">✍️ Tùy chỉnh số lượng câu...</option>
                        </select>
                        <div id="customQuestionCountBox" class="hidden mt-2">
                            <input type="number" id="customQuestionCount" min="1" max="1000" placeholder="Nhập số câu (Ví dụ: 75 câu)..." class="w-full text-xs sm:text-sm p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
                        </div>
                    </div>

                    <!-- 3. Thời gian làm bài -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Thời gian làm bài:</label>
                        <select id="examDuration" onchange="toggleCustomDurationInput(this.value)" class="w-full text-xs sm:text-sm p-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
                            <option value="0" selected>Không giới hạn thời gian (Tự do ôn tập)</option>
                            <option value="15">15 phút</option>
                            <option value="30">30 phút (Tiêu chuẩn)</option>
                            <option value="45">45 phút</option>
                            <option value="60">60 phút (1 tiếng)</option>
                            <option value="90">90 phút</option>
                            <option value="120">120 phút (2 tiếng)</option>
                            <option value="custom">✍️ Tùy chỉnh số phút...</option>
                        </select>
                        <div id="customDurationBox" class="hidden mt-2">
                            <input type="number" id="customDurationInput" min="1" max="600" placeholder="Nhập số phút (Ví dụ: 50 phút)..." class="w-full text-xs sm:text-sm p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
                        </div>
                    </div>

                    <!-- 4. Tùy chọn xáo câu & đảo đáp án -->
                    <div class="space-y-2.5 pt-2 border-t border-slate-100 dark:border-slate-800">
                        <label class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                            <input type="checkbox" id="shuffleQuestions" checked class="w-4 h-4 rounded text-violet-600 focus:ring-violet-500 border-slate-300 dark:border-slate-700">
                            <span class="font-medium flex items-center gap-1.5">
                                <i data-lucide="shuffle" class="w-3.5 h-3.5 text-violet-600"></i>
                                Trộn ngẫu nhiên câu hỏi (Xáo câu)
                            </span>
                        </label>
                        <label class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                            <input type="checkbox" id="shuffleOptions" class="w-4 h-4 rounded text-violet-600 focus:ring-violet-500 border-slate-300 dark:border-slate-700">
                            <span class="font-medium flex items-center gap-1.5">
                                <i data-lucide="refresh-cw" class="w-3.5 h-3.5 text-violet-600"></i>
                                Trộn ngẫu nhiên thứ tự đáp án (A, B, C, D)
                            </span>
                        </label>
                    </div>

                    <!-- Nút Bắt đầu làm bài -->
                    <div class="pt-3">
                        <button type="button" id="startExamBtn" onclick="handleStartExam()" class="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 text-white font-bold text-sm shadow-lg shadow-violet-500/25 transition flex items-center justify-center gap-2">
                            <i data-lucide="play-circle" class="w-5 h-5"></i>
                            <span id="startExamBtnText">Bắt Đầu Làm Bài Thi</span>
                        </button>
                    </div>

                    <!-- Trạng thái Loading khi trích xuất -->
                    <div id="parseLoadingStatus" class="hidden text-center py-4 space-y-2">
                        <div class="inline-block animate-spin text-violet-600">
                            <i data-lucide="loader-2" class="w-7 h-7"></i>
                        </div>
                        <p id="parseLoadingText" class="text-xs font-semibold text-slate-700 dark:text-slate-300">Đang đọc tài liệu...</p>
                        <p class="text-[11px] text-slate-400">Quá trình phân tích câu hỏi và đáp án có thể mất từ 5-15 giây.</p>
                    </div>

                </div>
            </div>

        </div>

    </div>

    <!-- ========================================== -->
    <!-- PANEL 2: PHÒNG THI TRỰC TUYẾN (EXAM MODE) -->
    <!-- ========================================== -->
    <div id="examPanel" class="hidden space-y-6">

        <!-- Sticky Header Bar khi làm bài -->
        <div class="sticky top-16 z-30 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md rounded-2xl p-4 border border-slate-200 dark:border-slate-800 shadow-md flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-violet-100 dark:bg-violet-950/60 text-violet-600 dark:text-violet-400 flex items-center justify-center">
                    <i data-lucide="edit-3" class="w-5 h-5"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 id="activeExamTitle" class="text-sm sm:text-base font-bold text-slate-900 dark:text-white truncate max-w-xs sm:max-w-md">Đề Thi Trắc Nghiệm</h2>
                        <span id="activeModeBadge" class="text-[10px] sm:text-xs font-bold px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 flex items-center gap-1">
                            <i data-lucide="book-open" class="w-3 h-3"></i> Ôn Tập
                        </span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <span id="progressText">Đã làm: 0/0 câu (0%)</span>
                    </div>
                </div>
            </div>

            <!-- Timer & Actions -->
            <div class="flex items-center gap-3">
                <!-- Đồng hồ bấm giờ -->
                <div id="timerBadge" class="flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 text-xs sm:text-sm font-mono font-bold">
                    <i data-lucide="clock" class="w-4 h-4 text-violet-600"></i>
                    <span id="timerDisplay">00:00</span>
                </div>

                <!-- Nút nộp bài -->
                <button type="button" id="submitExamBtnHeader" onclick="confirmSubmitExam()" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-rose-600/20 transition flex items-center gap-1.5">
                    <i data-lucide="check-square" class="w-4 h-4"></i>
                    <span id="submitExamBtnText">Kết Thúc Ôn Tập</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

            <!-- Cột câu hỏi chính (3 cols) -->
            <div class="lg:col-span-3 space-y-6" id="questionsContainer">
                <!-- Danh sách câu hỏi render bằng JS -->
            </div>

            <!-- Cột ma trận câu hỏi (1 col) -->
            <div class="lg:col-span-1">
                <div class="sticky top-36 bg-white dark:bg-slate-900 rounded-3xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                        <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Danh Sách Câu Hỏi</h3>
                        <span id="gridProgressRatio" class="text-xs text-violet-600 font-semibold">0/0</span>
                    </div>

                    <!-- Chú thích màu sắc -->
                    <div id="paletteLegendPractice" class="grid grid-cols-3 gap-2 text-[11px] text-slate-500 pb-2">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-md bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 inline-block"></span>
                            <span>Chưa làm</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-md bg-emerald-500 text-white inline-block"></span>
                            <span>Đúng</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-md bg-rose-500 text-white inline-block"></span>
                            <span>Sai</span>
                        </div>
                    </div>
                    <div id="paletteLegendExam" class="hidden grid grid-cols-3 gap-2 text-[11px] text-slate-500 pb-2">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-md bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 inline-block"></span>
                            <span>Chưa làm</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-md bg-violet-600 text-white inline-block"></span>
                            <span>Đã chọn</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-md bg-amber-500 text-white inline-block"></span>
                            <span>Đánh dấu</span>
                        </div>
                    </div>

                    <!-- Ma trận các nút số câu hỏi -->
                    <div id="questionPaletteGrid" class="grid grid-cols-5 gap-2 max-h-96 overflow-y-auto pr-1">
                        <!-- Rendered by JS -->
                    </div>

                    <!-- Nút nộp bài phụ -->
                    <div class="pt-3 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" onclick="confirmSubmitExam()" class="w-full py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold transition flex items-center justify-center gap-2">
                            <i data-lucide="send" class="w-4 h-4"></i> <span id="paletteSubmitText">Xem Bảng Điểm</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- ========================================== -->
    <!-- PANEL 3: BẢNG ĐIỂM & XEM LẠI CÂU SAI -->
    <!-- ========================================== -->
    <div id="resultPanel" class="hidden space-y-8">

        <!-- Banner Chúc mừng & Tổng quan điểm số -->
        <div class="bg-gradient-to-br from-violet-900 via-indigo-900 to-slate-900 text-white rounded-3xl p-6 sm:p-10 shadow-xl relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-64 h-64 rounded-full bg-violet-600/20 blur-3xl pointer-events-none"></div>

            <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                <!-- Điểm số lớn -->
                <div class="text-center md:text-left space-y-1">
                    <span class="text-xs font-bold uppercase tracking-widest text-violet-300">Kết Quả Bài Kiểm Tra</span>
                    <div class="flex items-baseline justify-center md:justify-start gap-2">
                        <span id="scoreTenScale" class="text-5xl sm:text-6xl font-black text-amber-400">8.5</span>
                        <span class="text-2xl text-violet-300 font-bold">/ 10</span>
                    </div>
                    <p id="rankBadge" class="inline-block px-3 py-1 rounded-full bg-white/10 text-xs font-bold uppercase text-white mt-2">
                        XUẤT SẮC
                    </p>
                </div>

                <!-- Thống kê chi tiết -->
                <div class="grid grid-cols-3 gap-3 text-center md:col-span-2">
                    <div class="p-3 sm:p-4 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/10">
                        <div class="text-emerald-400 font-bold text-xl sm:text-2xl flex items-center justify-center gap-1">
                            <i data-lucide="check" class="w-5 h-5"></i>
                            <span id="correctCountDisplay">0</span>
                        </div>
                        <span class="text-[11px] text-slate-300">Câu Đúng</span>
                    </div>
                    <div class="p-3 sm:p-4 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/10">
                        <div class="text-rose-400 font-bold text-xl sm:text-2xl flex items-center justify-center gap-1">
                            <i data-lucide="x" class="w-5 h-5"></i>
                            <span id="wrongCountDisplay">0</span>
                        </div>
                        <span class="text-[11px] text-slate-300">Câu Sai</span>
                    </div>
                    <div class="p-3 sm:p-4 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/10">
                        <div class="text-amber-400 font-bold text-xl sm:text-2xl flex items-center justify-center gap-1">
                            <i data-lucide="clock" class="w-5 h-5"></i>
                            <span id="timeSpentDisplay">00:00</span>
                        </div>
                        <span class="text-[11px] text-slate-300">Thời Gian</span>
                    </div>
                </div>
            </div>

            <!-- Nút hành động nhanh -->
            <div class="relative z-10 flex flex-wrap items-center gap-3 pt-6 mt-6 border-t border-white/10">
                <button type="button" onclick="retakeWrongOnly()" id="retakeWrongBtn" class="px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition flex items-center gap-2 shadow-sm">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i> Ôn Luyện Lại Riêng Các Câu Sai
                </button>
                <button type="button" onclick="retakeAll()" class="px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 text-white text-xs font-bold transition flex items-center gap-2">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i> Làm Lại Toàn Bộ Đề
                </button>
                <button type="button" onclick="resetToSetup()" class="px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 text-white text-xs font-bold transition flex items-center gap-2">
                    <i data-lucide="file-plus" class="w-4 h-4"></i> Tạo Đề Mới Từ File Khác
                </button>
            </div>
        </div>

        <!-- Bộ Lọc Xem Lại Câu Hỏi (Tất Cả, Chỉ Câu Sai, Chỉ Câu Đúng) -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-slate-100 dark:border-slate-800">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i data-lucide="check-check" class="w-5 h-5 text-violet-600"></i>
                        <span>Chi Tiết Đáp Án & Giải Thích</span>
                    </h3>
                    <p class="text-xs text-slate-500">Xem lại câu bạn đã chọn so với đáp án chính xác</p>
                </div>

                <!-- 3 Nút lọc -->
                <div class="flex bg-slate-100 dark:bg-slate-800 p-1 rounded-2xl text-xs font-semibold">
                    <button type="button" id="filterAllBtn" onclick="filterReviewList('all')" class="px-3.5 py-1.5 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm transition">
                        Tất cả (<span id="filterAllCount">0</span>)
                    </button>
                    <button type="button" id="filterWrongBtn" onclick="filterReviewList('wrong')" class="px-3.5 py-1.5 rounded-xl text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition flex items-center gap-1 font-bold">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> Chỉ câu sai (<span id="filterWrongCount">0</span>)
                    </button>
                    <button type="button" id="filterCorrectBtn" onclick="filterReviewList('correct')" class="px-3.5 py-1.5 rounded-xl text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 transition flex items-center gap-1 font-bold">
                        <i data-lucide="check" class="w-3.5 h-3.5"></i> Chỉ câu đúng (<span id="filterCorrectCount">0</span>)
                    </button>
                </div>
            </div>

            <!-- Danh sách câu hỏi xem lại -->
            <div id="reviewQuestionsContainer" class="space-y-6">
                <!-- Rendered by JS -->
            </div>
        </div>

    </div>

    <!-- In-Content Ad Banner -->
    <x-ad-banner slot="in_content" class="my-10" />

    <!-- How-To Guide & SEO Content -->
    <div class="mt-12 space-y-12">
        @if(!empty($tool['how_to']))
            <div class="p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                    <i data-lucide="help-circle" class="w-5 h-5 text-violet-500"></i>
                    <span>Hướng Dẫn Sử Dụng Công Cụ</span>
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($tool['how_to'] as $idx => $step)
                        <div class="flex items-start gap-3.5 p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50">
                            <span class="w-7 h-7 rounded-full bg-violet-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">
                                {{ $idx + 1 }}
                            </span>
                            <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 leading-relaxed pt-0.5">{{ $step }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- FAQ Section -->
        @if(!empty($tool['faq']))
            <div>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                    <i data-lucide="message-square" class="w-5 h-5 text-violet-500"></i>
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

<!-- Modal xác nhận nộp bài -->
<div id="submitConfirmModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 max-w-md w-full border border-slate-200 dark:border-slate-800 shadow-2xl space-y-4">
        <div class="w-12 h-12 rounded-2xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 flex items-center justify-center mx-auto">
            <i data-lucide="alert-triangle" class="w-6 h-6"></i>
        </div>
        <div class="text-center space-y-1">
            <h3 id="submitModalTitle" class="text-base font-bold text-slate-900 dark:text-white">Bạn có chắc muốn nộp bài?</h3>
            <p id="unansweredWarningText" class="text-xs text-slate-500">Bạn đã hoàn thành 0/0 câu hỏi.</p>
        </div>
        <div class="grid grid-cols-2 gap-3 pt-2">
            <button type="button" onclick="closeSubmitModal()" class="py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-semibold hover:bg-slate-200 transition">
                Tiếp tục làm
            </button>
            <button type="button" onclick="submitExamFinal()" id="submitModalBtn" class="py-2.5 rounded-xl bg-violet-600 text-white text-xs font-bold hover:bg-violet-700 transition">
                Nộp bài ngay
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // State management
    let rawQuestions = [];
    let currentQuestions = [];
    let userAnswers = {}; // { questionId: 'A' }
    let flaggedQuestions = new Set();
    let currentExamTitle = 'Bài Thi Trắc Nghiệm';
    let timerInterval = null;
    let totalExamSeconds = 0;
    let remainingSeconds = 0;
    let timeElapsed = 0;
    let currentFilter = 'all';
    let currentQuizMode = 'practice'; // 'practice' (Ôn tập) or 'exam' (Thi thử)

    // Switch Quiz Mode: Ôn tập vs Thi thử
    function setQuizMode(mode) {
        currentQuizMode = mode;
        const practiceBtn = document.getElementById('modePracticeBtn');
        const examBtn = document.getElementById('modeExamBtn');
        const practiceIcon = document.getElementById('modePracticeIcon');
        const examIcon = document.getElementById('modeExamIcon');
        const practiceTitle = document.getElementById('modePracticeTitle');
        const examTitle = document.getElementById('modeExamTitle');
        const practiceCheck = document.getElementById('modePracticeCheck');
        const examCheck = document.getElementById('modeExamCheck');
        const startBtnText = document.getElementById('startExamBtnText');
        const examDuration = document.getElementById('examDuration');

        if (mode === 'practice') {
            if (practiceBtn) practiceBtn.className = 'p-3 rounded-2xl border-2 border-emerald-500 bg-emerald-50/70 dark:bg-emerald-950/40 text-left transition relative group';
            if (practiceIcon) practiceIcon.className = 'w-6 h-6 rounded-lg bg-emerald-600 text-white flex items-center justify-center text-xs';
            if (practiceTitle) practiceTitle.className = 'text-xs font-bold text-slate-900 dark:text-white';
            if (practiceCheck) practiceCheck.classList.remove('hidden');

            if (examBtn) examBtn.className = 'p-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 text-left hover:border-slate-300 dark:hover:border-slate-700 transition relative group';
            if (examIcon) examIcon.className = 'w-6 h-6 rounded-lg bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 flex items-center justify-center text-xs';
            if (examTitle) examTitle.className = 'text-xs font-bold text-slate-700 dark:text-slate-300';
            if (examCheck) examCheck.classList.add('hidden');

            if (startBtnText) startBtnText.innerText = 'Bắt Đầu Ôn Tập';
            if (examDuration && (examDuration.value === '30' || examDuration.value === '45')) {
                examDuration.value = '0';
            }
        } else {
            if (examBtn) examBtn.className = 'p-3 rounded-2xl border-2 border-violet-600 bg-violet-50/70 dark:bg-violet-950/40 text-left transition relative group';
            if (examIcon) examIcon.className = 'w-6 h-6 rounded-lg bg-violet-600 text-white flex items-center justify-center text-xs';
            if (examTitle) examTitle.className = 'text-xs font-bold text-slate-900 dark:text-white';
            if (examCheck) examCheck.classList.remove('hidden');

            if (practiceBtn) practiceBtn.className = 'p-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 text-left hover:border-slate-300 dark:hover:border-slate-700 transition relative group';
            if (practiceIcon) practiceIcon.className = 'w-6 h-6 rounded-lg bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 flex items-center justify-center text-xs';
            if (practiceTitle) practiceTitle.className = 'text-xs font-bold text-slate-700 dark:text-slate-300';
            if (practiceCheck) practiceCheck.classList.add('hidden');

            if (startBtnText) startBtnText.innerText = 'Bắt Đầu Thi Thử';
            if (examDuration && examDuration.value === '0') {
                examDuration.value = '30';
            }
        }
        lucide.createIcons();
    }

    let currentActiveQuizId = null;

    // LocalStorage management for saved exams
    function getSavedExams() {
        try {
            const data = localStorage.getItem('ziitool_saved_quizzes');
            return data ? JSON.parse(data) : [];
        } catch (e) {
            console.warn('Cannot read localStorage:', e);
            return [];
        }
    }

    function saveExamToHistory(title, questions) {
        if (!questions || questions.length === 0) return null;
        try {
            const saved = getSavedExams();
            const existingIdx = saved.findIndex(item => item.title === title && item.total_questions === questions.length);
            const now = new Date();
            const dateStr = now.toLocaleDateString('vi-VN') + ' ' + now.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });

            const examItem = {
                id: existingIdx >= 0 ? saved[existingIdx].id : 'quiz_' + Date.now(),
                title: title || 'Đề Thi Trắc Nghiệm',
                total_questions: questions.length,
                created_at: existingIdx >= 0 ? saved[existingIdx].created_at : dateStr,
                updated_at: dateStr,
                last_score: existingIdx >= 0 ? saved[existingIdx].last_score : null,
                last_rank: existingIdx >= 0 ? saved[existingIdx].last_rank : null,
                questions: questions
            };

            if (existingIdx >= 0) {
                saved.splice(existingIdx, 1);
            }
            saved.unshift(examItem);

            // Cap at 15 items
            if (saved.length > 15) {
                saved.pop();
            }

            localStorage.setItem('ziitool_saved_quizzes', JSON.stringify(saved));
            renderSavedExamsList();
            return examItem.id;
        } catch (e) {
            console.warn('Cannot save exam to localStorage:', e);
            return null;
        }
    }

    function updateExamScoreInHistory(title, scoreTen, rank) {
        try {
            const saved = getSavedExams();
            const item = saved.find(q => q.title === title || q.id === currentActiveQuizId);
            if (item) {
                item.last_score = `${scoreTen}/10`;
                item.last_rank = rank;
                const now = new Date();
                item.updated_at = now.toLocaleDateString('vi-VN') + ' ' + now.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
                localStorage.setItem('ziitool_saved_quizzes', JSON.stringify(saved));
                renderSavedExamsList();
            }
        } catch (e) {
            console.warn('Cannot update score in localStorage:', e);
        }
    }

    function deleteSavedExam(id, e) {
        if (e) e.stopPropagation();
        if (!confirm('Bạn có chắc chắn muốn xóa đề thi này khỏi danh sách đã lưu?')) return;
        try {
            let saved = getSavedExams();
            saved = saved.filter(item => item.id !== id);
            localStorage.setItem('ziitool_saved_quizzes', JSON.stringify(saved));
            if (currentActiveQuizId === id) {
                currentActiveQuizId = null;
            }
            renderSavedExamsList();
        } catch (e) {
            console.warn(e);
        }
    }

    function clearAllSavedExams() {
        if (!confirm('Bạn có chắc chắn muốn xóa toàn bộ danh sách đề thi đã lưu?')) return;
        try {
            localStorage.removeItem('ziitool_saved_quizzes');
            currentActiveQuizId = null;
            renderSavedExamsList();
        } catch (e) {
            console.warn(e);
        }
    }

    function loadSavedExam(id, startImmediately = false) {
        const list = getSavedExams();
        const quiz = list.find(item => item.id === id);
        if (!quiz) return;

        rawQuestions = quiz.questions;
        currentExamTitle = quiz.title;
        currentActiveQuizId = quiz.id;

        const badge = document.getElementById('detectedQuestionsBadge');
        if (badge) {
            badge.innerText = `(Đã nạp ${rawQuestions.length} câu từ đề đã lưu)`;
            badge.classList.remove('hidden');
        }

        renderSavedExamsList(id);

        if (startImmediately) {
            startExamSession();
        } else {
            const settingsCard = document.getElementById('examSettingsCard');
            if (settingsCard) {
                settingsCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    }

    function renderSavedExamsList(activeId = null) {
        const container = document.getElementById('savedExamsList');
        const badge = document.getElementById('savedExamsCountBadge');
        const clearBtn = document.getElementById('clearAllExamsBtn');
        if (!container) return;

        const saved = getSavedExams();
        if (badge) badge.innerText = `${saved.length} đề`;

        if (saved.length === 0) {
            if (clearBtn) clearBtn.classList.add('hidden');
            container.innerHTML = `
                <div class="text-center py-6 px-4 rounded-2xl border border-dashed border-slate-200 dark:border-slate-800 text-slate-400 text-xs space-y-1">
                    <i data-lucide="inbox" class="w-6 h-6 mx-auto mb-1 text-slate-300 dark:text-slate-600"></i>
                    <p class="font-medium text-slate-600 dark:text-slate-400">Chưa có đề thi nào được lưu</p>
                    <p class="text-[11px] text-slate-400">Khi bạn tải file hoặc thử đề mẫu, đề thi sẽ tự động được lưu tại đây để lần sau mở làm lại ngay mà không cần tải lại file.</p>
                </div>
            `;
            lucide.createIcons();
            return;
        }

        if (clearBtn) clearBtn.classList.remove('hidden');
        container.innerHTML = '';

        saved.forEach(quiz => {
            const isCurrentActive = (activeId === quiz.id || currentActiveQuizId === quiz.id);
            const item = document.createElement('div');
            item.className = `p-4 rounded-2xl border transition flex flex-col sm:flex-row sm:items-center justify-between gap-3 ${isCurrentActive ? 'border-2 border-violet-600 bg-violet-50/40 dark:bg-violet-950/20 shadow-sm' : 'border-slate-200 dark:border-slate-800 hover:border-violet-300 dark:hover:border-violet-700 bg-slate-50/50 dark:bg-slate-800/30'}`;

            item.innerHTML = `
                <div class="space-y-1 flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <h4 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white truncate" title="${escapeHtml(quiz.title)}">
                            ${escapeHtml(quiz.title)}
                        </h4>
                        <span class="text-[10px] sm:text-[11px] font-bold px-2 py-0.5 rounded-md bg-violet-100 dark:bg-violet-950/60 text-violet-700 dark:text-violet-300 flex-shrink-0">
                            ${quiz.total_questions} câu
                        </span>
                    </div>
                    <div class="flex items-center gap-3 text-[11px] text-slate-500 dark:text-slate-400 flex-wrap">
                        <span class="flex items-center gap-1">
                            <i data-lucide="calendar" class="w-3 h-3 text-slate-400"></i> ${quiz.created_at}
                        </span>
                        ${quiz.last_score ? `
                            <span class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-bold">
                                <i data-lucide="award" class="w-3 h-3"></i> Điểm: ${quiz.last_score} ${quiz.last_rank ? '(' + quiz.last_rank + ')' : ''}
                            </span>
                        ` : '<span class="text-slate-400 italic">Chưa làm bài</span>'}
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <button type="button" onclick="loadSavedExam('${quiz.id}', true)" class="px-3.5 py-1.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                        <i data-lucide="play" class="w-3.5 h-3.5"></i> Làm Lại Đề Này
                    </button>
                    <button type="button" onclick="loadSavedExam('${quiz.id}', false)" class="px-2.5 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-medium transition" title="Nạp vào cài đặt để tùy chỉnh số câu & thời gian">
                        Cài đặt
                    </button>
                    <button type="button" onclick="deleteSavedExam('${quiz.id}', event)" class="p-1.5 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition" title="Xóa đề này khỏi danh sách">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            `;
            container.appendChild(item);
        });

        lucide.createIcons();
    }

    // On Load
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
        renderSavedExamsList();
    });

    function switchInputTab(tab) {
        if (tab === 'file') {
            document.getElementById('inputTabFile').classList.remove('hidden');
            document.getElementById('inputTabText').classList.add('hidden');
            document.getElementById('tabFileBtn').classList.add('bg-white', 'dark:bg-slate-700', 'text-slate-900', 'dark:text-white', 'shadow-sm');
            document.getElementById('tabFileBtn').classList.remove('text-slate-500', 'dark:text-slate-400');
            document.getElementById('tabTextBtn').classList.remove('bg-white', 'dark:bg-slate-700', 'text-slate-900', 'dark:text-white', 'shadow-sm');
            document.getElementById('tabTextBtn').classList.add('text-slate-500', 'dark:text-slate-400');
        } else {
            document.getElementById('inputTabFile').classList.add('hidden');
            document.getElementById('inputTabText').classList.remove('hidden');
            document.getElementById('tabTextBtn').classList.add('bg-white', 'dark:bg-slate-700', 'text-slate-900', 'dark:text-white', 'shadow-sm');
            document.getElementById('tabTextBtn').classList.remove('text-slate-500', 'dark:text-slate-400');
            document.getElementById('tabFileBtn').classList.remove('bg-white', 'dark:bg-slate-700', 'text-slate-900', 'dark:text-white', 'shadow-sm');
            document.getElementById('tabFileBtn').classList.add('text-slate-500', 'dark:text-slate-400');
        }
    }

    function handleDragOver(e) {
        e.preventDefault();
        document.getElementById('dropZone').classList.add('border-violet-500', 'bg-violet-50/30');
    }

    function handleDragLeave(e) {
        e.preventDefault();
        document.getElementById('dropZone').classList.remove('border-violet-500', 'bg-violet-50/30');
    }

    function handleDrop(e) {
        e.preventDefault();
        document.getElementById('dropZone').classList.remove('border-violet-500', 'bg-violet-50/30');
        if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
            handleFileSelected(e.dataTransfer.files);
        }
    }

    let selectedUploadFile = null;

    function handleFileSelected(files) {
        if (!files || files.length === 0) return;
        selectedUploadFile = files[0];
        document.getElementById('fileLabelTitle').innerHTML = `Đã chọn file: <span class="text-violet-600 font-bold">${selectedUploadFile.name}</span>`;
        document.getElementById('fileLabelDesc').innerText = `Dung lượng: ${(selectedUploadFile.size / 1024 / 1024).toFixed(2)} MB • Sẵn sàng tạo đề thi`;
    }

    // Load sample exam
    async function loadSampleExam() {
        setParseLoading(true, 'Đang nạp đề mẫu 40 câu Tư tưởng Hồ Chí Minh...');
        try {
            const resp = await fetch('{{ route("tool.quiz.sample") }}');
            const data = await resp.json();
            if (data.success && data.questions && data.questions.length > 0) {
                rawQuestions = data.questions;
                currentExamTitle = data.title || '300 Câu Trắc Nghiệm Tư Tưởng Hồ Chí Minh';
                currentActiveQuizId = saveExamToHistory(currentExamTitle, rawQuestions);
                const badge = document.getElementById('detectedQuestionsBadge');
                if (badge) {
                    badge.innerText = `(Tài liệu có ${rawQuestions.length} câu)`;
                    badge.classList.remove('hidden');
                }
                setParseLoading(false);
                startExamSession();
            } else {
                throw new Error(data.error || 'Không tải được đề mẫu');
            }
        } catch (err) {
            setParseLoading(false);
            alert('Lỗi nạp đề mẫu: ' + err.message);
        }
    }

    // Start exam button handler
    async function handleStartExam() {
        const textContent = document.getElementById('rawTextContent').value.trim();
        const model = 'gemini-2.0-flash';
        const mode = 'auto';

        if (!selectedUploadFile && !textContent) {
            alert('Vui lòng chọn 1 file tài liệu (PDF, Word, TXT) hoặc dán văn bản câu hỏi, hoặc bấm "Thử Đề Mẫu Ngay".');
            return;
        }

        const formData = new FormData();
        if (selectedUploadFile) {
            formData.append('file', selectedUploadFile);
        } else {
            formData.append('text', textContent);
        }

        formData.append('model', model);
        formData.append('mode', mode);

        setParseLoading(true, 'AI đang đọc tài liệu và phân tích câu hỏi...');

        try {
            const resp = await fetch('{{ route("tool.quiz.parse") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const data = await resp.json();

            if (!resp.ok || !data.success) {
                throw new Error(data.error || 'Không thể trích xuất câu hỏi từ tài liệu này.');
            }

            rawQuestions = data.questions;
            currentExamTitle = data.title || (selectedUploadFile ? selectedUploadFile.name : 'Bài Thi Trắc Nghiệm');
            currentActiveQuizId = saveExamToHistory(currentExamTitle, rawQuestions);
            const badge = document.getElementById('detectedQuestionsBadge');
            if (badge) {
                badge.innerText = `(Tài liệu có ${rawQuestions.length} câu)`;
                badge.classList.remove('hidden');
            }
            setParseLoading(false);
            startExamSession();

        } catch (err) {
            setParseLoading(false);
            alert('Lỗi: ' + err.message);
        }
    }

    function setParseLoading(isLoading, text = '') {
        const btn = document.getElementById('startExamBtn');
        const loader = document.getElementById('parseLoadingStatus');
        const loaderText = document.getElementById('parseLoadingText');

        if (isLoading) {
            btn.classList.add('hidden');
            loader.classList.remove('hidden');
            loaderText.innerText = text;
        } else {
            btn.classList.remove('hidden');
            loader.classList.add('hidden');
        }
    }

    function toggleCustomQuestionInput(val) {
        const box = document.getElementById('customQuestionCountBox');
        if (val === 'custom') {
            box.classList.remove('hidden');
            const input = document.getElementById('customQuestionCount');
            if (!input.value && rawQuestions.length > 0) {
                input.value = Math.min(40, rawQuestions.length);
            }
            input.focus();
        } else {
            box.classList.add('hidden');
        }
    }

    function toggleCustomDurationInput(val) {
        const box = document.getElementById('customDurationBox');
        if (val === 'custom') {
            box.classList.remove('hidden');
            const input = document.getElementById('customDurationInput');
            if (!input.value) input.value = 45;
            input.focus();
        } else {
            box.classList.add('hidden');
        }
    }

    // Start exam session
    function startExamSession(filterQuestionIds = null) {
        if (!rawQuestions || rawQuestions.length === 0) return;

        // Reset state
        userAnswers = {};
        flaggedQuestions.clear();

        let questionsToUse = [...rawQuestions];

        // If retaking only specific questions (e.g. wrong answers)
        if (filterQuestionIds && filterQuestionIds.length > 0) {
            questionsToUse = questionsToUse.filter(q => filterQuestionIds.includes(q.id));
        }

        // Apply question limit
        let limit = 0;
        const limitVal = document.getElementById('questionLimit').value;
        if (limitVal === 'custom') {
            limit = parseInt(document.getElementById('customQuestionCount').value) || 0;
        } else {
            limit = parseInt(limitVal) || 0;
        }

        const doShuffleQ = document.getElementById('shuffleQuestions').checked;
        const doShuffleOpt = document.getElementById('shuffleOptions').checked;

        if (doShuffleQ) {
            questionsToUse.sort(() => Math.random() - 0.5);
        }

        if (limit > 0 && limit < questionsToUse.length && !filterQuestionIds) {
            questionsToUse = questionsToUse.slice(0, limit);
        }

        // Shuffle options if requested
        if (doShuffleOpt) {
            questionsToUse = questionsToUse.map(q => {
                const optKeys = Object.keys(q.options);
                const optValues = optKeys.map(k => ({ key: k, text: q.options[k], isCorrect: k === q.correct }));
                optValues.sort(() => Math.random() - 0.5);

                const newOptions = {};
                let newCorrect = 'A';
                const alphabet = ['A', 'B', 'C', 'D', 'E'];

                optValues.forEach((item, idx) => {
                    const newKey = alphabet[idx] || item.key;
                    newOptions[newKey] = item.text;
                    if (item.isCorrect) {
                        newCorrect = newKey;
                    }
                });

                return {
                    ...q,
                    options: newOptions,
                    correct: newCorrect
                };
            });
        }

        currentQuestions = questionsToUse;

        // UI transitions
        document.getElementById('setupPanel').classList.add('hidden');
        document.getElementById('resultPanel').classList.add('hidden');
        document.getElementById('examPanel').classList.remove('hidden');

        document.getElementById('activeExamTitle').innerText = currentExamTitle;

        // Update mode badge & buttons in header
        const modeBadge = document.getElementById('activeModeBadge');
        const submitBtnText = document.getElementById('submitExamBtnText');
        const paletteSubmitText = document.getElementById('paletteSubmitText');
        const legendPractice = document.getElementById('paletteLegendPractice');
        const legendExam = document.getElementById('paletteLegendExam');

        if (currentQuizMode === 'practice') {
            if (modeBadge) {
                modeBadge.className = 'text-[10px] sm:text-xs font-bold px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 flex items-center gap-1';
                modeBadge.innerHTML = '<i data-lucide="book-open" class="w-3 h-3"></i> Ôn Tập';
            }
            if (submitBtnText) submitBtnText.innerText = 'Kết Thúc Ôn Tập';
            if (paletteSubmitText) paletteSubmitText.innerText = 'Xem Bảng Điểm';
            if (legendPractice) legendPractice.classList.remove('hidden');
            if (legendExam) legendExam.classList.add('hidden');
        } else {
            if (modeBadge) {
                modeBadge.className = 'text-[10px] sm:text-xs font-bold px-2 py-0.5 rounded-full bg-violet-100 dark:bg-violet-950/60 text-violet-700 dark:text-violet-300 flex items-center gap-1';
                modeBadge.innerHTML = '<i data-lucide="timer" class="w-3 h-3"></i> Thi Thử';
            }
            if (submitBtnText) submitBtnText.innerText = 'Nộp Bài';
            if (paletteSubmitText) paletteSubmitText.innerText = 'Nộp Bài & Xem Điểm';
            if (legendPractice) legendPractice.classList.add('hidden');
            if (legendExam) legendExam.classList.remove('hidden');
        }

        // Render questions & palette
        renderQuestionsList();
        renderQuestionPalette();
        updateExamProgress();

        // Setup timer
        let durationMin = 0;
        const durVal = document.getElementById('examDuration').value;
        if (durVal === 'custom') {
            durationMin = parseInt(document.getElementById('customDurationInput').value) || 0;
        } else {
            durationMin = parseInt(durVal) || 0;
        }
        setupExamTimer(durationMin);

        // Smooth scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
        lucide.createIcons();
    }

    // Render questions in exam
    function renderQuestionsList() {
        const container = document.getElementById('questionsContainer');
        container.innerHTML = '';

        currentQuestions.forEach((q, index) => {
            const card = document.createElement('div');
            card.id = `qCard_${q.id}`;
            card.className = 'bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-800 shadow-sm space-y-5 transition';

            // Question Header
            const header = document.createElement('div');
            header.className = 'flex items-start justify-between gap-3';
            header.innerHTML = `
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-violet-100 dark:bg-violet-950/60 text-violet-700 dark:text-violet-300 font-bold text-xs flex items-center justify-center flex-shrink-0">
                        ${index + 1}
                    </span>
                    <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white leading-relaxed">
                        ${escapeHtml(q.question)}
                    </h3>
                </div>
                <button type="button" onclick="toggleFlagQuestion(${q.id})" id="flagBtn_${q.id}" class="text-slate-400 hover:text-amber-500 transition p-1.5 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800" title="Đánh dấu câu này để xem lại">
                    <i data-lucide="flag" class="w-4 h-4"></i>
                </button>
            `;
            card.appendChild(header);

            // Options List
            const optList = document.createElement('div');
            optList.id = `optList_${q.id}`;
            optList.className = 'grid grid-cols-1 gap-2.5 pt-1';

            Object.keys(q.options).forEach(optKey => {
                const optText = q.options[optKey];
                const optBtn = document.createElement('div');
                optBtn.id = `opt_${q.id}_${optKey}`;
                optBtn.onclick = () => selectOption(q.id, optKey);
                optBtn.className = 'flex items-center justify-between gap-3.5 p-3.5 sm:p-4 rounded-2xl border border-slate-200 dark:border-slate-800 hover:border-violet-400 dark:hover:border-violet-600 bg-slate-50/50 dark:bg-slate-800/40 cursor-pointer transition group';

                optBtn.innerHTML = `
                    <div class="flex items-center gap-3.5 flex-1 min-w-0">
                        <span class="w-7 h-7 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs flex items-center justify-center flex-shrink-0 group-hover:bg-violet-600 group-hover:text-white group-hover:border-violet-600 transition">
                            ${optKey}
                        </span>
                        <span class="text-xs sm:text-sm text-slate-800 dark:text-slate-200 leading-snug">
                            ${escapeHtml(optText)}
                        </span>
                    </div>
                    <div id="badge_${q.id}_${optKey}" class="flex-shrink-0"></div>
                `;
                optList.appendChild(optBtn);
            });

            card.appendChild(optList);

            // Placeholder for practice mode immediate explanation box
            const explainBox = document.createElement('div');
            explainBox.id = `explainBox_${q.id}`;
            explainBox.className = 'hidden';
            card.appendChild(explainBox);

            container.appendChild(card);
        });
    }

    // Render palette matrix
    function renderQuestionPalette() {
        const grid = document.getElementById('questionPaletteGrid');
        grid.innerHTML = '';

        currentQuestions.forEach((q, index) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.id = `paletteBtn_${q.id}`;
            btn.onclick = () => scrollToQuestion(q.id);
            btn.className = 'w-full aspect-square rounded-xl text-xs font-bold transition flex items-center justify-center bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700';
            btn.innerText = index + 1;
            grid.appendChild(btn);
        });
    }

    function selectOption(qId, selectedKey) {
        userAnswers[qId] = selectedKey;

        const q = currentQuestions.find(item => item.id === qId);
        if (!q) return;

        const isPractice = (currentQuizMode === 'practice');

        if (isPractice) {
            // PRACTICE MODE: Reveal correct answer immediately!
            const isUserCorrect = (selectedKey.toUpperCase() === q.correct.toUpperCase());

            Object.keys(q.options).forEach(optKey => {
                const el = document.getElementById(`opt_${qId}_${optKey}`);
                if (!el) return;
                const circle = el.querySelector('span');
                const badgeContainer = document.getElementById(`badge_${qId}_${optKey}`);
                const isThisOptOfficialCorrect = (optKey.toUpperCase() === q.correct.toUpperCase());
                const isThisOptSelected = (optKey === selectedKey);

                if (isThisOptSelected && isUserCorrect) {
                    // Selected and Correct!
                    el.className = 'flex items-center justify-between gap-3.5 p-3.5 sm:p-4 rounded-2xl border-2 border-emerald-500 bg-emerald-50 dark:bg-emerald-950/40 cursor-pointer transition';
                    circle.className = 'w-7 h-7 rounded-lg bg-emerald-600 text-white font-bold text-xs flex items-center justify-center flex-shrink-0';
                    if (badgeContainer) {
                        badgeContainer.innerHTML = '<span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1"><i data-lucide="check" class="w-4 h-4"></i> Chính xác</span>';
                    }
                } else if (isThisOptSelected && !isUserCorrect) {
                    // Selected and Wrong!
                    el.className = 'flex items-center justify-between gap-3.5 p-3.5 sm:p-4 rounded-2xl border-2 border-rose-500 bg-rose-50 dark:bg-rose-950/40 cursor-pointer transition';
                    circle.className = 'w-7 h-7 rounded-lg bg-rose-600 text-white font-bold text-xs flex items-center justify-center flex-shrink-0';
                    if (badgeContainer) {
                        badgeContainer.innerHTML = '<span class="text-xs font-bold text-rose-600 dark:text-rose-400 flex items-center gap-1"><i data-lucide="x" class="w-4 h-4"></i> Bạn chọn</span>';
                    }
                } else if (isThisOptOfficialCorrect) {
                    // Not selected by user, but this IS the official correct answer! Reveal it!
                    el.className = 'flex items-center justify-between gap-3.5 p-3.5 sm:p-4 rounded-2xl border-2 border-emerald-500 bg-emerald-50/70 dark:bg-emerald-950/30 cursor-pointer transition';
                    circle.className = 'w-7 h-7 rounded-lg bg-emerald-600 text-white font-bold text-xs flex items-center justify-center flex-shrink-0';
                    if (badgeContainer) {
                        badgeContainer.innerHTML = '<span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1"><i data-lucide="check-circle" class="w-4 h-4"></i> Đáp án đúng</span>';
                    }
                } else {
                    // Other options
                    el.className = 'flex items-center justify-between gap-3.5 p-3.5 sm:p-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-800/20 opacity-60 cursor-pointer transition';
                    circle.className = 'w-7 h-7 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-400 font-bold text-xs flex items-center justify-center flex-shrink-0';
                    if (badgeContainer) badgeContainer.innerHTML = '';
                }
            });

            // Show explanation box immediately
            const explainBox = document.getElementById(`explainBox_${qId}`);
            if (explainBox) {
                explainBox.className = `p-4 rounded-2xl ${isUserCorrect ? 'bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-950 dark:text-emerald-200' : 'bg-rose-50/70 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800 text-rose-950 dark:text-rose-200'} text-xs leading-relaxed space-y-2 mt-2 transition`;
                explainBox.innerHTML = `
                    <div class="flex items-center gap-2 font-bold text-xs sm:text-sm ${isUserCorrect ? 'text-emerald-700 dark:text-emerald-300' : 'text-rose-700 dark:text-rose-300'}">
                        <i data-lucide="${isUserCorrect ? 'check-circle' : 'alert-circle'}" class="w-4 h-4"></i>
                        <span>${isUserCorrect ? 'Bạn đã trả lời chính xác!' : `Chưa chính xác! Đáp án đúng là: ${q.correct}`}</span>
                    </div>
                    <div class="text-slate-700 dark:text-slate-300 pt-1">
                        <p><strong class="text-slate-900 dark:text-white">Đáp án đúng:</strong> <strong>${q.correct}.</strong> ${escapeHtml(q.options[q.correct] || '')}</p>
                        ${q.explanation ? `<p class="mt-1 text-slate-600 dark:text-slate-400 italic"><strong class="not-italic text-slate-800 dark:text-slate-200">💡 Giải thích:</strong> ${escapeHtml(q.explanation)}</p>` : ''}
                    </div>
                `;
            }

        } else {
            // EXAM MODE: Standard selection, do not reveal correct answer
            Object.keys(q.options).forEach(optKey => {
                const el = document.getElementById(`opt_${qId}_${optKey}`);
                if (!el) return;
                const circle = el.querySelector('span');

                if (optKey === selectedKey) {
                    el.className = 'flex items-center justify-between gap-3.5 p-3.5 sm:p-4 rounded-2xl border-2 border-violet-600 bg-violet-50/80 dark:bg-violet-950/40 cursor-pointer transition';
                    circle.className = 'w-7 h-7 rounded-lg bg-violet-600 text-white font-bold text-xs flex items-center justify-center flex-shrink-0';
                } else {
                    el.className = 'flex items-center justify-between gap-3.5 p-3.5 sm:p-4 rounded-2xl border border-slate-200 dark:border-slate-800 hover:border-violet-400 dark:hover:border-violet-600 bg-slate-50/50 dark:bg-slate-800/40 cursor-pointer transition group';
                    circle.className = 'w-7 h-7 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs flex items-center justify-center flex-shrink-0 group-hover:bg-violet-600 group-hover:text-white group-hover:border-violet-600 transition';
                }
            });
        }

        // Update palette button
        updatePaletteButton(qId);
        updateExamProgress();
        lucide.createIcons();
    }

    function toggleFlagQuestion(qId) {
        const flagBtn = document.getElementById(`flagBtn_${qId}`);
        if (flaggedQuestions.has(qId)) {
            flaggedQuestions.delete(qId);
            flagBtn.className = 'text-slate-400 hover:text-amber-500 transition p-1.5 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800';
        } else {
            flaggedQuestions.add(qId);
            flagBtn.className = 'text-amber-500 transition p-1.5 rounded-lg bg-amber-50 dark:bg-amber-950/60';
        }
        updatePaletteButton(qId);
    }

    function updatePaletteButton(qId) {
        const btn = document.getElementById(`paletteBtn_${qId}`);
        if (!btn) return;

        const isAnswered = !!userAnswers[qId];
        const isFlagged = flaggedQuestions.has(qId);
        const q = currentQuestions.find(item => item.id === qId);

        if (isFlagged) {
            btn.className = 'w-full aspect-square rounded-xl text-xs font-bold transition flex items-center justify-center bg-amber-500 text-white shadow-sm';
            return;
        }

        if (isAnswered) {
            if (currentQuizMode === 'practice' && q) {
                const isCorrect = (userAnswers[qId].toUpperCase() === q.correct.toUpperCase());
                if (isCorrect) {
                    btn.className = 'w-full aspect-square rounded-xl text-xs font-bold transition flex items-center justify-center bg-emerald-500 text-white shadow-sm';
                } else {
                    btn.className = 'w-full aspect-square rounded-xl text-xs font-bold transition flex items-center justify-center bg-rose-500 text-white shadow-sm';
                }
            } else {
                btn.className = 'w-full aspect-square rounded-xl text-xs font-bold transition flex items-center justify-center bg-violet-600 text-white shadow-sm';
            }
        } else {
            btn.className = 'w-full aspect-square rounded-xl text-xs font-bold transition flex items-center justify-center bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700';
        }
    }

    function scrollToQuestion(qId) {
        const card = document.getElementById(`qCard_${qId}`);
        if (card) {
            const offset = 90;
            const bodyRect = document.body.getBoundingClientRect().top;
            const elementRect = card.getBoundingClientRect().top;
            const elementPosition = elementRect - bodyRect;
            const offsetPosition = elementPosition - offset;

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
        }
    }

    function updateExamProgress() {
        const total = currentQuestions.length;
        const answered = Object.keys(userAnswers).length;
        const percent = total > 0 ? Math.round((answered / total) * 100) : 0;

        document.getElementById('progressText').innerText = `Đã làm: ${answered}/${total} câu (${percent}%)`;
        document.getElementById('gridProgressRatio').innerText = `${answered}/${total}`;
    }

    function setupExamTimer(durationMinutes) {
        if (timerInterval) clearInterval(timerInterval);
        timeElapsed = 0;

        if (durationMinutes <= 0) {
            // Count up timer
            document.getElementById('timerDisplay').innerText = '00:00';
            timerInterval = setInterval(() => {
                timeElapsed++;
                const mins = String(Math.floor(timeElapsed / 60)).padStart(2, '0');
                const secs = String(timeElapsed % 60).padStart(2, '0');
                document.getElementById('timerDisplay').innerText = `${mins}:${secs}`;
            }, 1000);
        } else {
            // Countdown timer
            totalExamSeconds = durationMinutes * 60;
            remainingSeconds = totalExamSeconds;

            const updateDisplay = () => {
                const mins = String(Math.floor(remainingSeconds / 60)).padStart(2, '0');
                const secs = String(remainingSeconds % 60).padStart(2, '0');
                document.getElementById('timerDisplay').innerText = `${mins}:${secs}`;

                if (remainingSeconds <= 300) { // < 5 mins
                    document.getElementById('timerBadge').className = 'flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 text-xs sm:text-sm font-mono font-bold animate-pulse';
                }
            };

            updateDisplay();

            timerInterval = setInterval(() => {
                remainingSeconds--;
                timeElapsed++;
                updateDisplay();

                if (remainingSeconds <= 0) {
                    clearInterval(timerInterval);
                    alert('Hết giờ làm bài! Hệ thống sẽ tự động nộp bài và chấm điểm.');
                    submitExamFinal();
                }
            }, 1000);
        }
    }

    function confirmSubmitExam() {
        const total = currentQuestions.length;
        const answered = Object.keys(userAnswers).length;
        const unanswered = total - answered;

        const isPractice = (currentQuizMode === 'practice');
        const modalTitle = document.getElementById('submitModalTitle');
        const modalBtn = document.getElementById('submitModalBtn');

        if (isPractice) {
            if (modalTitle) modalTitle.innerText = 'Kết thúc ôn tập & xem bảng điểm?';
            if (modalBtn) modalBtn.innerText = 'Xem bảng điểm ngay';
        } else {
            if (modalTitle) modalTitle.innerText = 'Bạn có chắc muốn nộp bài?';
            if (modalBtn) modalBtn.innerText = 'Nộp bài ngay';
        }

        let warning = `Bạn đã hoàn thành ${answered}/${total} câu hỏi.`;
        if (unanswered > 0) {
            warning += ` Còn ${unanswered} câu chưa chọn đáp án!`;
        }

        document.getElementById('unansweredWarningText').innerText = warning;
        document.getElementById('submitConfirmModal').classList.remove('hidden');
    }

    function closeSubmitModal() {
        document.getElementById('submitConfirmModal').classList.add('hidden');
    }

    // Submit and Grade
    function submitExamFinal() {
        closeSubmitModal();
        if (timerInterval) clearInterval(timerInterval);

        // Calculate score
        let correctCount = 0;
        let wrongCount = 0;
        let unansweredCount = 0;

        currentQuestions.forEach(q => {
            const userChoice = userAnswers[q.id];
            if (!userChoice) {
                unansweredCount++;
                wrongCount++;
            } else if (userChoice.toUpperCase() === q.correct.toUpperCase()) {
                correctCount++;
            } else {
                wrongCount++;
            }
        });

        const total = currentQuestions.length;
        const scoreTen = total > 0 ? ((correctCount / total) * 10).toFixed(1) : '0.0';
        const scorePercent = total > 0 ? Math.round((correctCount / total) * 100) : 0;

        // Rank determination
        let rank = 'CẦN CỐ GẮNG';
        let rankColor = 'bg-rose-500/20 text-rose-300';
        if (scorePercent >= 90) {
            rank = 'XUẤT SẮC 🏆';
            rankColor = 'bg-emerald-500/20 text-emerald-300';
        } else if (scorePercent >= 80) {
            rank = 'GIỎI 🎉';
            rankColor = 'bg-indigo-500/20 text-indigo-300';
        } else if (scorePercent >= 65) {
            rank = 'KHÁ 👍';
            rankColor = 'bg-amber-500/20 text-amber-300';
        }

        // Format time spent
        const mins = String(Math.floor(timeElapsed / 60)).padStart(2, '0');
        const secs = String(timeElapsed % 60).padStart(2, '0');

        // Update Result Banner
        document.getElementById('scoreTenScale').innerText = scoreTen;
        document.getElementById('correctCountDisplay').innerText = `${correctCount}/${total}`;
        document.getElementById('wrongCountDisplay').innerText = `${wrongCount}/${total}`;
        document.getElementById('timeSpentDisplay').innerText = `${mins}:${secs}`;
        
        const rankEl = document.getElementById('rankBadge');
        rankEl.innerText = rank;
        rankEl.className = `inline-block px-3 py-1 rounded-full text-xs font-bold uppercase mt-2 ${rankColor}`;

        // Update filters counts
        document.getElementById('filterAllCount').innerText = total;
        document.getElementById('filterWrongCount').innerText = wrongCount;
        document.getElementById('filterCorrectCount').innerText = correctCount;

        // Update score in saved history
        updateExamScoreInHistory(currentExamTitle, scoreTen, rank);

        // Render review cards
        renderReviewQuestions();

        // UI transition
        document.getElementById('examPanel').classList.add('hidden');
        document.getElementById('resultPanel').classList.remove('hidden');

        window.scrollTo({ top: 0, behavior: 'smooth' });
        lucide.createIcons();
    }

    // Render review cards with wrong/correct answers
    function renderReviewQuestions() {
        const container = document.getElementById('reviewQuestionsContainer');
        container.innerHTML = '';

        currentQuestions.forEach((q, index) => {
            const userChoice = userAnswers[q.id];
            const isCorrect = userChoice && userChoice.toUpperCase() === q.correct.toUpperCase();

            const card = document.createElement('div');
            card.id = `reviewCard_${q.id}`;
            card.setAttribute('data-correct', isCorrect ? 'true' : 'false');

            const borderColor = isCorrect 
                ? 'border-emerald-200 dark:border-emerald-900/60 bg-emerald-50/20 dark:bg-emerald-950/20' 
                : 'border-rose-200 dark:border-rose-900/60 bg-rose-50/20 dark:bg-rose-950/20';

            card.className = `p-6 sm:p-8 rounded-3xl border ${borderColor} space-y-5 transition`;

            // Question Header
            const statusBadge = isCorrect
                ? `<span class="px-2.5 py-1 rounded-xl bg-emerald-100 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300 text-xs font-bold flex items-center gap-1">
                    <i data-lucide="check" class="w-3.5 h-3.5"></i> Đúng
                   </span>`
                : `<span class="px-2.5 py-1 rounded-xl bg-rose-100 dark:bg-rose-950/80 text-rose-700 dark:text-rose-300 text-xs font-bold flex items-center gap-1">
                    <i data-lucide="x" class="w-3.5 h-3.5"></i> Sai (Bạn chọn: ${userChoice || 'Bỏ trống'})
                   </span>`;

            card.innerHTML = `
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-xl ${isCorrect ? 'bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700' : 'bg-rose-100 dark:bg-rose-900/60 text-rose-700'} font-bold text-xs flex items-center justify-center flex-shrink-0">
                            ${index + 1}
                        </span>
                        <h4 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white leading-relaxed">
                            ${escapeHtml(q.question)}
                        </h4>
                    </div>
                    ${statusBadge}
                </div>
            `;

            // Options Comparison List
            const optList = document.createElement('div');
            optList.className = 'grid grid-cols-1 gap-2.5 pt-1';

            Object.keys(q.options).forEach(optKey => {
                const optText = q.options[optKey];
                const isSelectedByUser = userChoice === optKey;
                const isOfficialCorrect = q.correct.toUpperCase() === optKey;

                let optStyle = 'border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300';
                let indicator = '';

                if (isOfficialCorrect) {
                    optStyle = 'border-2 border-emerald-500 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-950 dark:text-emerald-200 font-semibold';
                    indicator = `<span class="ml-auto text-xs font-bold text-emerald-600 flex items-center gap-1"><i data-lucide="check-circle" class="w-4 h-4"></i> Đáp án đúng</span>`;
                } else if (isSelectedByUser && !isOfficialCorrect) {
                    optStyle = 'border-2 border-rose-500 bg-rose-50 dark:bg-rose-950/40 text-rose-950 dark:text-rose-200 font-semibold';
                    indicator = `<span class="ml-auto text-xs font-bold text-rose-600 flex items-center gap-1"><i data-lucide="x-circle" class="w-4 h-4"></i> Lựa chọn của bạn</span>`;
                }

                const optItem = document.createElement('div');
                optItem.className = `flex items-center gap-3.5 p-3.5 sm:p-4 rounded-2xl border ${optStyle} transition`;
                optItem.innerHTML = `
                    <span class="w-7 h-7 rounded-lg ${isOfficialCorrect ? 'bg-emerald-600 text-white' : (isSelectedByUser ? 'bg-rose-600 text-white' : 'border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-600')} font-bold text-xs flex items-center justify-center flex-shrink-0">
                        ${optKey}
                    </span>
                    <span class="text-xs sm:text-sm leading-snug">
                        ${escapeHtml(optText)}
                    </span>
                    ${indicator}
                `;
                optList.appendChild(optItem);
            });

            card.appendChild(optList);

            // Explanation box
            if (q.explanation) {
                const expBox = document.createElement('div');
                expBox.className = 'p-4 rounded-2xl bg-violet-50/80 dark:bg-violet-950/40 border border-violet-200/80 dark:border-violet-800/60 text-xs text-violet-900 dark:text-violet-200 leading-relaxed flex items-start gap-2.5';
                expBox.innerHTML = `
                    <i data-lucide="info" class="w-4 h-4 text-violet-600 dark:text-violet-400 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <span class="font-bold">Giải thích:</span> ${escapeHtml(q.explanation)}
                    </div>
                `;
                card.appendChild(expBox);
            }

            container.appendChild(card);
        });

        lucide.createIcons();
    }

    // Filter review list
    function filterReviewList(filter) {
        currentFilter = filter;
        const cards = document.querySelectorAll('#reviewQuestionsContainer > div');

        cards.forEach(card => {
            const isCorrect = card.getAttribute('data-correct') === 'true';
            if (filter === 'all') {
                card.classList.remove('hidden');
            } else if (filter === 'wrong') {
                if (!isCorrect) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            } else if (filter === 'correct') {
                if (isCorrect) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            }
        });

        // Toggle buttons style
        const btnAll = document.getElementById('filterAllBtn');
        const btnWrong = document.getElementById('filterWrongBtn');
        const btnCorrect = document.getElementById('filterCorrectBtn');

        [btnAll, btnWrong, btnCorrect].forEach(b => {
            b.classList.remove('bg-white', 'dark:bg-slate-700', 'text-slate-900', 'dark:text-white', 'shadow-sm');
        });

        if (filter === 'all') {
            btnAll.classList.add('bg-white', 'dark:bg-slate-700', 'text-slate-900', 'dark:text-white', 'shadow-sm');
        } else if (filter === 'wrong') {
            btnWrong.classList.add('bg-white', 'dark:bg-slate-700', 'text-slate-900', 'dark:text-white', 'shadow-sm');
        } else if (filter === 'correct') {
            btnCorrect.classList.add('bg-white', 'dark:bg-slate-700', 'text-slate-900', 'dark:text-white', 'shadow-sm');
        }
    }

    // Retake only wrong answers
    function retakeWrongOnly() {
        const wrongIds = [];
        currentQuestions.forEach(q => {
            const userChoice = userAnswers[q.id];
            if (!userChoice || userChoice.toUpperCase() !== q.correct.toUpperCase()) {
                wrongIds.push(q.id);
            }
        });

        if (wrongIds.length === 0) {
            alert('Tuyệt vời! Bạn không có câu nào làm sai trong bài kiểm tra này.');
            return;
        }

        currentExamTitle = `[Ôn Luyện Lại] Các Câu Làm Sai (${wrongIds.length} Câu)`;
        setQuizMode('practice');
        startExamSession(wrongIds);
    }

    // Retake all
    function retakeAll() {
        startExamSession();
    }

    // Reset to setup
    function resetToSetup() {
        if (timerInterval) clearInterval(timerInterval);
        document.getElementById('resultPanel').classList.add('hidden');
        document.getElementById('examPanel').classList.add('hidden');
        document.getElementById('setupPanel').classList.remove('hidden');
        renderSavedExamsList();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
</script>
@endpush
@endsection

