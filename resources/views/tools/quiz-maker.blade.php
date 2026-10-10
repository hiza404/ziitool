@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-6">
            <a href="{{ route('home') }}" class="hover:text-indigo-600">{{ __('Trang chủ') }}</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span>{{ __($categoryInfo['name'] ?? 'Giáo dục & Ôn thi') }}</span>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span class="text-slate-800 dark:text-slate-200 font-semibold">{{ __($tool['title']) }}</span>
        </nav>

        <!-- Tool Header -->
        <div class="mb-8">
            <div class="flex flex-wrap items-center gap-2 mb-2">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">
                    {{ __($tool['title']) }}
                </h1>
                <span
                    class="text-xs px-2.5 py-0.5 rounded-full bg-violet-100 dark:bg-violet-900/60 text-violet-700 dark:text-violet-300 font-semibold flex items-center gap-1">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> {{ __('Trích Xuất Thông Minh') }}
                </span>
                <span
                    class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 font-semibold flex items-center gap-1">
                    <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> {{ __('Chấm Điểm Tự Động') }}
                </span>
                <span
                    class="text-xs px-2.5 py-0.5 rounded-full bg-rose-100 dark:bg-rose-900/60 text-rose-700 dark:text-rose-300 font-semibold flex items-center gap-1">
                    <i data-lucide="eye" class="w-3.5 h-3.5"></i> {{ __('Lọc Xem Lại Câu Sai') }}
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 max-w-3xl">
                {{ __('Tự động đọc file PDF hoặc văn bản đề thi, nhận diện đáp án in đậm, bôi màu hoặc bảng đáp án. Tạo phòng thi trắc nghiệm trực tuyến có bấm giờ, chấm điểm tức thì và hỗ trợ ôn luyện lại các câu sai.') }}
            </p>
        </div>

        <!-- Top Leaderboard Ad Banner -->
        <x-ad-banner placement="top_leaderboard" class="mb-8" />

        @if (session('quiz_error'))
            <div
                class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-xs sm:text-sm flex items-center justify-between gap-3 shadow-sm animate-pulse">
                <div class="flex items-center gap-2.5">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-600 flex-shrink-0"></i>
                    <span class="font-medium">{{ __(session('quiz_error')) }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 p-1">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif

        <!-- Thanh Thông Tin Tài Khoản & Truy Cập Đề Của Tôi -->
        <div
            class="mb-6 p-4 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                @auth
                    <div
                        class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-violet-600 to-indigo-600 text-white flex items-center justify-center font-black text-sm shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span
                                class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white">{{ Auth::user()->name }}</span>
                            <span
                                class="text-[10px] px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 font-semibold flex items-center gap-1">
                                <i data-lucide="check" class="w-3 h-3"></i> {{ __('Đã đăng nhập') }}
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">{!! __('Bạn có thể lưu đề thi vĩnh viễn trên Server và tùy chọn chế độ <b>Công Khai</b> hoặc <b>Riêng Tư</b>.') !!}</p>
                    </div>
                @else
                    <div
                        class="w-10 h-10 rounded-2xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="user-check" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span
                                class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200">{{ __('Khách Vãng Lai') }}</span>
                            <span
                                class="text-[10px] px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 font-medium">{{ __('Chưa đăng nhập') }}</span>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">{!! __(
                            'Đăng nhập để tự động đồng bộ đề thi lên tài khoản và tạo đề <b>Riêng Tư (Private)</b> chỉ mình bạn mở được.',
                        ) !!}</p>
                    </div>
                @endauth
            </div>

            <div class="flex items-center gap-2 flex-wrap self-end sm:self-auto">
                @auth
                    <button type="button" onclick="openMyQuizzesModal()"
                        class="px-3.5 py-2 rounded-xl bg-violet-50 dark:bg-violet-950/50 hover:bg-violet-100 dark:hover:bg-violet-900/50 text-violet-700 dark:text-violet-300 border border-violet-200 dark:border-violet-800/80 text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                        <i data-lucide="folder-kanban" class="w-4 h-4 text-violet-600"></i>
                        <span>{{ __('Bộ Đề Của Tôi (Server)') }}</span>
                    </button>
                    <form action="{{ route('logout') }}" method="POST" class="inline"
                        onsubmit="return confirm('{{ __('Bạn có chắc chắn muốn đăng xuất tài khoản?') }}');">
                        @csrf
                        <input type="hidden" name="redirect" value="{{ request()->getRequestUri() }}">
                        <button type="submit"
                            class="px-3.5 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-950/40 dark:hover:text-rose-400 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-800 text-xs font-bold transition flex items-center gap-1.5"
                            title="{{ __('Đăng xuất tài khoản') }}">
                            <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                            <span>{{ __('Đăng Xuất') }}</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login', ['redirect' => request()->getRequestUri()]) }}"
                        class="px-3.5 py-2 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold transition shadow-sm flex items-center gap-1.5">
                        <i data-lucide="log-in" class="w-3.5 h-3.5"></i>
                        <span>{{ __('Đăng Nhập') }}</span>
                    </a>
                    <a href="{{ route('register', ['redirect' => request()->getRequestUri()]) }}"
                        class="px-3 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold transition">
                        {{ __('Đăng Ký') }}
                    </a>
                @endauth
            </div>
        </div>

        <!-- Thanh Mở Nhanh Đề Thi Bằng Mã Đề (Quick Open by Quiz Code) -->
        <div
            class="mb-8 p-4 sm:p-5 rounded-3xl bg-gradient-to-r from-violet-600/10 via-indigo-600/10 to-transparent border border-violet-200/80 dark:border-violet-800/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div
                    class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-violet-600 to-indigo-600 text-white flex items-center justify-center flex-shrink-0 shadow-md shadow-violet-500/20">
                    <i data-lucide="key-round" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span>{{ __('Bạn Có Mã Đề Thi Được Chia Sẻ?') }}</span>
                        <span
                            class="text-[10px] px-2 py-0.5 rounded-full bg-violet-100 dark:bg-violet-950/60 text-violet-700 dark:text-violet-300 font-semibold">{{ __('1 Giây Mở Đề') }}</span>
                    </h3>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">{!! __('Nhập mã đề (Ví dụ: :sample) để mở làm ngay trên bất kỳ máy nào mà không cần tải lại file.', [
                        'sample' => '<code class="font-mono font-bold text-violet-600 dark:text-violet-400">ZT-A1B2C3</code>',
                    ]) !!}</p>
                </div>
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto">
                <div class="relative flex-1 md:w-56">
                    <input type="text" id="quickQuizCodeInput" placeholder="ZT-XXXXXX" maxlength="15"
                        onkeydown="if(event.key==='Enter') handleLoadQuizByCode()"
                        class="w-full text-xs sm:text-sm font-mono font-bold uppercase tracking-wider py-2.5 px-3.5 pl-8 rounded-xl border border-violet-200 dark:border-violet-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-violet-500 outline-none shadow-inner">
                    <i data-lucide="hash" class="w-4 h-4 text-slate-400 absolute left-2.5 top-3"></i>
                </div>
                <button type="button" onclick="handleLoadQuizByCode()" id="btnQuickLoadCode"
                    class="px-4 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-violet-500/20 transition flex items-center gap-1.5 flex-shrink-0">
                    <i data-lucide="arrow-right-circle" class="w-4 h-4"></i>
                    <span id="btnQuickLoadCodeText">{{ __('Mở Đề Thi') }}</span>
                </button>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- PANEL 1: CẤU HÌNH & TẢI FILE TÀI LIỆU -->
        <!-- ========================================== -->
        <div id="setupPanel" class="space-y-8">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Cột trái: Tải file & Nhập liệu (2 cols) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Tab chọn phương thức nhập liệu -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-800 shadow-sm">
                        <div
                            class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100 dark:border-slate-800">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-violet-100 dark:bg-violet-950/60 text-violet-600 dark:text-violet-400 flex items-center justify-center">
                                    <i data-lucide="file-text" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h2 class="text-base font-bold text-slate-800 dark:text-slate-200">
                                        {{ __('1. Tải Lên Tài Liệu Đề Thi') }}</h2>
                                    <p class="text-xs text-slate-500">
                                        {{ __('Hỗ trợ file PDF (hoặc chuyển sang tab Dán Văn Bản)') }}</p>
                                </div>
                            </div>

                            <!-- Switch tabs -->
                            <div class="flex bg-slate-100 dark:bg-slate-800 p-1 rounded-xl text-xs font-medium">
                                <button type="button" id="tabFileBtn" onclick="switchInputTab('file')"
                                    class="px-3 py-1.5 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm transition">
                                    {{ __('Tải File PDF') }}
                                </button>
                                <button type="button" id="tabTextBtn" onclick="switchInputTab('text')"
                                    class="px-3 py-1.5 rounded-lg text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition">
                                    {{ __('Dán Văn Bản') }}
                                </button>
                            </div>
                        </div>

                        <!-- Khu vực tải file -->
                        <div id="inputTabFile">
                            <div id="dropZone" onclick="document.getElementById('fileInput').click()"
                                ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)"
                                ondrop="handleDrop(event)"
                                class="relative p-8 sm:p-10 rounded-2xl border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-violet-500 dark:hover:border-violet-500 bg-slate-50/50 dark:bg-slate-800/40 text-center cursor-pointer transition group">
                                <input type="file" id="fileInput" accept=".pdf,application/pdf" class="hidden"
                                    onchange="handleFileSelected(this.files)">

                                <div
                                    class="w-14 h-14 rounded-2xl bg-violet-50 dark:bg-violet-950/60 text-violet-600 dark:text-violet-400 flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                    <i data-lucide="file-up" class="w-7 h-7"></i>
                                </div>

                                <h3 id="fileLabelTitle" class="text-sm font-bold text-slate-800 dark:text-slate-200 mb-1">
                                    {!! __('Kéo thả file PDF hoặc :choose', [
                                        'choose' =>
                                            '<span class="text-violet-600 dark:text-violet-400 underline">' . __('chọn file PDF từ thiết bị') . '</span>',
                                    ]) !!}
                                </h3>
                                <p id="fileLabelDesc" class="text-xs text-slate-400 dark:text-slate-500">
                                    {{ __('Định dạng hỗ trợ: File PDF (tối đa 50MB) • Tự động nhận diện câu hỏi và đáp án') }}
                                </p>
                            </div>
                        </div>

                        <!-- Khu vực dán văn bản -->
                        <div id="inputTabText" class="hidden space-y-3">
                            <textarea id="rawTextContent" oninput="hasNewUnparsedInput = true;" rows="7"
                                placeholder="{{ __('Dán nội dung câu hỏi trắc nghiệm vào đây...\nVí dụ:\nCâu 1: Thủ đô của Việt Nam là gì?\nA. Đà Nẵng\nB. Hà Nội\nC. TP Hồ Chí Minh\nD. Cần Thơ\nĐáp án: B') }}"
                                class="w-full text-xs sm:text-sm p-4 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none"></textarea>
                        </div>

                        <!-- Quick Sample Exam Banner -->
                        <div
                            class="mt-4 p-4 rounded-2xl bg-violet-50 dark:bg-violet-950/40 border border-violet-200 dark:border-violet-800/60 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-lg bg-violet-600 text-white flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="book-open" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <h4 class="text-xs sm:text-sm font-bold text-violet-950 dark:text-violet-200">
                                        {{ __('Đề mẫu có sẵn: 40 Câu Tư Tưởng Hồ Chí Minh') }}</h4>
                                    <p class="text-[11px] text-violet-700 dark:text-violet-300">
                                        {{ __('Bộ đề trích xuất từ tài liệu ôn thi Học viện Tài chính kèm đáp án chuẩn.') }}
                                    </p>
                                </div>
                            </div>
                            <button type="button" onclick="loadSampleExam()"
                                class="px-3.5 py-1.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-semibold shadow-sm transition flex items-center gap-1.5 whitespace-nowrap self-end sm:self-auto">
                                <i data-lucide="play" class="w-3.5 h-3.5"></i> {{ __('Thử Đề Mẫu Ngay') }}
                            </button>
                        </div>

                    </div>

                    <!-- Card: Bộ Đề Đã Lưu & Lịch Sử Đã Đẩy (Saved Exams History) -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                        <div
                            class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                                    <i data-lucide="bookmark-check" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h2 class="text-base font-bold text-slate-800 dark:text-slate-200">
                                        {{ __('Bộ Đề Đã Lưu & Lịch Sử') }}</h2>
                                    <p class="text-xs text-slate-500">
                                        {{ __('Mở lại đề cũ để làm ngay mà không cần tải lại file') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span id="savedExamsCountBadge"
                                    class="text-xs px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-bold">
                                    {{ __('0 đề') }}
                                </span>
                                <button type="button" onclick="clearAllSavedExams()" id="clearAllExamsBtn"
                                    class="hidden text-xs text-rose-500 hover:text-rose-600 hover:underline transition">
                                    {{ __('Xóa tất cả') }}
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
                    <div id="examSettingsCard"
                        class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-800 shadow-sm space-y-5">
                        <div class="flex items-center gap-3 pb-4 border-b border-slate-100 dark:border-slate-800">
                            <div
                                class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                                <i data-lucide="sliders" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-slate-800 dark:text-slate-200">
                                    {{ __('2. Cài Đặt Bài Thi') }}</h2>
                                <p class="text-xs text-slate-500">{{ __('Tùy chỉnh số câu & thời gian') }}</p>
                            </div>
                        </div>

                        <!-- 1. Hình thức thực hiện: Ôn tập vs Thi thử -->
                        <div>
                            <label
                                class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">{{ __('Hình thức thực hiện:') }}</label>
                            <div class="grid grid-cols-2 gap-2.5">
                                <button type="button" id="modePracticeBtn" onclick="setQuizMode('practice')"
                                    class="p-3 rounded-2xl border-2 border-emerald-500 bg-emerald-50/70 dark:bg-emerald-950/40 text-left transition relative group">
                                    <div class="flex items-center gap-2 mb-1">
                                        <div id="modePracticeIcon"
                                            class="w-6 h-6 rounded-lg bg-emerald-600 text-white flex items-center justify-center text-xs">
                                            <i data-lucide="book-open" class="w-3.5 h-3.5"></i>
                                        </div>
                                        <span id="modePracticeTitle"
                                            class="text-xs font-bold text-slate-900 dark:text-white">{{ __('Ôn Tập') }}</span>
                                        <span id="modePracticeCheck"
                                            class="ml-auto w-2 h-2 rounded-full bg-emerald-500"></span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-tight">
                                        {{ __('Hiện đáp án đúng ngay khi chọn') }}</p>
                                </button>

                                <button type="button" id="modeExamBtn" onclick="setQuizMode('exam')"
                                    class="p-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 text-left hover:border-slate-300 dark:hover:border-slate-700 transition relative group">
                                    <div class="flex items-center gap-2 mb-1">
                                        <div id="modeExamIcon"
                                            class="w-6 h-6 rounded-lg bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 flex items-center justify-center text-xs">
                                            <i data-lucide="timer" class="w-3.5 h-3.5"></i>
                                        </div>
                                        <span id="modeExamTitle"
                                            class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ __('Thi Thử') }}</span>
                                        <span id="modeExamCheck"
                                            class="ml-auto w-2 h-2 rounded-full bg-violet-600 hidden"></span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-tight">
                                        {{ __('Bấm giờ, nộp bài mới chấm điểm') }}</p>
                                </button>
                            </div>
                        </div>

                        <!-- 2. Số lượng câu hỏi để ôn tập -->
                        <div>
                            <label
                                class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 flex items-center justify-between">
                                <span>{{ __('Số lượng câu hỏi:') }}</span>
                                <span id="detectedQuestionsBadge"
                                    class="hidden text-[11px] text-violet-600 dark:text-violet-400 font-bold"></span>
                            </label>
                            <select id="questionLimit" onchange="toggleCustomQuestionInput(this.value)"
                                class="w-full text-xs sm:text-sm p-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
                                <option value="0" selected>{{ __('Toàn bộ câu hỏi trong tài liệu') }}</option>
                                <option value="10">{{ __('10 câu') }}</option>
                                <option value="20">{{ __('20 câu') }}</option>
                                <option value="30">{{ __('30 câu') }}</option>
                                <option value="40">{{ __('40 câu (Tiêu chuẩn)') }}</option>
                                <option value="50">{{ __('50 câu') }}</option>
                                <option value="60">{{ __('60 câu') }}</option>
                                <option value="100">{{ __('100 câu') }}</option>
                                <option value="150">{{ __('150 câu') }}</option>
                                <option value="200">{{ __('200 câu') }}</option>
                                <option value="custom">{{ __('✍️ Tùy chỉnh số lượng câu...') }}</option>
                            </select>
                            <div id="customQuestionCountBox" class="hidden mt-2">
                                <input type="number" id="customQuestionCount" min="1" max="1000"
                                    placeholder="{{ __('Nhập số câu (Ví dụ: 75 câu)...') }}"
                                    class="w-full text-xs sm:text-sm p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
                            </div>
                        </div>

                        <!-- 3. Thời gian làm bài -->
                        <div>
                            <label
                                class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Thời gian làm bài:') }}</label>
                            <select id="examDuration" onchange="toggleCustomDurationInput(this.value)"
                                class="w-full text-xs sm:text-sm p-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
                                <option value="0" selected>{{ __('Không giới hạn thời gian (Tự do ôn tập)') }}
                                </option>
                                <option value="15">{{ __('15 phút') }}</option>
                                <option value="30">{{ __('30 phút (Tiêu chuẩn)') }}</option>
                                <option value="45">{{ __('45 phút') }}</option>
                                <option value="60">{{ __('60 phút (1 tiếng)') }}</option>
                                <option value="90">{{ __('90 phút') }}</option>
                                <option value="120">{{ __('120 phút (2 tiếng)') }}</option>
                                <option value="custom">{{ __('✍️ Tùy chỉnh số phút...') }}</option>
                            </select>
                            <div id="customDurationBox" class="hidden mt-2">
                                <input type="number" id="customDurationInput" min="1" max="600"
                                    placeholder="{{ __('Nhập số phút (Ví dụ: 50 phút)...') }}"
                                    class="w-full text-xs sm:text-sm p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-violet-500 outline-none">
                            </div>
                        </div>

                        <!-- 4. Tùy chọn xáo câu & đảo đáp án -->
                        <div class="space-y-2.5 pt-2 border-t border-slate-100 dark:border-slate-800">
                            <label
                                class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                                <input type="checkbox" id="shuffleQuestions" checked
                                    class="w-4 h-4 rounded text-violet-600 focus:ring-violet-500 border-slate-300 dark:border-slate-700">
                                <span class="font-medium flex items-center gap-1.5">
                                    <i data-lucide="shuffle" class="w-3.5 h-3.5 text-violet-600"></i>
                                    {{ __('Trộn ngẫu nhiên câu hỏi (Xáo câu)') }}
                                </span>
                            </label>
                            <label
                                class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                                <input type="checkbox" id="shuffleOptions"
                                    class="w-4 h-4 rounded text-violet-600 focus:ring-violet-500 border-slate-300 dark:border-slate-700">
                                <span class="font-medium flex items-center gap-1.5">
                                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5 text-violet-600"></i>
                                    {{ __('Trộn ngẫu nhiên thứ tự đáp án (A, B, C, D)') }}
                                </span>
                            </label>
                        </div>

                        <!-- 5. Quyền riêng tư & Lưu trữ đề thi -->
                        <div class="space-y-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                            <div class="flex items-center justify-between">
                                <label
                                    class="block text-xs font-semibold text-slate-700 dark:text-slate-300">{{ __('Quyền riêng tư đề thi:') }}</label>
                                <span id="visStatusBadge"
                                    class="text-[10px] px-2 py-0.5 rounded-full bg-violet-100 dark:bg-violet-950/60 text-violet-700 dark:text-violet-300 font-bold">{{ __('Công Khai') }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" id="visPublicBtn" onclick="setQuizVisibility(true)"
                                    class="p-2.5 rounded-2xl border-2 border-violet-600 bg-violet-50/70 dark:bg-violet-950/40 text-left transition relative">
                                    <div class="flex items-center gap-1.5 mb-1">
                                        <div
                                            class="w-5 h-5 rounded-md bg-violet-600 text-white flex items-center justify-center text-[10px]">
                                            <i data-lucide="globe" class="w-3.5 h-3.5"></i>
                                        </div>
                                        <span
                                            class="text-xs font-bold text-slate-900 dark:text-white">{{ __('Công Khai') }}</span>
                                        <span id="visPublicCheck"
                                            class="ml-auto w-2 h-2 rounded-full bg-violet-600"></span>
                                    </div>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400 leading-tight">
                                        {{ __('Mọi người có mã/link đều có thể làm') }}</p>
                                </button>

                                <button type="button" id="visPrivateBtn" onclick="setQuizVisibility(false)"
                                    class="p-2.5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 text-left hover:border-slate-300 dark:hover:border-slate-700 transition relative">
                                    <div class="flex items-center gap-1.5 mb-1">
                                        <div
                                            class="w-5 h-5 rounded-md bg-slate-300 dark:bg-slate-700 text-slate-700 dark:text-slate-300 flex items-center justify-center text-[10px]">
                                            <i data-lucide="lock" class="w-3.5 h-3.5"></i>
                                        </div>
                                        <span
                                            class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ __('Riêng Tư') }}</span>
                                        <span id="visPrivateCheck"
                                            class="ml-auto w-2 h-2 rounded-full bg-violet-600 hidden"></span>
                                    </div>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400 leading-tight">
                                        {{ __('Chỉ tài khoản bạn mới mở được') }}</p>
                                </button>
                            </div>
                            <p id="visGuestNotice"
                                class="hidden text-[11px] text-amber-600 dark:text-amber-400 flex items-center gap-1 pt-1 bg-amber-50 dark:bg-amber-950/40 p-2 rounded-xl border border-amber-200 dark:border-amber-800/60">
                                <i data-lucide="alert-circle" class="w-3.5 h-3.5 flex-shrink-0"></i>
                                <span>{!! __('Bạn cần :login để đặt đề ở chế độ Riêng tư.', [
                                    'login' =>
                                        '<a href="' .
                                        route('login', ['redirect' => request()->getRequestUri()]) .
                                        '" class="underline font-bold">' .
                                        __('đăng nhập') .
                                        '</a>',
                                ]) !!}</span>
                            </p>
                        </div>

                        <!-- Nút Bắt đầu làm bài, Lấy link chia sẻ & Lưu lên Server -->
                        <div class="pt-3 space-y-2">
                            <button type="button" id="startExamBtn" onclick="handleStartExam()"
                                class="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 text-white font-bold text-sm shadow-lg shadow-violet-500/25 transition flex items-center justify-center gap-2">
                                <i data-lucide="play-circle" class="w-5 h-5"></i>
                                <span id="startExamBtnText">{{ __('Bắt Đầu Làm Bài Thi') }}</span>
                            </button>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" id="shareQuizSetupBtn" onclick="openShareModal()"
                                    class="py-2.5 px-3 rounded-xl border border-violet-200 dark:border-violet-800 hover:bg-violet-50 dark:hover:bg-violet-950/40 text-violet-700 dark:text-violet-300 font-semibold text-xs transition flex items-center justify-center gap-1.5 shadow-sm">
                                    <i data-lucide="share-2" class="w-3.5 h-3.5"></i>
                                    <span>{{ __('Mã Đề & Link') }}</span>
                                </button>
                                <button type="button" id="manualSaveServerBtn" onclick="manualSaveCurrentQuizToServer()"
                                    class="py-2.5 px-3 rounded-xl bg-violet-50 dark:bg-violet-950/40 hover:bg-violet-100 dark:hover:bg-violet-900/50 border border-violet-200 dark:border-violet-800 text-violet-700 dark:text-violet-300 font-bold text-xs transition flex items-center justify-center gap-1.5 shadow-sm">
                                    <i data-lucide="cloud-upload" class="w-3.5 h-3.5 text-violet-600"></i>
                                    <span id="manualSaveServerBtnText">{{ __('Lưu Lên Server') }}</span>
                                </button>
                            </div>
                        </div>

                        <!-- Trạng thái Loading khi trích xuất -->
                        <div id="parseLoadingStatus" class="hidden text-center py-4 space-y-2">
                            <div class="inline-block animate-spin text-violet-600">
                                <i data-lucide="loader-2" class="w-7 h-7"></i>
                            </div>
                            <p id="parseLoadingText" class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                {{ __('Đang đọc tài liệu...') }}</p>
                            <p class="text-[11px] text-slate-400">
                                {{ __('Quá trình phân tích câu hỏi và đáp án có thể mất từ 30-60 giây.') }}</p>
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
            <div
                class="sticky top-0 z-50 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md rounded-2xl p-4 border border-slate-200 dark:border-slate-800 shadow-md flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-violet-100 dark:bg-violet-950/60 text-violet-600 dark:text-violet-400 flex items-center justify-center">
                        <i data-lucide="edit-3" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h2 id="activeExamTitle"
                                class="text-sm sm:text-base font-bold text-slate-900 dark:text-white truncate max-w-xs sm:max-w-md">
                                {{ __('Đề Thi Trắc Nghiệm') }}</h2>
                            <span id="activeModeBadge"
                                class="text-[10px] sm:text-xs font-bold px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 flex items-center gap-1">
                                <i data-lucide="book-open" class="w-3 h-3"></i> {{ __('Ôn Tập') }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <span id="progressText">{{ __('Đã làm: 0/0 câu (0%)') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Timer & Actions -->
                <div class="flex items-center gap-2.5">
                    <!-- Mã đề & Nút chia sẻ nhanh -->
                    <button type="button" onclick="openShareModal()" id="examHeaderShareBtn"
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-violet-50 dark:bg-violet-950/60 hover:bg-violet-100 dark:hover:bg-violet-900/60 text-violet-700 dark:text-violet-300 text-xs font-bold border border-violet-200 dark:border-violet-800 transition shadow-sm"
                        title="{{ __('Xem mã & link chia sẻ') }}">
                        <i data-lucide="share-2" class="w-3.5 h-3.5 text-violet-600 dark:text-violet-400"></i>
                        <span class="hidden sm:inline">{{ __('Mã:') }}</span>
                        <span id="activeQuizCodeBadge" class="font-mono">---</span>
                    </button>

                    <!-- Đồng hồ bấm giờ -->
                    <div id="timerBadge"
                        class="flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 text-xs sm:text-sm font-mono font-bold">
                        <i data-lucide="clock" class="w-4 h-4 text-violet-600"></i>
                        <span id="timerDisplay">00:00</span>
                    </div>

                    <!-- Nút nộp bài -->
                    <button type="button" id="submitExamBtnHeader" onclick="confirmSubmitExam()"
                        class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-rose-600/20 transition flex items-center gap-1.5">
                        <i data-lucide="check-square" class="w-4 h-4"></i>
                        <span id="submitExamBtnText">{{ __('Kết Thúc Ôn Tập') }}</span>
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
                    <div
                        class="sticky top-36 bg-white dark:bg-slate-900 rounded-3xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                        <div
                            class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                            <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">
                                {{ __('Danh Sách Câu Hỏi') }}</h3>
                            <span id="gridProgressRatio" class="text-xs text-violet-600 font-semibold">0/0</span>
                        </div>

                        <!-- Chú thích màu sắc -->
                        <div id="paletteLegendPractice" class="grid grid-cols-3 gap-2 text-[11px] text-slate-500 pb-2">
                            <div class="flex items-center gap-1.5">
                                <span
                                    class="w-3 h-3 rounded-md bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 inline-block"></span>
                                <span>{{ __('Chưa làm') }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-md bg-emerald-500 text-white inline-block"></span>
                                <span>{{ __('Đúng') }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-md bg-rose-500 text-white inline-block"></span>
                                <span>{{ __('Sai') }}</span>
                            </div>
                        </div>
                        <div id="paletteLegendExam" class="hidden grid grid-cols-3 gap-2 text-[11px] text-slate-500 pb-2">
                            <div class="flex items-center gap-1.5">
                                <span
                                    class="w-3 h-3 rounded-md bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 inline-block"></span>
                                <span>{{ __('Chưa làm') }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-md bg-violet-600 text-white inline-block"></span>
                                <span>{{ __('Đã chọn') }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-md bg-amber-500 text-white inline-block"></span>
                                <span>{{ __('Đánh dấu') }}</span>
                            </div>
                        </div>

                        <!-- Ma trận các nút số câu hỏi -->
                        <div id="questionPaletteGrid" class="grid grid-cols-5 gap-2 max-h-96 overflow-y-auto pr-1">
                            <!-- Rendered by JS -->
                        </div>

                        <!-- Nút nộp bài phụ -->
                        <div class="pt-3 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" onclick="confirmSubmitExam()"
                                class="w-full py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold transition flex items-center justify-center gap-2">
                                <i data-lucide="send" class="w-4 h-4"></i> <span
                                    id="paletteSubmitText">{{ __('Xem Bảng Điểm') }}</span>
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
            <div
                class="bg-gradient-to-br from-violet-900 via-indigo-900 to-slate-900 text-white rounded-3xl p-6 sm:p-10 shadow-xl relative overflow-hidden">
                <div
                    class="absolute -right-10 -bottom-10 w-64 h-64 rounded-full bg-violet-600/20 blur-3xl pointer-events-none">
                </div>

                <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                    <!-- Điểm số lớn -->
                    <div class="text-center md:text-left space-y-1">
                        <span
                            class="text-xs font-bold uppercase tracking-widest text-violet-300">{{ __('Kết Quả Bài Kiểm Tra') }}</span>
                        <div class="flex items-baseline justify-center md:justify-start gap-2">
                            <span id="scoreTenScale" class="text-5xl sm:text-6xl font-black text-amber-400">8.5</span>
                            <span class="text-2xl text-violet-300 font-bold">/ 10</span>
                        </div>
                        <p id="rankBadge"
                            class="inline-block px-3 py-1 rounded-full bg-white/10 text-xs font-bold uppercase text-white mt-2">
                            {{ __('XUẤT SẮC 🏆') }}
                        </p>
                    </div>

                    <!-- Thống kê chi tiết -->
                    <div class="grid grid-cols-3 gap-3 text-center md:col-span-2">
                        <div class="p-3 sm:p-4 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/10">
                            <div
                                class="text-emerald-400 font-bold text-xl sm:text-2xl flex items-center justify-center gap-1">
                                <i data-lucide="check" class="w-5 h-5"></i>
                                <span id="correctCountDisplay">0</span>
                            </div>
                            <span class="text-[11px] text-slate-300">{{ __('Câu Đúng') }}</span>
                        </div>
                        <div class="p-3 sm:p-4 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/10">
                            <div
                                class="text-rose-400 font-bold text-xl sm:text-2xl flex items-center justify-center gap-1">
                                <i data-lucide="x" class="w-5 h-5"></i>
                                <span id="wrongCountDisplay">0</span>
                            </div>
                            <span class="text-[11px] text-slate-300">{{ __('Câu Sai') }}</span>
                        </div>
                        <div class="p-3 sm:p-4 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/10">
                            <div
                                class="text-amber-400 font-bold text-xl sm:text-2xl flex items-center justify-center gap-1">
                                <i data-lucide="clock" class="w-5 h-5"></i>
                                <span id="timeSpentDisplay">00:00</span>
                            </div>
                            <span class="text-[11px] text-slate-300">{{ __('Thời Gian') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Nút hành động nhanh -->
                <div class="relative z-10 flex flex-wrap items-center gap-3 pt-6 mt-6 border-t border-white/10">
                    <button type="button" onclick="openShareModal()"
                        class="px-4 py-2.5 rounded-xl bg-white text-violet-900 hover:bg-slate-100 font-bold text-xs transition flex items-center gap-2 shadow-sm">
                        <i data-lucide="share-2" class="w-4 h-4 text-violet-600"></i> {{ __('Chia Sẻ Đề Này (Mã Đề)') }}
                    </button>
                    <button type="button" onclick="retakeWrongOnly()" id="retakeWrongBtn"
                        class="px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition flex items-center gap-2 shadow-sm">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i> {{ __('Ôn Luyện Lại Riêng Các Câu Sai') }}
                    </button>
                    <button type="button" onclick="retakeAll()"
                        class="px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 text-white text-xs font-bold transition flex items-center gap-2">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i> {{ __('Làm Lại Toàn Bộ Đề') }}
                    </button>
                    <button type="button" onclick="resetToSetup()"
                        class="px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 text-white text-xs font-bold transition flex items-center gap-2">
                        <i data-lucide="file-plus" class="w-4 h-4"></i> {{ __('Tạo Đề Mới Từ File Khác') }}
                    </button>
                </div>

            </div>

            <!-- Bộ Lọc Xem Lại Câu Hỏi (Tất Cả, Chỉ Câu Sai, Chỉ Câu Đúng) -->
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
                <div
                    class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <i data-lucide="check-check" class="w-5 h-5 text-violet-600"></i>
                            <span>{{ __('Chi Tiết Đáp Án & Giải Thích') }}</span>
                        </h3>
                        <p class="text-xs text-slate-500">{{ __('Xem lại câu bạn đã chọn so với đáp án chính xác') }}</p>
                    </div>

                    <!-- 3 Nút lọc -->
                    <div class="flex bg-slate-100 dark:bg-slate-800 p-1 rounded-2xl text-xs font-semibold">
                        <button type="button" id="filterAllBtn" onclick="filterReviewList('all')"
                            class="px-3.5 py-1.5 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm transition">
                            {{ __('Tất cả') }} (<span id="filterAllCount">0</span>)
                        </button>
                        <button type="button" id="filterWrongBtn" onclick="filterReviewList('wrong')"
                            class="px-3.5 py-1.5 rounded-xl text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition flex items-center gap-1 font-bold">
                            <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ __('Chỉ câu sai') }} (<span
                                id="filterWrongCount">0</span>)
                        </button>
                        <button type="button" id="filterCorrectBtn" onclick="filterReviewList('correct')"
                            class="px-3.5 py-1.5 rounded-xl text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 transition flex items-center gap-1 font-bold">
                            <i data-lucide="check" class="w-3.5 h-3.5"></i> {{ __('Chỉ câu đúng') }} (<span
                                id="filterCorrectCount">0</span>)
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
        <x-ad-banner placement="in_content" class="my-10" />

        <!-- How-To Guide & SEO Content -->
        <div class="mt-12 space-y-12">
            @if (!empty($tool['how_to']))
                <div
                    class="p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                        <i data-lucide="help-circle" class="w-5 h-5 text-violet-500"></i>
                        <span>{{ __('Hướng Dẫn Sử Dụng Công Cụ') }}</span>
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($tool['how_to'] as $idx => $step)
                            <div class="flex items-start gap-3.5 p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50">
                                <span
                                    class="w-7 h-7 rounded-full bg-violet-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">
                                    {{ $idx + 1 }}
                                </span>
                                <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 leading-relaxed pt-0.5">
                                    {{ __($step) }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- FAQ Section -->
            @if (!empty($tool['faq']))
                <div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                        <i data-lucide="message-square" class="w-5 h-5 text-violet-500"></i>
                        <span>{{ __('Câu Hỏi Thường Gặp (FAQ)') }}</span>
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($tool['faq'] as $faqItem)
                            <div
                                class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-2">{{ __($faqItem['q']) }}
                                </h3>
                                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                                    {{ __($faqItem['a']) }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

    </div>

    <!-- Modal xác nhận nộp bài -->
    <div id="submitConfirmModal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div
            class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 max-w-md w-full border border-slate-200 dark:border-slate-800 shadow-2xl space-y-4">
            <div
                class="w-12 h-12 rounded-2xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 flex items-center justify-center mx-auto">
                <i data-lucide="alert-triangle" class="w-6 h-6"></i>
            </div>
            <div class="text-center space-y-1">
                <h3 id="submitModalTitle" class="text-base font-bold text-slate-900 dark:text-white">
                    {{ __('Bạn có chắc muốn nộp bài?') }}</h3>
                <p id="unansweredWarningText" class="text-xs text-slate-500">
                    {{ __('Bạn đã hoàn thành :answered/:total câu hỏi.', ['answered' => 0, 'total' => 0]) }}</p>
            </div>
            <div class="grid grid-cols-2 gap-3 pt-2">
                <button type="button" onclick="closeSubmitModal()"
                    class="py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-semibold hover:bg-slate-200 transition">
                    {{ __('Tiếp tục làm') }}
                </button>
                <button type="button" onclick="submitExamFinal()" id="submitModalBtn"
                    class="py-2.5 rounded-xl bg-violet-600 text-white text-xs font-bold hover:bg-violet-700 transition">
                    {{ __('Nộp bài ngay') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Chia Sẻ Đề Thi & Mã Đề -->
    <div id="shareQuizModal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div
            class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 max-w-lg w-full border border-slate-200 dark:border-slate-800 shadow-2xl space-y-6">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-2xl bg-violet-100 dark:bg-violet-950/60 text-violet-600 dark:text-violet-400 flex items-center justify-center">
                        <i data-lucide="share-2" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Chia Sẻ & Mã Đề Thi') }}
                        </h3>
                        <p class="text-xs text-slate-500">{{ __('Mã đề giúp người khác mở làm trên mọi thiết bị') }}</p>
                    </div>
                </div>
                <button type="button" onclick="closeShareModal()"
                    class="p-2 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Tiêu đề đề thi đang chia sẻ -->
            <div
                class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60 flex items-center justify-between gap-2">
                <span class="text-xs font-semibold text-slate-700 dark:text-slate-300 truncate"
                    id="shareModalExamTitle">{{ __('Đề Thi Trắc Nghiệm') }}</span>
                <span id="shareModalVisibilityBadge"
                    class="text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 flex-shrink-0">
                    {{ __('Công Khai') }}
                </span>
            </div>

            <!-- Hộp hiển thị Mã Đề to rõ -->
            <div
                class="p-5 rounded-2xl bg-gradient-to-br from-violet-50 to-indigo-50 dark:from-violet-950/40 dark:to-indigo-950/40 border border-violet-200 dark:border-violet-800 text-center space-y-2">
                <span
                    class="text-xs font-bold tracking-wider text-violet-700 dark:text-violet-300 uppercase">{{ __('Mã Đề Thi (Quiz Code)') }}</span>
                <div class="flex items-center justify-center gap-3">
                    <span id="shareModalCodeDisplay"
                        class="text-3xl sm:text-4xl font-black font-mono tracking-widest text-violet-600 dark:text-violet-400 select-all">ZT-XXXXXX</span>
                    <button type="button" onclick="copyShareCode()"
                        class="p-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white transition shadow-sm"
                        title="{{ __('Sao chép mã đề:') }}">
                        <i data-lucide="copy" class="w-4 h-4"></i>
                    </button>
                </div>
                <p class="text-[11px] text-slate-500 dark:text-slate-400">
                    {{ __('Người nhận chỉ cần nhập mã này vào ô tìm mã đề ở đầu trang để làm.') }}</p>
            </div>

            <!-- Hộp Link chia sẻ trực tiếp -->
            <div class="space-y-1.5">
                <label
                    class="block text-xs font-semibold text-slate-700 dark:text-slate-300">{{ __('Link mở trực tiếp bài thi:') }}</label>
                <div class="flex items-center gap-2">
                    <input type="text" id="shareModalUrlInput" readonly
                        class="flex-1 text-xs p-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-200 font-mono outline-none select-all">
                    <button type="button" onclick="copyShareLink()" id="btnCopyShareLink"
                        class="px-3.5 py-3 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold transition flex items-center gap-1.5 flex-shrink-0 shadow-sm">
                        <i data-lucide="copy" class="w-4 h-4"></i>
                        <span id="btnCopyShareLinkText">{{ __('Sao Chép Link') }}</span>
                    </button>
                </div>
            </div>

            <!-- Khu vực chuyển đổi quyền riêng tư / Lưu bản sao -->
            <div id="shareModalOwnerActions"
                class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="space-y-0.5 min-w-0 flex-1">
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block"
                        id="shareModalOwnerTitle">{{ __('Quyền riêng tư đề thi') }}</span>
                    <p id="shareModalVisDesc" class="text-[11px] text-slate-500 leading-tight">
                        {{ __('Đang ở chế độ Công Khai (Bất kỳ ai có mã đều xem được).') }}</p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <button type="button" onclick="toggleCurrentQuizVisibility()" id="btnToggleVisInModal"
                        class="px-3.5 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs font-bold transition flex items-center gap-1.5 flex-shrink-0 shadow-sm">
                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                        <span id="btnToggleVisText">{{ __('Đổi sang Riêng Tư') }}</span>
                    </button>
                    <button type="button" onclick="saveQuizCopyToServer()" id="btnCloneQuizInModal"
                        class="hidden px-3.5 py-2 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold transition flex items-center gap-1.5 flex-shrink-0 shadow-sm">
                        <i data-lucide="bookmark-plus" class="w-3.5 h-3.5"></i>
                        <span>{{ __('Lưu Bản Sao Vào Server') }}</span>
                    </button>
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="button" onclick="closeShareModal()"
                    class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold transition">
                    {{ __('Đóng') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Quản Lý Bộ Đề Của Tôi (My Quizzes) -->
    <div id="myQuizzesModal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div
            class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 max-w-2xl w-full border border-slate-200 dark:border-slate-800 shadow-2xl space-y-6 max-h-[90vh] flex flex-col">
            <div
                class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-2xl bg-indigo-100 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                        <i data-lucide="folder-kanban" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">
                            {{ __('Bộ Đề Của Tôi (Lưu Trên Server)') }}</h3>
                        <p class="text-xs text-slate-500">
                            {{ __('Được đồng bộ với tài khoản, lưu vĩnh viễn và không bị mất') }}</p>
                    </div>
                </div>
                <button type="button" onclick="closeMyQuizzesModal()"
                    class="p-2 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Danh sách đề -->
            <div id="myQuizzesListContainer" class="flex-1 overflow-y-auto space-y-3 pr-1">
                <div class="text-center py-8 text-slate-400 text-xs">
                    <div class="inline-block animate-spin text-violet-600 mb-2">
                        <i data-lucide="loader-2" class="w-6 h-6"></i>
                    </div>
                    <p>{{ __('Đang tải danh sách đề thi...') }}</p>
                </div>
            </div>

            <div
                class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-800 flex-shrink-0">
                <span id="myQuizzesCountBadge" class="text-xs text-slate-500 font-medium">{{ __('0 đề') }}</span>
                <button type="button" onclick="closeMyQuizzesModal()"
                    class="px-5 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold transition">
                    {{ __('Đóng') }}
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Server Configuration & Initial Data
            const INITIAL_QUIZ = @json($initialQuiz);
            const IS_LOGGED_IN = {{ Auth::check() ? 'true' : 'false' }};
            const CURRENT_USER_ID = {{ Auth::check() ? Auth::id() : 'null' }};
            const LOGIN_URL = @json(route('login', ['redirect' => request()->getRequestUri()]));
            const SAVE_QUIZ_URL = @json(route('tool.quiz.save'));
            const LOAD_QUIZ_URL = @json(url('/tool/trac-nghiem/load'));
            const VISIBILITY_URL = @json(route('tool.quiz.visibility'));
            const MY_QUIZZES_URL = @json(route('tool.quiz.my_quizzes'));
            const DELETE_QUIZ_URL = @json(route('tool.quiz.delete'));
            const CSRF_TOKEN = '{{ csrf_token() }}';

            // Internationalization dictionary
            const I18N = {
                locale: '{{ app()->getLocale() }}',
                defaultExamTitle: @json(__('Bài Thi Trắc Nghiệm')),
                btnPracticeStart: @json(__('Bắt Đầu Ôn Tập')),
                btnExamStart: @json(__('Bắt Đầu Thi Thử')),
                btnPracticeSubmit: @json(__('Kết Thúc Ôn Tập')),
                btnExamSubmit: @json(__('Nộp Bài')),
                palettePracticeSubmit: @json(__('Xem Bảng Điểm')),
                paletteExamSubmit: @json(__('Nộp Bài & Xem Điểm')),
                visPublic: @json(__('Công Khai')),
                visPrivate: @json(__('Riêng Tư')),
                visPublicDesc: @json(__('Đang ở chế độ Công Khai (Bất kỳ ai có mã đều xem được).')),
                visPrivateDesc: @json(__('Đang ở chế độ Riêng Tư (Chỉ bạn mới có quyền xem).')),
                btnSwitchToPrivate: @json(__('Đổi sang Riêng Tư')),
                btnSwitchToPublic: @json(__('Đổi sang Công Khai')),
                copied: @json(__('Đã chép!')),
                copyLink: @json(__('Sao Chép Link')),
                loadingQuizzesServer: @json(__('Đang tải danh sách đề thi trên Server...')),
                loadingParsing: @json(__('Đang đọc tài liệu và phân tích câu hỏi...')),
                noQuizzesOnServer: @json(__('Chưa có đề thi nào trên Server')),
                noQuizzesOnServerDesc: @json(__('Khi bạn tải file hoặc làm bài, đề thi sẽ tự động được lưu trữ và hiển thị tại đây.')),
                noSavedExams: @json(__('Chưa có đề thi nào được lưu')),
                noSavedExamsDesc: @json(__(
                        'Khi bạn tải file hoặc thử đề mẫu, đề thi sẽ tự động được lưu tại đây để lần sau mở làm lại ngay mà không cần tải lại file.')),
                questionsCount: @json(__(':count câu')),
                attemptsCount: @json(__(':count lượt làm')),
                openQuiz: @json(__('Mở Đề')),
                viewShareCodeLink: @json(__('Xem mã & link chia sẻ')),
                deleteQuizFromServer: @json(__('Xóa đề này khỏi server')),
                deleteExamFromList: @json(__('Xóa đề này khỏi danh sách')),
                retakeThisExam: @json(__('Làm Lại Đề Này')),
                settings: @json(__('Cài đặt')),
                notTakenYet: @json(__('Chưa làm bài')),
                scoreLabel: @json(__('Điểm:')),
                questionsInDoc: @json(__('(Tài liệu có :count câu)')),
                questionsFromCode: @json(__('(Đã nạp :count câu từ mã :code)')),
                questionsFromSaved: @json(__('(Đã nạp :count câu từ đề đã lưu)')),
                onlyPdfSupported: @json(__(
                        'Hệ thống chỉ hỗ trợ tải lên file PDF. Nếu bạn có file Word (.docx), vui lòng lưu sang định dạng PDF (chọn File > Save as > PDF trong Word) hoặc chuyển sang tab "Dán Văn Bản" để dán trực tiếp nội dung đề thi.')),
                selectedPdf: @json(__('Đã chọn file PDF:')),
                fileSizeReady: @json(__('Dung lượng: :size MB • Sẵn sàng tạo đề thi')),
                pleaseSelectFileOrText: @json(__('Vui lòng chọn 1 file PDF đề thi hoặc dán văn bản câu hỏi vào tab "Dán Văn Bản", hoặc bấm "Thử Đề Mẫu Ngay".')),
                fileSizeTooLarge: @json(__('Dung lượng file tải lên quá lớn, vui lòng chọn file nhỏ hơn.')),
                sessionExpired: @json(__('Phiên làm việc đã hết hạn. Vui lòng làm mới trang (F5) và thử lại.')),
                serverError: @json(__('Máy chủ phản hồi mã :status. Vui lòng thử lại.')),
                cannotExtract: @json(__('Không thể trích xuất câu hỏi từ tài liệu này.')),
                stepDetectingQuestions: @json(__('Đang nhận diện các câu hỏi trắc nghiệm...')),
                stepMatchingAnswers: @json(__('Đang đối chiếu đáp án & giải thích...')),
                stepFinalizingExam: @json(__('Đang hoàn tất chuẩn bị bài thi...')),
                completedProgress: @json(__('Đã làm: :answered/:total câu (:percent%)')),
                timeUpAlert: @json(__('Hết giờ làm bài! Hệ thống sẽ tự động nộp bài và chấm điểm.')),
                confirmSubmitPracticeTitle: @json(__('Kết thúc ôn tập & xem bảng điểm?')),
                confirmSubmitPracticeBtn: @json(__('Xem bảng điểm ngay')),
                confirmSubmitExamTitle: @json(__('Bạn có chắc muốn nộp bài?')),
                confirmSubmitExamBtn: @json(__('Nộp bài ngay')),
                completedQuestionsWarning: @json(__('Bạn đã hoàn thành :answered/:total câu hỏi.')),
                unansweredQuestionsWarning: @json(__(' Còn :count câu chưa chọn đáp án!')),
                rankExcellent: @json(__('XUẤT SẮC 🏆')),
                rankVeryGood: @json(__('GIỎI 🎉')),
                rankGood: @json(__('KHÁ 👍')),
                rankNeedsImprovement: @json(__('CẦN CỐ GẮNG')),
                correctBadge: @json(__('Chính xác')),
                userChoiceBadge: @json(__('Bạn chọn')),
                correctAnswerBadge: @json(__('Đáp án đúng')),
                userSelectedThis: @json(__('Lựa chọn của bạn')),
                correctAnswerPrompt: @json(__('Bạn đã trả lời chính xác!')),
                wrongAnswerPrompt: @json(__('Chưa chính xác! Đáp án đúng là: :correct')),
                explanationLabel: @json(__('💡 Giải thích:')),
                explanationTitle: @json(__('Giải thích:')),
                correctStat: @json(__('Đúng')),
                wrongStat: @json(__('Sai (Bạn chọn: :choice)')),
                emptyChoice: @json(__('Bỏ trống')),
                noWrongQuestionsAlert: @json(__('Tuyệt vời! Bạn không có câu nào làm sai trong bài kiểm tra này.')),
                retakeWrongTitle: @json(__('[Ôn Luyện Lại] Các Câu Làm Sai (:count Câu)')),
                promptEnterQuizCode: @json(__('Vui lòng nhập Mã Đề Thi (Ví dụ: ZT-A1B2C3).')),
                loadingCodeText: @json(__('Đang nạp...')),
                openQuizBtnText: @json(__('Mở Đề Thi')),
                openQuizSuccess: @json(__('🎉 Mở đề thi thành công: ":title" (:count câu)!\nBạn có thể tùy chỉnh số câu & thời gian rồi bấm Bắt Đầu.')),
                errorLoadingQuiz: @json(__('Lỗi kết nối khi tải đề thi:')),
                pleaseUploadBeforeShare: @json(__('Vui lòng tải file hoặc chọn đề thi trước khi lấy mã chia sẻ.')),
                copiedCode: @json(__('Đã sao chép mã đề: :code')),
                promptCopyCode: @json(__('Sao chép mã đề:')),
                promptCopyLink: @json(__('Sao chép liên kết:')),
                mustLoginForPrivate: @json(__('Bạn cần đăng nhập để lưu đề ở chế độ Riêng Tư.')),
                mustLoginToChangeVis: @json(__('Vui lòng đăng nhập để thay đổi quyền riêng tư của đề thi.')),
                confirmDeleteQuizServer: @json(__('Bạn có chắc chắn muốn xóa vĩnh viễn đề thi ":code" khỏi Server?')),
                quizDeletedSuccess: @json(__('Đã xóa đề thi thành công.')),
                cannotDeleteQuiz: @json(__('Không thể xóa đề thi.')),
                confirmDeleteSavedExam: @json(__('Bạn có chắc chắn muốn xóa đề thi này khỏi danh sách đã lưu?')),
                confirmClearAllSaved: @json(__('Bạn có chắc chắn muốn xóa toàn bộ danh sách đề thi đã lưu?')),
                privateModeLoginConfirm: @json(__(
                        'Chế độ Riêng Tư (Private) yêu cầu tài khoản để bảo mật và chỉ mình bạn mở được.\nBạn có muốn chuyển sang trang Đăng nhập ngay bây giờ?')),
                errorPrefix: @json(__('Lỗi: ')),
                practiceBadge: @json(__('Ôn Tập')),
                examBadge: @json(__('Thi Thử')),
                flagQuestionTitle: @json(__('Đánh dấu câu này để xem lại')),
                savedQuizzesCount: @json(__(':count đề thi đã lưu')),
                confirmGoLogin: @json(__('Bạn có muốn chuyển sang trang Đăng nhập ngay bây giờ?')),
                quizNotFound: @json(__('Không tìm thấy đề thi với mã: :code')),
                cannotLoadQuizList: @json(__('Không thể tải danh sách đề thi.')),
                connErrorPrefix: @json(__('Lỗi kết nối: ')),
                cannotChangeVis: @json(__('Không thể thay đổi quyền riêng tư.')),
                sampleExamDefaultTitle: @json(__('300 Câu Trắc Nghiệm Tư Tưởng Hồ Chí Minh')),
                sampleExamLoading: @json(__('Đang nạp đề mẫu 40 câu Tư tưởng Hồ Chí Minh...')),
                sampleExamError: @json(__('Lỗi nạp đề mẫu: ')),
                quizzesSuffix: @json(__('đề')),
                saveServerSuccess: @json(__('Đã lưu đề thi thành công vào Bộ Đề Của Tôi trên máy chủ (Mã: :code)!')),
                saveServerBtnText: @json(__('Lưu Lên Server')),
                savingText: @json(__('Đang lưu...')),
                pleaseUploadBeforeSave: @json(__('Vui lòng chọn hoặc tải đề thi trước khi lưu lên Server.')),
                confirmLoginToSaveServer: @json(__(
                        'Đăng nhập tài khoản để lưu đề thi vĩnh viễn trên Server và đồng bộ trên mọi thiết bị. Bạn có muốn đăng nhập ngay?')),
                quizSavedToAccountSuccess: @json(__('Đã lưu bản sao đề thi vào tài khoản của bạn thành công!')),
            };

            // State management
            let rawQuestions = [];
            let currentQuestions = [];
            let userAnswers = {}; // { questionId: 'A' }
            let flaggedQuestions = new Set();
            let currentExamTitle = I18N.defaultExamTitle;
            let currentQuizCode = null;
            let currentQuizIsPublic = true;
            let currentQuizIsOwner = false;
            let currentQuizShareUrl = null;
            let timerInterval = null;
            let totalExamSeconds = 0;
            let remainingSeconds = 0;
            let timeElapsed = 0;
            let currentFilter = 'all';
            let currentQuizMode = 'practice'; // 'practice' (Ôn tập) or 'exam' (Thi thử)
            let currentActiveQuizId = null;

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
                    if (practiceBtn) practiceBtn.className =
                        'p-3 rounded-2xl border-2 border-emerald-500 bg-emerald-50/70 dark:bg-emerald-950/40 text-left transition relative group';
                    if (practiceIcon) practiceIcon.className =
                        'w-6 h-6 rounded-lg bg-emerald-600 text-white flex items-center justify-center text-xs';
                    if (practiceTitle) practiceTitle.className = 'text-xs font-bold text-slate-900 dark:text-white';
                    if (practiceCheck) practiceCheck.classList.remove('hidden');

                    if (examBtn) examBtn.className =
                        'p-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 text-left hover:border-slate-300 dark:hover:border-slate-700 transition relative group';
                    if (examIcon) examIcon.className =
                        'w-6 h-6 rounded-lg bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 flex items-center justify-center text-xs';
                    if (examTitle) examTitle.className = 'text-xs font-bold text-slate-700 dark:text-slate-300';
                    if (examCheck) examCheck.classList.add('hidden');

                    if (startBtnText) startBtnText.innerText = I18N.btnPracticeStart;
                    if (examDuration && (examDuration.value === '30' || examDuration.value === '45')) {
                        examDuration.value = '0';
                    }
                } else {
                    if (examBtn) examBtn.className =
                        'p-3 rounded-2xl border-2 border-violet-600 bg-violet-50/70 dark:bg-violet-950/40 text-left transition relative group';
                    if (examIcon) examIcon.className =
                        'w-6 h-6 rounded-lg bg-violet-600 text-white flex items-center justify-center text-xs';
                    if (examTitle) examTitle.className = 'text-xs font-bold text-slate-900 dark:text-white';
                    if (examCheck) examCheck.classList.remove('hidden');

                    if (practiceBtn) practiceBtn.className =
                        'p-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 text-left hover:border-slate-300 dark:hover:border-slate-700 transition relative group';
                    if (practiceIcon) practiceIcon.className =
                        'w-6 h-6 rounded-lg bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 flex items-center justify-center text-xs';
                    if (practiceTitle) practiceTitle.className = 'text-xs font-bold text-slate-700 dark:text-slate-300';
                    if (practiceCheck) practiceCheck.classList.add('hidden');

                    if (startBtnText) startBtnText.innerText = I18N.btnExamStart;
                    if (examDuration && examDuration.value === '0') {
                        examDuration.value = '30';
                    }
                }
                lucide.createIcons();
            }

            // Switch Quiz Visibility: Công Khai (Public) vs Riêng Tư (Private)
            function setQuizVisibility(isPublic) {
                if (!isPublic && !IS_LOGGED_IN) {
                    const guestNotice = document.getElementById('visGuestNotice');
                    if (guestNotice) guestNotice.classList.remove('hidden');
                    if (confirm(I18N.privateModeLoginConfirm)) {
                        window.location.href = LOGIN_URL;
                    }
                    return;
                }

                currentQuizIsPublic = isPublic;
                const pubBtn = document.getElementById('visPublicBtn');
                const privBtn = document.getElementById('visPrivateBtn');
                const pubCheck = document.getElementById('visPublicCheck');
                const privCheck = document.getElementById('visPrivateCheck');
                const statusBadge = document.getElementById('visStatusBadge');
                const guestNotice = document.getElementById('visGuestNotice');

                if (guestNotice) guestNotice.classList.add('hidden');

                if (isPublic) {
                    if (pubBtn) pubBtn.className =
                        'p-2.5 rounded-2xl border-2 border-violet-600 bg-violet-50/70 dark:bg-violet-950/40 text-left transition relative';
                    if (pubCheck) pubCheck.classList.remove('hidden');
                    if (privBtn) privBtn.className =
                        'p-2.5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 text-left hover:border-slate-300 dark:hover:border-slate-700 transition relative';
                    if (privCheck) privCheck.classList.add('hidden');
                    if (statusBadge) {
                        statusBadge.innerText = I18N.visPublic;
                        statusBadge.className =
                            'text-[10px] px-2 py-0.5 rounded-full bg-violet-100 dark:bg-violet-950/60 text-violet-700 dark:text-violet-300 font-bold';
                    }
                } else {
                    if (privBtn) privBtn.className =
                        'p-2.5 rounded-2xl border-2 border-violet-600 bg-violet-50/70 dark:bg-violet-950/40 text-left transition relative';
                    if (privCheck) privCheck.classList.remove('hidden');
                    if (pubBtn) pubBtn.className =
                        'p-2.5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 text-left hover:border-slate-300 dark:hover:border-slate-700 transition relative';
                    if (pubCheck) pubCheck.classList.add('hidden');
                    if (statusBadge) {
                        statusBadge.innerText = I18N.visPrivate;
                        statusBadge.className =
                            'text-[10px] px-2 py-0.5 rounded-full bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 font-bold';
                    }
                }
                lucide.createIcons();
            }

            // Save Quiz to server
            async function saveQuizToServer(isPublic = null) {
                if (!rawQuestions || rawQuestions.length === 0) return null;

                const visibility = (isPublic !== null) ? isPublic : currentQuizIsPublic;
                if (!visibility && !IS_LOGGED_IN) {
                    alert(I18N.mustLoginForPrivate);
                    return null;
                }

                try {
                    const resp = await fetch(SAVE_QUIZ_URL, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: JSON.stringify({
                            title: currentExamTitle || 'Bài Thi Trắc Nghiệm',
                            questions: rawQuestions,
                            is_public: visibility,
                            code: currentQuizCode || null
                        })
                    });

                    const data = await resp.json();
                    if (data.success && data.code) {
                        currentQuizCode = data.code;
                        currentQuizShareUrl = data.share_url;
                        currentQuizIsOwner = data.is_owner;
                        currentQuizIsPublic = data.is_public;

                        updateQuizCodeUI(currentQuizCode);

                        if (window.history && window.history.replaceState) {
                            const newUrl = `${window.location.pathname}?code=${currentQuizCode}`;
                            window.history.replaceState(null, '', newUrl);
                        }

                        saveExamToHistory(currentExamTitle, rawQuestions, currentQuizCode);
                        return data;
                    } else {
                        if (data.require_login) {
                            if (confirm((data.error || '') + '\n' + I18N.confirmGoLogin)) {
                                window.location.href = LOGIN_URL;
                            }
                        } else {
                            console.warn(I18N.errorPrefix, data.error);
                        }
                        return null;
                    }
                } catch (e) {
                    console.error('Error saving quiz to server:', e);
                    return null;
                }
            }

            function updateQuizCodeUI(code) {
                if (!code) return;
                const codeInput = document.getElementById('quickQuizCodeInput');
                if (codeInput && !codeInput.value) codeInput.value = code;

                const headerCode = document.getElementById('activeQuizCodeBadge');
                if (headerCode) headerCode.innerText = code;

                const shareCodeDisp = document.getElementById('shareModalCodeDisplay');
                if (shareCodeDisp) shareCodeDisp.innerText = code;

                const shareUrlInput = document.getElementById('shareModalUrlInput');
                if (shareUrlInput) {
                    const currentUrl = `${window.location.origin}${window.location.pathname}?code=${code}`;
                    shareUrlInput.value = currentUrl;
                }
            }

            // Load Quiz by Code (from input or param)
            async function handleLoadQuizByCode(codeToLoad = null) {
                let code = codeToLoad;
                if (!code) {
                    const input = document.getElementById('quickQuizCodeInput');
                    code = input ? input.value.trim() : '';
                }
                code = code.toUpperCase();

                if (!code) {
                    alert(I18N.promptEnterQuizCode);
                    return;
                }

                const btn = document.getElementById('btnQuickLoadCode');
                const btnText = document.getElementById('btnQuickLoadCodeText');
                if (btnText) btnText.innerText = I18N.loadingCodeText;
                if (btn) btn.disabled = true;

                try {
                    const resp = await fetch(`${LOAD_QUIZ_URL}/${encodeURIComponent(code)}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    const data = await resp.json();

                    if (!resp.ok || !data.success) {
                        if (data.require_login) {
                            if (confirm((data.error || '') + '\n' + I18N.confirmGoLogin)) {
                                window.location.href = LOGIN_URL;
                            }
                        } else {
                            alert(data.error || I18N.quizNotFound.replace(':code', code));
                        }
                        return;
                    }

                    // Load successful
                    rawQuestions = data.questions;
                    currentExamTitle = data.title;
                    currentQuizCode = data.code;
                    currentQuizIsPublic = data.is_public;
                    currentQuizIsOwner = data.is_owner;
                    currentActiveQuizId = saveExamToHistory(currentExamTitle, rawQuestions, currentQuizCode);

                    updateQuizCodeUI(currentQuizCode);

                    const badge = document.getElementById('detectedQuestionsBadge');
                    if (badge) {
                        badge.innerText = I18N.questionsFromCode.replace(':count', rawQuestions.length).replace(':code',
                            currentQuizCode);
                        badge.classList.remove('hidden');
                    }

                    setQuizVisibility(currentQuizIsPublic);
                    const settingsCard = document.getElementById('examSettingsCard');
                    if (settingsCard) {
                        settingsCard.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }

                    alert(I18N.openQuizSuccess.replace(':title', currentExamTitle).replace(':count', rawQuestions.length));

                } catch (err) {
                    alert(I18N.errorLoadingQuiz + ' ' + err.message);
                } finally {
                    if (btnText) btnText.innerText = I18N.openQuizBtnText;
                    if (btn) btn.disabled = false;
                }
            }

            // Share modal handlers
            async function openShareModal(specificCode = null, specificTitle = null, specificPublic = null, specificIsOwner =
                null) {
                if (specificCode) currentQuizCode = specificCode;
                if (specificTitle) currentExamTitle = specificTitle;
                if (specificPublic !== null) currentQuizIsPublic = specificPublic;
                if (specificIsOwner !== null) currentQuizIsOwner = specificIsOwner;

                let code = specificCode || currentQuizCode;
                let title = specificTitle || currentExamTitle;
                let isPublic = (specificPublic !== null) ? specificPublic : currentQuizIsPublic;

                if (!code && rawQuestions && rawQuestions.length > 0) {
                    const saved = await saveQuizToServer();
                    if (saved && saved.code) {
                        code = saved.code;
                        isPublic = saved.is_public;
                        currentQuizIsOwner = saved.is_owner;
                    }
                }

                if (!code) {
                    alert(I18N.pleaseUploadBeforeShare);
                    return;
                }

                const modal = document.getElementById('shareQuizModal');
                const codeDisplay = document.getElementById('shareModalCodeDisplay');
                const urlInput = document.getElementById('shareModalUrlInput');
                const titleDisplay = document.getElementById('shareModalExamTitle');
                const visBadge = document.getElementById('shareModalVisibilityBadge');
                const visDesc = document.getElementById('shareModalVisDesc');
                const btnToggleVis = document.getElementById('btnToggleVisText');
                const btnToggleVisBtn = document.getElementById('btnToggleVisInModal');
                const btnCloneBtn = document.getElementById('btnCloneQuizInModal');
                const ownerActions = document.getElementById('shareModalOwnerActions');
                const ownerTitle = document.getElementById('shareModalOwnerTitle');

                if (codeDisplay) codeDisplay.innerText = code;
                if (titleDisplay) titleDisplay.innerText = title || I18N.defaultExamTitle;
                if (urlInput) {
                    urlInput.value = `${window.location.origin}${window.location.pathname}?code=${code}`;
                }

                if (visBadge) {
                    visBadge.innerText = isPublic ? I18N.visPublic : I18N.visPrivate;
                    visBadge.className = isPublic ?
                        'text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 flex-shrink-0' :
                        'text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 flex-shrink-0';
                }

                if (visDesc) {
                    visDesc.innerText = isPublic ? I18N.visPublicDesc : I18N.visPrivateDesc;
                }

                if (btnToggleVis) {
                    btnToggleVis.innerText = isPublic ? I18N.btnSwitchToPrivate : I18N.btnSwitchToPublic;
                }

                if (ownerActions) {
                    ownerActions.classList.toggle('hidden', !IS_LOGGED_IN);
                    if (currentQuizIsOwner) {
                        if (ownerTitle) ownerTitle.innerText = @json(__('Quyền riêng tư đề thi'));
                        if (btnToggleVisBtn) btnToggleVisBtn.classList.remove('hidden');
                        if (btnCloneBtn) btnCloneBtn.classList.add('hidden');
                    } else {
                        if (ownerTitle) ownerTitle.innerText = @json(__('Lưu bản sao về tài khoản'));
                        if (visDesc) visDesc.innerText = @json(__('Bạn đang xem đề thi của người khác. Bạn có thể lưu một bản sao riêng vào Bộ Đề Của Tôi để toàn quyền quản lý.'));
                        if (btnToggleVisBtn) btnToggleVisBtn.classList.add('hidden');
                        if (btnCloneBtn) btnCloneBtn.classList.remove('hidden');
                    }
                }

                if (modal) {
                    modal.classList.remove('hidden');
                    lucide.createIcons();
                }
            }

            function closeShareModal() {
                const modal = document.getElementById('shareQuizModal');
                if (modal) modal.classList.add('hidden');
            }

            function copyShareCode() {
                const code = document.getElementById('shareModalCodeDisplay')?.innerText;
                if (!code) return;
                navigator.clipboard.writeText(code).then(() => {
                    alert(I18N.copiedCode.replace(':code', code));
                }).catch(() => {
                    prompt(I18N.promptCopyCode, code);
                });
            }

            function copyShareLink() {
                const input = document.getElementById('shareModalUrlInput');
                if (!input || !input.value) return;
                navigator.clipboard.writeText(input.value).then(() => {
                    const btnText = document.getElementById('btnCopyShareLinkText');
                    if (btnText) {
                        btnText.innerText = I18N.copied;
                        setTimeout(() => {
                            btnText.innerText = I18N.copyLink;
                        }, 2000);
                    }
                }).catch(() => {
                    prompt(I18N.promptCopyLink, input.value);
                });
            }

            async function toggleCurrentQuizVisibility() {
                if (!currentQuizCode) return;
                if (!IS_LOGGED_IN) {
                    alert(I18N.mustLoginToChangeVis);
                    window.location.href = LOGIN_URL;
                    return;
                }

                const newVis = !currentQuizIsPublic;
                try {
                    const resp = await fetch(VISIBILITY_URL, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: JSON.stringify({
                            code: currentQuizCode,
                            is_public: newVis
                        })
                    });

                    const data = await resp.json();
                    if (data.success) {
                        currentQuizIsPublic = data.is_public;
                        if (data.cloned && data.code) {
                            currentQuizCode = data.code;
                            currentQuizIsOwner = true;
                            updateQuizCodeUI(currentQuizCode);
                            saveExamToHistory(currentExamTitle, rawQuestions, currentQuizCode);
                        }
                        setQuizVisibility(currentQuizIsPublic);
                        openShareModal(currentQuizCode, currentExamTitle, currentQuizIsPublic, true);
                        alert(data.message);
                    } else {
                        alert(data.error || I18N.cannotChangeVis);
                    }
                } catch (e) {
                    alert(I18N.errorPrefix + e.message);
                }
            }

            // Save a separate copy of a shared quiz to current user's account
            async function saveQuizCopyToServer() {
                if (!rawQuestions || rawQuestions.length === 0) return;
                if (!IS_LOGGED_IN) {
                    window.location.href = LOGIN_URL;
                    return;
                }

                try {
                    const resp = await fetch(SAVE_QUIZ_URL, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: JSON.stringify({
                            title: currentExamTitle || 'Bài Thi Trắc Nghiệm',
                            questions: rawQuestions,
                            is_public: true,
                            code: null // Forces creation of personal copy
                        })
                    });

                    const data = await resp.json();
                    if (data.success && data.code) {
                        currentQuizCode = data.code;
                        currentQuizIsOwner = true;
                        currentQuizIsPublic = data.is_public;
                        updateQuizCodeUI(currentQuizCode);
                        saveExamToHistory(currentExamTitle, rawQuestions, currentQuizCode);
                        openShareModal(currentQuizCode, currentExamTitle, currentQuizIsPublic, true);
                        alert(I18N.quizSavedToAccountSuccess);
                    } else {
                        alert(data.error || 'Không thể lưu bản sao.');
                    }
                } catch (e) {
                    alert(I18N.errorPrefix + e.message);
                }
            }

            // Explicit manual save of currently active exam to server
            async function manualSaveCurrentQuizToServer() {
                if (!rawQuestions || rawQuestions.length === 0) {
                    alert(I18N.pleaseUploadBeforeSave);
                    return;
                }

                if (!IS_LOGGED_IN) {
                    if (confirm(I18N.confirmLoginToSaveServer)) {
                        window.location.href = LOGIN_URL;
                        return;
                    }
                }

                const btnText = document.getElementById('manualSaveServerBtnText');
                const origText = btnText ? btnText.innerText : '';
                if (btnText) btnText.innerText = I18N.savingText;

                try {
                    const saved = await saveQuizToServer();
                    if (saved && saved.code) {
                        alert(I18N.saveServerSuccess.replace(':code', saved.code));
                    }
                } finally {
                    if (btnText) btnText.innerText = origText;
                }
            }

            // Save an item from LocalStorage saved list directly to server
            async function saveSavedExamToServer(id) {
                const list = getSavedExams();
                const quiz = list.find(item => item.id === id);
                if (!quiz || !quiz.questions || quiz.questions.length === 0) return;

                if (!IS_LOGGED_IN) {
                    if (confirm(I18N.confirmLoginToSaveServer)) {
                        window.location.href = LOGIN_URL;
                        return;
                    }
                }

                try {
                    const resp = await fetch(SAVE_QUIZ_URL, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: JSON.stringify({
                            title: quiz.title,
                            questions: quiz.questions,
                            is_public: true,
                            code: quiz.code || null
                        })
                    });

                    const data = await resp.json();
                    if (data.success && data.code) {
                        quiz.code = data.code;
                        localStorage.setItem('ziitool_saved_quizzes', JSON.stringify(list));
                        if (currentActiveQuizId === id) {
                            currentQuizCode = data.code;
                            currentQuizIsOwner = data.is_owner;
                            updateQuizCodeUI(data.code);
                        }
                        renderSavedExamsList();
                        alert(I18N.saveServerSuccess.replace(':code', data.code));
                    } else {
                        alert(data.error || 'Không thể lưu đề thi lên server.');
                    }
                } catch (e) {
                    alert(I18N.errorPrefix + e.message);
                }
            }

            // My Quizzes Modal handlers
            async function openMyQuizzesModal() {
                if (!IS_LOGGED_IN) {
                    window.location.href = LOGIN_URL;
                    return;
                }

                const modal = document.getElementById('myQuizzesModal');
                const container = document.getElementById('myQuizzesListContainer');
                const badge = document.getElementById('myQuizzesCountBadge');
                if (modal) modal.classList.remove('hidden');

                if (container) {
                    container.innerHTML = `
                <div class="text-center py-8 text-slate-400 text-xs">
                    <div class="inline-block animate-spin text-violet-600 mb-2">
                        <i data-lucide="loader-2" class="w-6 h-6"></i>
                    </div>
                    <p>${I18N.loadingQuizzesServer}</p>
                </div>
            `;
                    lucide.createIcons();
                }

                try {
                    const resp = await fetch(MY_QUIZZES_URL, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    const data = await resp.json();

                    if (data.success && data.quizzes) {
                        renderMyQuizzesList(data.quizzes);
                        if (badge) badge.innerText = I18N.savedQuizzesCount.replace(':count', data.quizzes.length);
                    } else {
                        if (container) container.innerHTML =
                            `<p class="text-center py-6 text-rose-500 text-xs">${I18N.cannotLoadQuizList}</p>`;
                    }
                } catch (e) {
                    if (container) container.innerHTML =
                        `<p class="text-center py-6 text-rose-500 text-xs">${I18N.connErrorPrefix}${escapeHtml(e.message)}</p>`;
                }
            }

            function closeMyQuizzesModal() {
                const modal = document.getElementById('myQuizzesModal');
                if (modal) modal.classList.add('hidden');
            }

            function renderMyQuizzesList(quizzes) {
                const container = document.getElementById('myQuizzesListContainer');
                if (!container) return;

                if (quizzes.length === 0) {
                    container.innerHTML = `
                <div class="text-center py-10 px-4 rounded-2xl border border-dashed border-slate-200 dark:border-slate-800 text-slate-400 text-xs space-y-2">
                    <i data-lucide="folder-open" class="w-8 h-8 mx-auto text-slate-300 dark:text-slate-600"></i>
                    <p class="font-bold text-slate-700 dark:text-slate-300 text-sm">${I18N.noQuizzesOnServer}</p>
                    <p class="text-[11px] text-slate-400">${I18N.noQuizzesOnServerDesc}</p>
                </div>
            `;
                    lucide.createIcons();
                    return;
                }

                container.innerHTML = '';
                quizzes.forEach(q => {
                    const item = document.createElement('div');
                    item.className =
                        'p-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 hover:border-violet-300 transition flex flex-col sm:flex-row sm:items-center justify-between gap-3';
                    item.innerHTML = `
                <div class="space-y-1 min-w-0 flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs font-mono font-bold px-2 py-0.5 rounded-md bg-violet-100 dark:bg-violet-950/60 text-violet-700 dark:text-violet-300">${escapeHtml(q.code)}</span>
                        <h4 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white truncate" title="${escapeHtml(q.title)}">${escapeHtml(q.title)}</h4>
                    </div>
                    <div class="flex items-center gap-3 text-[11px] text-slate-500 dark:text-slate-400 flex-wrap">
                        <span>${I18N.questionsCount.replace(':count', q.total_questions)}</span>
                        <span>•</span>
                        <span>${I18N.attemptsCount.replace(':count', q.attempts_count)}</span>
                        <span>•</span>
                        <span>${q.created_at}</span>
                        <span>•</span>
                        <span class="font-semibold ${q.is_public ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400'}">
                            ${q.is_public ? '🌐 ' + I18N.visPublic : '🔒 ' + I18N.visPrivate}
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0 flex-wrap">
                    <button type="button" onclick="loadMyQuiz('${q.code}')" class="px-3 py-1.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold transition flex items-center gap-1">
                        <i data-lucide="play" class="w-3.5 h-3.5"></i> ${I18N.openQuiz}
                    </button>
                    <button type="button" onclick="openShareModal('${q.code}', '${escapeHtml(q.title)}', ${q.is_public}, true)" class="px-2.5 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 text-slate-700 dark:text-slate-300 text-xs font-semibold transition" title="${I18N.viewShareCodeLink}">
                        <i data-lucide="share-2" class="w-3.5 h-3.5"></i>
                    </button>
                    <button type="button" onclick="deleteMyQuiz('${q.code}')" class="p-1.5 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition" title="${I18N.deleteQuizFromServer}">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            `;
                    container.appendChild(item);
                });

                lucide.createIcons();
            }

            async function loadMyQuiz(code) {
                closeMyQuizzesModal();
                currentQuizIsOwner = true;
                await handleLoadQuizByCode(code);
            }

            async function deleteMyQuiz(code) {
                if (!confirm(I18N.confirmDeleteQuizServer.replace(':code', code))) return;

                try {
                    const resp = await fetch(DELETE_QUIZ_URL, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: JSON.stringify({
                            code: code
                        })
                    });

                    const data = await resp.json();
                    if (data.success) {
                        try {
                            let saved = getSavedExams();
                            saved = saved.filter(item => item.code !== code);
                            localStorage.setItem('ziitool_saved_quizzes', JSON.stringify(saved));
                            renderSavedExamsList();
                        } catch (ignore) {}

                        if (currentQuizCode === code) {
                            currentQuizCode = null;
                            updateQuizCodeUI('');
                        }

                        alert(I18N.quizDeletedSuccess);
                        openMyQuizzesModal();
                    } else {
                        alert(data.error || I18N.cannotDeleteQuiz);
                    }
                } catch (e) {
                    alert(I18N.errorPrefix + e.message);
                }
            }

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

            function saveExamToHistory(title, questions, quizCode = null) {
                if (!questions || questions.length === 0) return null;
                try {
                    const saved = getSavedExams();
                    const existingIdx = saved.findIndex(item => (quizCode && item.code === quizCode) || (item.title === title &&
                        item.total_questions === questions.length));
                    const now = new Date();
                    const dateLocale = (I18N.locale === 'en' ? 'en-US' : 'vi-VN');
                    const dateStr = now.toLocaleDateString(dateLocale) + ' ' + now.toLocaleTimeString(dateLocale, {
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    const examItem = {
                        id: existingIdx >= 0 ? saved[existingIdx].id : 'quiz_' + Date.now(),
                        code: quizCode || (existingIdx >= 0 ? saved[existingIdx].code : currentQuizCode),
                        title: title || I18N.defaultExamTitle,
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

                    // Cap at 20 items
                    if (saved.length > 20) {
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
                        const dateLocale = (I18N.locale === 'en' ? 'en-US' : 'vi-VN');
                        item.updated_at = now.toLocaleDateString(dateLocale) + ' ' + now.toLocaleTimeString(dateLocale, {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        localStorage.setItem('ziitool_saved_quizzes', JSON.stringify(saved));
                        renderSavedExamsList();
                    }
                } catch (e) {
                    console.warn('Cannot update score in localStorage:', e);
                }
            }

            async function deleteSavedExam(id, e) {
                if (e) e.stopPropagation();
                if (!confirm(I18N.confirmDeleteSavedExam)) return;
                try {
                    let saved = getSavedExams();
                    const item = saved.find(q => q.id === id);
                    saved = saved.filter(item => item.id !== id);
                    localStorage.setItem('ziitool_saved_quizzes', JSON.stringify(saved));
                    if (currentActiveQuizId === id) {
                        currentActiveQuizId = null;
                    }
                    renderSavedExamsList();

                    // If item had a server code and user is logged in, optionally also delete on server
                    if (item && item.code && IS_LOGGED_IN) {
                        try {
                            await fetch(DELETE_QUIZ_URL, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': CSRF_TOKEN
                                },
                                body: JSON.stringify({
                                    code: item.code
                                })
                            });
                        } catch (ignore) {}
                    }
                } catch (e) {
                    console.warn(e);
                }
            }

            function clearAllSavedExams() {
                if (!confirm(I18N.confirmClearAllSaved)) return;
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
                currentQuizCode = quiz.code || null;
                currentQuizIsOwner = IS_LOGGED_IN;
                selectedUploadFile = null;
                hasNewUnparsedInput = false;
                const fileInput = document.getElementById('fileInput');
                if (fileInput) fileInput.value = '';

                updateQuizCodeUI(currentQuizCode);

                const badge = document.getElementById('detectedQuestionsBadge');
                if (badge) {
                    badge.innerText = I18N.questionsFromSaved.replace(':count', rawQuestions.length);
                    badge.classList.remove('hidden');
                }

                renderSavedExamsList(id);

                if (startImmediately) {
                    startExamSession();
                } else {
                    const settingsCard = document.getElementById('examSettingsCard');
                    if (settingsCard) {
                        settingsCard.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                }
            }

            function renderSavedExamsList(activeId = null) {
                const container = document.getElementById('savedExamsList');
                const badge = document.getElementById('savedExamsCountBadge');
                const clearBtn = document.getElementById('clearAllExamsBtn');
                if (!container) return;

                const saved = getSavedExams();
                if (badge) badge.innerText = `${saved.length} ${I18N.quizzesSuffix}`;

                if (saved.length === 0) {
                    if (clearBtn) clearBtn.classList.add('hidden');
                    container.innerHTML = `
                <div class="text-center py-6 px-4 rounded-2xl border border-dashed border-slate-200 dark:border-slate-800 text-slate-400 text-xs space-y-1">
                    <i data-lucide="inbox" class="w-6 h-6 mx-auto mb-1 text-slate-300 dark:text-slate-600"></i>
                    <p class="font-medium text-slate-600 dark:text-slate-400">${I18N.noSavedExams}</p>
                    <p class="text-[11px] text-slate-400">${I18N.noSavedExamsDesc}</p>
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
                    item.className =
                        `p-4 rounded-2xl border transition flex flex-col sm:flex-row sm:items-center justify-between gap-3 ${isCurrentActive ? 'border-2 border-violet-600 bg-violet-50/40 dark:bg-violet-950/20 shadow-sm' : 'border-slate-200 dark:border-slate-800 hover:border-violet-300 dark:hover:border-violet-700 bg-slate-50/50 dark:bg-slate-800/30'}`;

                    item.innerHTML = `
                <div class="space-y-1 flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        ${quiz.code ? `<span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded-md bg-violet-100 dark:bg-violet-950/60 text-violet-700 dark:text-violet-300">${quiz.code}</span>` : ''}
                        <h4 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white truncate" title="${escapeHtml(quiz.title)}">
                            ${escapeHtml(quiz.title)}
                        </h4>
                        <span class="text-[10px] sm:text-[11px] font-bold px-2 py-0.5 rounded-md bg-violet-100 dark:bg-violet-950/60 text-violet-700 dark:text-violet-300 flex-shrink-0">
                            ${I18N.questionsCount.replace(':count', quiz.total_questions)}
                        </span>
                    </div>
                    <div class="flex items-center gap-3 text-[11px] text-slate-500 dark:text-slate-400 flex-wrap">
                        <span class="flex items-center gap-1">
                            <i data-lucide="calendar" class="w-3 h-3 text-slate-400"></i> ${quiz.created_at}
                        </span>
                        ${quiz.last_score ? `
                                            <span class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-bold">
                                                <i data-lucide="award" class="w-3 h-3"></i> ${I18N.scoreLabel} ${quiz.last_score} ${quiz.last_rank ? '(' + quiz.last_rank + ')' : ''}
                                            </span>
                                        ` : `<span class="text-slate-400 italic">${I18N.notTakenYet}</span>`}
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0 flex-wrap">
                    <button type="button" onclick="loadSavedExam('${quiz.id}', true)" class="px-3.5 py-1.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                        <i data-lucide="play" class="w-3.5 h-3.5"></i> ${I18N.retakeThisExam}
                    </button>
                    <button type="button" onclick="saveSavedExamToServer('${quiz.id}')" class="p-1.5 rounded-xl bg-violet-50 dark:bg-violet-950/40 hover:bg-violet-100 dark:hover:bg-violet-900/60 text-violet-700 dark:text-violet-300 transition" title="${I18N.saveServerBtnText}">
                        <i data-lucide="cloud-upload" class="w-4 h-4"></i>
                    </button>
                    ${quiz.code ? `
                                        <button type="button" onclick="openShareModal('${quiz.code}', '${escapeHtml(quiz.title)}', true, true)" class="p-1.5 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 text-slate-600 dark:text-slate-300 transition" title="${I18N.viewShareCodeLink}">
                                            <i data-lucide="share-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    ` : ''}
                    <button type="button" onclick="loadSavedExam('${quiz.id}', false)" class="px-2.5 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-medium transition" title="${I18N.settings}">
                        ${I18N.settings}
                    </button>
                    <button type="button" onclick="deleteSavedExam('${quiz.id}', event)" class="p-1.5 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition" title="${I18N.deleteExamFromList}">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            `;
                    container.appendChild(item);
                });

                lucide.createIcons();
            }




            function switchInputTab(tab) {
                if (tab === 'file') {
                    document.getElementById('inputTabFile').classList.remove('hidden');
                    document.getElementById('inputTabText').classList.add('hidden');
                    document.getElementById('tabFileBtn').classList.add('bg-white', 'dark:bg-slate-700', 'text-slate-900',
                        'dark:text-white', 'shadow-sm');
                    document.getElementById('tabFileBtn').classList.remove('text-slate-500', 'dark:text-slate-400');
                    document.getElementById('tabTextBtn').classList.remove('bg-white', 'dark:bg-slate-700', 'text-slate-900',
                        'dark:text-white', 'shadow-sm');
                    document.getElementById('tabTextBtn').classList.add('text-slate-500', 'dark:text-slate-400');
                } else {
                    document.getElementById('inputTabFile').classList.add('hidden');
                    document.getElementById('inputTabText').classList.remove('hidden');
                    document.getElementById('tabTextBtn').classList.add('bg-white', 'dark:bg-slate-700', 'text-slate-900',
                        'dark:text-white', 'shadow-sm');
                    document.getElementById('tabTextBtn').classList.remove('text-slate-500', 'dark:text-slate-400');
                    document.getElementById('tabFileBtn').classList.remove('bg-white', 'dark:bg-slate-700', 'text-slate-900',
                        'dark:text-white', 'shadow-sm');
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
            let hasNewUnparsedInput = false;

            function handleFileSelected(files) {
                if (!files || files.length === 0) return;
                const file = files[0];
                const ext = file.name.split('.').pop().toLowerCase();
                if (ext !== 'pdf') {
                    alert(I18N.onlyPdfSupported);
                    const input = document.getElementById('fileInput');
                    if (input) input.value = '';
                    selectedUploadFile = null;
                    hasNewUnparsedInput = false;
                    return;
                }
                selectedUploadFile = file;
                hasNewUnparsedInput = true;
                document.getElementById('fileLabelTitle').innerHTML =
                    `${I18N.selectedPdf} <span class="text-violet-600 font-bold">${escapeHtml(selectedUploadFile.name)}</span>`;
                document.getElementById('fileLabelDesc').innerText = I18N.fileSizeReady.replace(':size', (selectedUploadFile
                    .size / 1024 / 1024).toFixed(2));
            }

            // Load sample exam
            async function loadSampleExam() {
                setParseLoading(true, I18N.sampleExamLoading);
                try {
                    const resp = await fetch('{{ route('tool.quiz.sample') }}');
                    const data = await resp.json();
                    if (data.success && data.questions && data.questions.length > 0) {
                        rawQuestions = data.questions;
                        currentExamTitle = data.title || I18N.sampleExamDefaultTitle;
                        currentActiveQuizId = saveExamToHistory(currentExamTitle, rawQuestions);
                        const badge = document.getElementById('detectedQuestionsBadge');
                        if (badge) {
                            badge.innerText = I18N.questionsInDoc.replace(':count', rawQuestions.length);
                            badge.classList.remove('hidden');
                        }
                        setParseLoading(false);
                        saveQuizToServer();
                        startExamSession();
                    } else {
                        throw new Error(data.error || 'Không tải được đề mẫu');
                    }
                } catch (err) {
                    setParseLoading(false);
                    alert(I18N.sampleExamError + err.message);
                }
            }

            // Start exam button handler
            async function handleStartExam() {
                // If an exam is already loaded into memory (e.g. from code ZT-..., saved history, or sample)
                // and no new file was explicitly uploaded, start exam session directly!
                if (rawQuestions && rawQuestions.length > 0 && !hasNewUnparsedInput) {
                    startExamSession();
                    return;
                }

                const textContent = document.getElementById('rawTextContent').value.trim();
                const model = 'gemini-flash-latest';
                const mode = 'auto';

                if (!selectedUploadFile && !textContent) {
                    if (rawQuestions && rawQuestions.length > 0) {
                        startExamSession();
                        return;
                    }
                    alert(I18N.pleaseSelectFileOrText);
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

                setParseLoading(true, I18N.loadingParsing);

                try {
                    const resp = await fetch('{{ route('tool.quiz.parse') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    let data;
                    const contentType = resp.headers.get('content-type') || '';
                    if (contentType.includes('application/json')) {
                        data = await resp.json();
                    } else {
                        throw new Error(resp.status === 413 ? I18N.fileSizeTooLarge : (resp.status === 419 ? I18N
                            .sessionExpired : I18N.serverError.replace(':status', resp.status)));
                    }

                    if (!resp.ok || !data.success) {
                        throw new Error(data.error || I18N.cannotExtract);
                    }

                    rawQuestions = data.questions;
                    hasNewUnparsedInput = false;
                    selectedUploadFile = null;
                    currentExamTitle = data.title || (selectedUploadFile ? selectedUploadFile.name : I18N.defaultExamTitle);
                    currentActiveQuizId = saveExamToHistory(currentExamTitle, rawQuestions);
                    const badge = document.getElementById('detectedQuestionsBadge');
                    if (badge) {
                        badge.innerText = I18N.questionsInDoc.replace(':count', rawQuestions.length);
                        badge.classList.remove('hidden');
                    }
                    setParseLoading(false);
                    saveQuizToServer();
                    startExamSession();

                } catch (err) {
                    setParseLoading(false);
                    alert(I18N.errorPrefix + err.message);
                }

            }

            let parseLoadingInterval = null;

            function setParseLoading(isLoading, text = '') {
                const btn = document.getElementById('startExamBtn');
                const loader = document.getElementById('parseLoadingStatus');
                const loaderText = document.getElementById('parseLoadingText');

                if (parseLoadingInterval) {
                    clearInterval(parseLoadingInterval);
                    parseLoadingInterval = null;
                }

                if (isLoading) {
                    btn.classList.add('hidden');
                    loader.classList.remove('hidden');
                    const initialText = text || I18N.loadingParsing;
                    loaderText.innerText = initialText;

                    const steps = [
                        initialText,
                        I18N.stepDetectingQuestions,
                        I18N.stepMatchingAnswers,
                        I18N.stepFinalizingExam
                    ];
                    let stepIndex = 0;
                    parseLoadingInterval = setInterval(() => {
                        stepIndex = (stepIndex + 1) % steps.length;
                        loaderText.innerText = steps[stepIndex];
                    }, 5000);
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
                        const optValues = optKeys.map(k => ({
                            key: k,
                            text: q.options[k],
                            isCorrect: k === q.correct
                        }));
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

                // Update Quiz Code in sticky header
                const headerCode = document.getElementById('activeQuizCodeBadge');
                if (headerCode) {
                    headerCode.innerText = currentQuizCode || 'ZT-...';
                }

                // Update mode badge & buttons in header
                const modeBadge = document.getElementById('activeModeBadge');
                const submitBtnText = document.getElementById('submitExamBtnText');
                const paletteSubmitText = document.getElementById('paletteSubmitText');
                const legendPractice = document.getElementById('paletteLegendPractice');
                const legendExam = document.getElementById('paletteLegendExam');

                if (currentQuizMode === 'practice') {
                    if (modeBadge) {
                        modeBadge.className =
                            'text-[10px] sm:text-xs font-bold px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 flex items-center gap-1';
                        modeBadge.innerHTML = `<i data-lucide="book-open" class="w-3 h-3"></i> ${I18N.practiceBadge}`;
                    }
                    if (submitBtnText) submitBtnText.innerText = I18N.btnPracticeSubmit;
                    if (paletteSubmitText) paletteSubmitText.innerText = I18N.palettePracticeSubmit;
                    if (legendPractice) legendPractice.classList.remove('hidden');
                    if (legendExam) legendExam.classList.add('hidden');
                } else {
                    if (modeBadge) {
                        modeBadge.className =
                            'text-[10px] sm:text-xs font-bold px-2 py-0.5 rounded-full bg-violet-100 dark:bg-violet-950/60 text-violet-700 dark:text-violet-300 flex items-center gap-1';
                        modeBadge.innerHTML = `<i data-lucide="timer" class="w-3 h-3"></i> ${I18N.examBadge}`;
                    }
                    if (submitBtnText) submitBtnText.innerText = I18N.btnExamSubmit;
                    if (paletteSubmitText) paletteSubmitText.innerText = I18N.paletteExamSubmit;
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
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
                lucide.createIcons();
            }

            // Render questions in exam
            function renderQuestionsList() {
                const container = document.getElementById('questionsContainer');
                container.innerHTML = '';

                currentQuestions.forEach((q, index) => {
                    const card = document.createElement('div');
                    card.id = `qCard_${q.id}`;
                    card.className =
                        'bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-800 shadow-sm space-y-5 transition';

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
                <button type="button" onclick="toggleFlagQuestion(${q.id})" id="flagBtn_${q.id}" class="text-slate-400 hover:text-amber-500 transition p-1.5 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800" title="${I18N.flagQuestionTitle}">
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
                        optBtn.className =
                            'flex items-center justify-between gap-3.5 p-3.5 sm:p-4 rounded-2xl border border-slate-200 dark:border-slate-800 hover:border-violet-400 dark:hover:border-violet-600 bg-slate-50/50 dark:bg-slate-800/40 cursor-pointer transition group';

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
                    btn.className =
                        'w-full aspect-square rounded-xl text-xs font-bold transition flex items-center justify-center bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700';
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
                            el.className =
                                'flex items-center justify-between gap-3.5 p-3.5 sm:p-4 rounded-2xl border-2 border-emerald-500 bg-emerald-50 dark:bg-emerald-950/40 cursor-pointer transition';
                            circle.className =
                                'w-7 h-7 rounded-lg bg-emerald-600 text-white font-bold text-xs flex items-center justify-center flex-shrink-0';
                            if (badgeContainer) {
                                badgeContainer.innerHTML =
                                    `<span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1"><i data-lucide="check" class="w-4 h-4"></i> ${I18N.correctBadge}</span>`;
                            }
                        } else if (isThisOptSelected && !isUserCorrect) {
                            // Selected and Wrong!
                            el.className =
                                'flex items-center justify-between gap-3.5 p-3.5 sm:p-4 rounded-2xl border-2 border-rose-500 bg-rose-50 dark:bg-rose-950/40 cursor-pointer transition';
                            circle.className =
                                'w-7 h-7 rounded-lg bg-rose-600 text-white font-bold text-xs flex items-center justify-center flex-shrink-0';
                            if (badgeContainer) {
                                badgeContainer.innerHTML =
                                    `<span class="text-xs font-bold text-rose-600 dark:text-rose-400 flex items-center gap-1"><i data-lucide="x" class="w-4 h-4"></i> ${I18N.userChoiceBadge}</span>`;
                            }
                        } else if (isThisOptOfficialCorrect) {
                            // Not selected by user, but this IS the official correct answer! Reveal it!
                            el.className =
                                'flex items-center justify-between gap-3.5 p-3.5 sm:p-4 rounded-2xl border-2 border-emerald-500 bg-emerald-50/70 dark:bg-emerald-950/30 cursor-pointer transition';
                            circle.className =
                                'w-7 h-7 rounded-lg bg-emerald-600 text-white font-bold text-xs flex items-center justify-center flex-shrink-0';
                            if (badgeContainer) {
                                badgeContainer.innerHTML =
                                    `<span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1"><i data-lucide="check-circle" class="w-4 h-4"></i> ${I18N.correctAnswerBadge}</span>`;
                            }
                        } else {
                            // Other options
                            el.className =
                                'flex items-center justify-between gap-3.5 p-3.5 sm:p-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-800/20 opacity-60 cursor-pointer transition';
                            circle.className =
                                'w-7 h-7 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-400 font-bold text-xs flex items-center justify-center flex-shrink-0';
                            if (badgeContainer) badgeContainer.innerHTML = '';
                        }
                    });

                    // Show explanation box immediately
                    const explainBox = document.getElementById(`explainBox_${qId}`);
                    if (explainBox) {
                        explainBox.className =
                            `p-4 rounded-2xl ${isUserCorrect ? 'bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-950 dark:text-emerald-200' : 'bg-rose-50/70 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800 text-rose-950 dark:text-rose-200'} text-xs leading-relaxed space-y-2 mt-2 transition`;
                        explainBox.innerHTML = `
                    <div class="flex items-center gap-2 font-bold text-xs sm:text-sm ${isUserCorrect ? 'text-emerald-700 dark:text-emerald-300' : 'text-rose-700 dark:text-rose-300'}">
                        <i data-lucide="${isUserCorrect ? 'check-circle' : 'alert-circle'}" class="w-4 h-4"></i>
                        <span>${isUserCorrect ? I18N.correctAnswerPrompt : I18N.wrongAnswerPrompt.replace(':correct', q.correct)}</span>
                    </div>
                    <div class="text-slate-700 dark:text-slate-300 pt-1">
                        <p><strong class="text-slate-900 dark:text-white">${I18N.correctAnswerBadge}:</strong> <strong>${q.correct}.</strong> ${escapeHtml(q.options[q.correct] || '')}</p>
                        ${q.explanation ? `<p class="mt-1 text-slate-600 dark:text-slate-400 italic"><strong class="not-italic text-slate-800 dark:text-slate-200">${I18N.explanationLabel}</strong> ${escapeHtml(q.explanation)}</p>` : ''}
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
                            el.className =
                                'flex items-center justify-between gap-3.5 p-3.5 sm:p-4 rounded-2xl border-2 border-violet-600 bg-violet-50/80 dark:bg-violet-950/40 cursor-pointer transition';
                            circle.className =
                                'w-7 h-7 rounded-lg bg-violet-600 text-white font-bold text-xs flex items-center justify-center flex-shrink-0';
                        } else {
                            el.className =
                                'flex items-center justify-between gap-3.5 p-3.5 sm:p-4 rounded-2xl border border-slate-200 dark:border-slate-800 hover:border-violet-400 dark:hover:border-violet-600 bg-slate-50/50 dark:bg-slate-800/40 cursor-pointer transition group';
                            circle.className =
                                'w-7 h-7 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs flex items-center justify-center flex-shrink-0 group-hover:bg-violet-600 group-hover:text-white group-hover:border-violet-600 transition';
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
                    flagBtn.className =
                        'text-slate-400 hover:text-amber-500 transition p-1.5 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800';
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
                    btn.className =
                        'w-full aspect-square rounded-xl text-xs font-bold transition flex items-center justify-center bg-amber-500 text-white shadow-sm';
                    return;
                }

                if (isAnswered) {
                    if (currentQuizMode === 'practice' && q) {
                        const isCorrect = (userAnswers[qId].toUpperCase() === q.correct.toUpperCase());
                        if (isCorrect) {
                            btn.className =
                                'w-full aspect-square rounded-xl text-xs font-bold transition flex items-center justify-center bg-emerald-500 text-white shadow-sm';
                        } else {
                            btn.className =
                                'w-full aspect-square rounded-xl text-xs font-bold transition flex items-center justify-center bg-rose-500 text-white shadow-sm';
                        }
                    } else {
                        btn.className =
                            'w-full aspect-square rounded-xl text-xs font-bold transition flex items-center justify-center bg-violet-600 text-white shadow-sm';
                    }
                } else {
                    btn.className =
                        'w-full aspect-square rounded-xl text-xs font-bold transition flex items-center justify-center bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700';
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

                document.getElementById('progressText').innerText = I18N.completedProgress.replace(':answered', answered)
                    .replace(':total', total).replace(':percent', percent);
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
                            document.getElementById('timerBadge').className =
                                'flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 text-xs sm:text-sm font-mono font-bold animate-pulse';
                        }
                    };

                    updateDisplay();

                    timerInterval = setInterval(() => {
                        remainingSeconds--;
                        timeElapsed++;
                        updateDisplay();

                        if (remainingSeconds <= 0) {
                            clearInterval(timerInterval);
                            alert(I18N.timeUpAlert);
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
                    if (modalTitle) modalTitle.innerText = I18N.confirmSubmitPracticeTitle;
                    if (modalBtn) modalBtn.innerText = I18N.confirmSubmitPracticeBtn;
                } else {
                    if (modalTitle) modalTitle.innerText = I18N.confirmSubmitExamTitle;
                    if (modalBtn) modalBtn.innerText = I18N.confirmSubmitExamBtn;
                }

                let warning = I18N.completedQuestionsWarning.replace(':answered', answered).replace(':total', total);
                if (unanswered > 0) {
                    warning += I18N.unansweredQuestionsWarning.replace(':count', unanswered);
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
                let rank = I18N.rankNeedsImprovement;
                let rankColor = 'bg-rose-500/20 text-rose-300';
                if (scorePercent >= 90) {
                    rank = I18N.rankExcellent;
                    rankColor = 'bg-emerald-500/20 text-emerald-300';
                } else if (scorePercent >= 80) {
                    rank = I18N.rankVeryGood;
                    rankColor = 'bg-indigo-500/20 text-indigo-300';
                } else if (scorePercent >= 65) {
                    rank = I18N.rankGood;
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

                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
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

                    const borderColor = isCorrect ?
                        'border-emerald-200 dark:border-emerald-900/60 bg-emerald-50/20 dark:bg-emerald-950/20' :
                        'border-rose-200 dark:border-rose-900/60 bg-rose-50/20 dark:bg-rose-950/20';

                    card.className = `p-6 sm:p-8 rounded-3xl border ${borderColor} space-y-5 transition`;

                    // Question Header
                    const statusBadge = isCorrect ?
                        `<span class="px-2.5 py-1 rounded-xl bg-emerald-100 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300 text-xs font-bold flex items-center gap-1">
                    <i data-lucide="check" class="w-3.5 h-3.5"></i> ${I18N.correctStat}
                   </span>` :
                        `<span class="px-2.5 py-1 rounded-xl bg-rose-100 dark:bg-rose-950/80 text-rose-700 dark:text-rose-300 text-xs font-bold flex items-center gap-1">
                    <i data-lucide="x" class="w-3.5 h-3.5"></i> ${I18N.wrongStat.replace(':choice', userChoice || I18N.emptyChoice)}
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

                        let optStyle =
                            'border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300';
                        let indicator = '';

                        if (isOfficialCorrect) {
                            optStyle =
                                'border-2 border-emerald-500 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-950 dark:text-emerald-200 font-semibold';
                            indicator =
                                `<span class="ml-auto text-xs font-bold text-emerald-600 flex items-center gap-1"><i data-lucide="check-circle" class="w-4 h-4"></i> ${I18N.correctAnswerBadge}</span>`;
                        } else if (isSelectedByUser && !isOfficialCorrect) {
                            optStyle =
                                'border-2 border-rose-500 bg-rose-50 dark:bg-rose-950/40 text-rose-950 dark:text-rose-200 font-semibold';
                            indicator =
                                `<span class="ml-auto text-xs font-bold text-rose-600 flex items-center gap-1"><i data-lucide="x-circle" class="w-4 h-4"></i> ${I18N.userSelectedThis}</span>`;
                        }

                        const optItem = document.createElement('div');
                        optItem.className =
                            `flex items-center gap-3.5 p-3.5 sm:p-4 rounded-2xl border ${optStyle} transition`;
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
                        expBox.className =
                            'p-4 rounded-2xl bg-violet-50/80 dark:bg-violet-950/40 border border-violet-200/80 dark:border-violet-800/60 text-xs text-violet-900 dark:text-violet-200 leading-relaxed flex items-start gap-2.5';
                        expBox.innerHTML = `
                    <i data-lucide="info" class="w-4 h-4 text-violet-600 dark:text-violet-400 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <span class="font-bold">${I18N.explanationTitle}</span> ${escapeHtml(q.explanation)}
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
                    b.classList.remove('bg-white', 'dark:bg-slate-700', 'text-slate-900', 'dark:text-white',
                        'shadow-sm');
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
                    alert(I18N.noWrongQuestionsAlert);
                    return;
                }

                currentExamTitle = I18N.retakeWrongTitle.replace(':count', wrongIds.length);
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
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
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

            // Boot initial state on page load
            document.addEventListener('DOMContentLoaded', () => {
                if (INITIAL_QUIZ && INITIAL_QUIZ.questions && INITIAL_QUIZ.questions.length > 0) {
                    rawQuestions = INITIAL_QUIZ.questions;
                    currentExamTitle = INITIAL_QUIZ.title;
                    currentQuizCode = INITIAL_QUIZ.code;
                    currentQuizIsPublic = INITIAL_QUIZ.is_public;
                    currentQuizIsOwner = INITIAL_QUIZ.is_owner;
                    currentActiveQuizId = saveExamToHistory(currentExamTitle, rawQuestions, currentQuizCode);

                    updateQuizCodeUI(currentQuizCode);
                    setQuizVisibility(currentQuizIsPublic);

                    const badge = document.getElementById('detectedQuestionsBadge');
                    if (badge) {
                        badge.innerText = I18N.questionsFromCode.replace(':count', rawQuestions.length).replace(':code',
                            currentQuizCode);
                        badge.classList.remove('hidden');
                    }
                } else {
                    renderSavedExamsList();
                }
            });
        </script>
    @endpush
@endsection
