@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-6">
            <a href="{{ route('home') }}" class="hover:text-indigo-600">Trang chủ</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span>{{ $categoryInfo['name'] ?? 'Developer' }}</span>
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
                    class="text-xs px-2.5 py-0.5 rounded-full bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 font-semibold">
                    {{ $tool['badge'] ?? 'Web Crypto API Chuẩn Quốc Tế' }}
                </span>
                <span
                    class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 font-semibold">
                    100% Bảo Mật Cục Bộ
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 max-w-3xl">
                {{ $tool['short_desc'] }} Mã hóa an toàn văn bản và tệp tin, tính toán mã băm mật mã học ngay trên thiết bị
                của bạn.
            </p>
        </div>

        <!-- Mode Selector Tabs -->
        <div class="flex gap-2 p-1 rounded-2xl bg-slate-200/60 dark:bg-slate-800 text-xs font-semibold max-w-md mb-6">
            <button type="button" onclick="switchTab('text')" id="tabBtn_text"
                class="flex-1 py-2.5 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs transition text-center">
                Base64 Văn Bản
            </button>
            <button type="button" onclick="switchTab('file')" id="tabBtn_file"
                class="flex-1 py-2.5 rounded-xl text-slate-500 hover:text-slate-900 dark:hover:text-white transition text-center">
                File Sang Base64
            </button>
            <button type="button" onclick="switchTab('hash')" id="tabBtn_hash"
                class="flex-1 py-2.5 rounded-xl text-slate-500 hover:text-slate-900 dark:hover:text-white transition text-center">
                Mã Băm (Hash)
            </button>
        </div>

        <!-- TAB 1: Base64 Text -->
        <div id="panel_text" class="space-y-6 mb-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <span class="font-bold text-xs text-slate-700 dark:text-slate-300">Văn Bản Gốc (Text)</span>
                    <textarea id="rawTextInput" placeholder="Nhập chuỗi văn bản cần mã hóa hoặc dán chuỗi Base64 để giải mã..."
                        class="w-full h-80 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 font-mono text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                </div>
                <div class="space-y-2">
                    <span class="font-bold text-xs text-indigo-600 dark:text-indigo-400">Kết Quả Base64</span>
                    <textarea id="base64Result" readonly placeholder="Kết quả mã hóa/giải mã..."
                        class="w-full h-80 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/60 text-indigo-600 dark:text-indigo-400 font-mono text-xs focus:outline-none"></textarea>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <button type="button" onclick="encodeBase64()"
                    class="px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
                    <i data-lucide="lock" class="w-4 h-4"></i> Mã Hóa Base64
                </button>
                <button type="button" onclick="decodeBase64()"
                    class="px-5 py-3 rounded-xl bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
                    <i data-lucide="unlock" class="w-4 h-4"></i> Giải Mã Base64
                </button>
                <button type="button" onclick="copyText(document.getElementById('base64Result').value)"
                    class="px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    Sao Chép Kết Quả
                </button>
            </div>
        </div>

        <!-- TAB 2: Base64 File -->
        <div id="panel_file" class="hidden space-y-6 mb-12">
            <div onclick="document.getElementById('b64FileInput').click()"
                class="p-8 sm:p-12 rounded-3xl border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-indigo-500 bg-white dark:bg-slate-900 text-center cursor-pointer transition group">
                <input type="file" id="b64FileInput" class="hidden" onchange="convertFileToBase64(this.files[0])">
                <div
                    class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center group-hover:scale-110 transition">
                    <i data-lucide="file-up" class="w-7 h-7"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 mb-1">Chọn tệp tin bất kỳ</h3>
                <p class="text-xs text-slate-400">Hình ảnh, Icon SVG, File PDF, Font chữ, Âm thanh</p>
            </div>

            <div id="fileResultBox" class="hidden space-y-3">
                <div class="flex items-center justify-between text-xs text-slate-500">
                    <span class="font-bold text-slate-700 dark:text-slate-300" id="fileDataStats">Chuỗi Base64 Data
                        URI</span>
                    <button type="button" onclick="copyText(document.getElementById('fileB64Output').value)"
                        class="text-indigo-600 font-semibold hover:underline">Sao Chép Toàn Bộ</button>
                </div>
                <textarea id="fileB64Output" readonly
                    class="w-full h-64 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/60 text-slate-800 dark:text-slate-200 font-mono text-xs focus:outline-none"></textarea>
            </div>
        </div>

        <!-- TAB 3: Hash Generator -->
        <div id="panel_hash" class="hidden space-y-6 mb-12">
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Chuỗi cần băm (Input
                    String)</label>
                <input type="text" id="hashInput" oninput="generateAllHashes(this.value)"
                    placeholder="Nhập văn bản cần tạo mã băm..."
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-mono text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>

            <div class="space-y-3">
                <!-- SHA-256 -->
                <div
                    class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-between gap-4">
                    <div class="min-w-0 flex-1">
                        <span class="text-[10px] uppercase font-bold text-emerald-600 block">SHA-256 (Chuẩn bảo mật
                            cao)</span>
                        <span id="sha256Display"
                            class="font-mono text-xs text-slate-800 dark:text-slate-200 break-all select-all block">e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855</span>
                    </div>
                    <button type="button" onclick="copyText(document.getElementById('sha256Display').innerText)"
                        class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold shrink-0">Copy</button>
                </div>

                <!-- SHA-512 -->
                <div
                    class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-between gap-4">
                    <div class="min-w-0 flex-1">
                        <span class="text-[10px] uppercase font-bold text-blue-600 block">SHA-512 (512-bit Hash)</span>
                        <span id="sha512Display"
                            class="font-mono text-xs text-slate-800 dark:text-slate-200 break-all select-all block">cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e</span>
                    </div>
                    <button type="button" onclick="copyText(document.getElementById('sha512Display').innerText)"
                        class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold shrink-0">Copy</button>
                </div>

                <!-- SHA-1 -->
                <div
                    class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-between gap-4">
                    <div class="min-w-0 flex-1">
                        <span class="text-[10px] uppercase font-bold text-purple-600 block">SHA-1 (Git Hash)</span>
                        <span id="sha1Display"
                            class="font-mono text-xs text-slate-800 dark:text-slate-200 break-all select-all block">da39a3ee5e6b4b0d3255bfef95601890afd80709</span>
                    </div>
                    <button type="button" onclick="copyText(document.getElementById('sha1Display').innerText)"
                        class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold shrink-0">Copy</button>
                </div>

                <!-- MD5 -->
                <div
                    class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-between gap-4">
                    <div class="min-w-0 flex-1">
                        <span class="text-[10px] uppercase font-bold text-amber-600 block">MD5 (Checksum phổ biến)</span>
                        <span id="md5Display"
                            class="font-mono text-xs text-slate-800 dark:text-slate-200 break-all select-all block">d41d8cd98f00b204e9800998ecf8427e</span>
                    </div>
                    <button type="button" onclick="copyText(document.getElementById('md5Display').innerText)"
                        class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold shrink-0">Copy</button>
                </div>
            </div>
        </div>

        <!-- In-Tool Ad Banner -->
        <x-ad-banner placement="in_tool" class="mb-12" />

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
            function switchTab(tab) {
                ['text', 'file', 'hash'].forEach(t => {
                    document.getElementById(`panel_${t}`).classList.add('hidden');
                    document.getElementById(`tabBtn_${t}`).className =
                        'flex-1 py-2.5 rounded-xl text-slate-500 hover:text-slate-900 dark:hover:text-white transition text-center';
                });
                document.getElementById(`panel_${tab}`).classList.remove('hidden');
                document.getElementById(`tabBtn_${tab}`).className =
                    'flex-1 py-2.5 rounded-xl bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs transition text-center';
                lucide.createIcons();
            }

            // UTF-8 safe Base64 encode
            function encodeBase64() {
                const text = document.getElementById('rawTextInput').value;
                if (!text) {
                    showToast('Vui lòng nhập văn bản!', 'error');
                    return;
                }
                try {
                    const encoded = btoa(unescape(encodeURIComponent(text)));
                    document.getElementById('base64Result').value = encoded;
                    showToast('Mã hóa Base64 thành công!', 'success');
                } catch (e) {
                    showToast('Lỗi khi mã hóa!', 'error');
                }
            }

            // UTF-8 safe Base64 decode
            function decodeBase64() {
                const text = document.getElementById('rawTextInput').value.trim();
                if (!text) return;
                try {
                    const decoded = decodeURIComponent(escape(atob(text)));
                    document.getElementById('base64Result').value = decoded;
                    showToast('Giải mã Base64 thành công!', 'success');
                } catch (e) {
                    showToast('Chuỗi Base64 không hợp lệ!', 'error');
                }
            }

            function convertFileToBase64(file) {
                if (!file) return;
                const reader = new FileReader();
                reader.onload = (e) => {
                    document.getElementById('fileB64Output').value = e.target.result;
                    document.getElementById('fileDataStats').innerText =
                        `${file.name} (${Math.round(e.target.result.length/1024)} KB)`;
                    document.getElementById('fileResultBox').classList.remove('hidden');
                    showToast('Đã trích xuất Base64 Data URI!', 'success');
                };
                reader.readAsDataURL(file);
            }

            // Native Web Crypto API for SHA algorithms
            async function hashString(str, algorithm) {
                const encoder = new TextEncoder();
                const data = encoder.encode(str);
                const hashBuffer = await crypto.subtle.digest(algorithm, data);
                const hashArray = Array.from(new Uint8Array(hashBuffer));
                return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
            }

            // Client-side MD5 implementation
            function md5Cycle(x, k) {
                var a = x[0],
                    b = x[1],
                    c = x[2],
                    d = x[3];
                a = ff(a, b, c, d, k[0], 7, -680876936);
                d = ff(d, a, b, c, k[1], 12, -389564586);
                c = ff(c, d, a, b, k[2], 17, 606105819);
                b = ff(b, c, d, a, k[3], 22, -1044525330);
                a = ff(a, b, c, d, k[4], 7, -176418897);
                d = ff(d, a, b, c, k[5], 12, 1200080426);
                c = ff(c, d, a, b, k[6], 17, -1473231341);
                b = ff(b, c, d, a, k[7], 22, -45705983);
                a = ff(a, b, c, d, k[8], 7, 1770035416);
                d = ff(d, a, b, c, k[9], 12, -1958414417);
                c = ff(c, d, a, b, k[10], 17, -42063);
                b = ff(b, c, d, a, k[11], 22, -1990404162);
                a = ff(a, b, c, d, k[12], 7, 1804603682);
                d = ff(d, a, b, c, k[13], 12, -40341101);
                c = ff(c, d, a, b, k[14], 17, -1502002290);
                b = ff(b, c, d, a, k[15], 22, 1236535329);

                a = gg(a, b, c, d, k[1], 5, -165796510);
                d = gg(d, a, b, c, k[6], 9, -1069501632);
                c = gg(c, d, a, b, k[11], 14, 643717713);
                b = gg(b, c, d, a, k[0], 20, -373897302);
                a = gg(a, b, c, d, k[5], 5, -701558691);
                d = gg(d, a, b, c, k[10], 9, 38016083);
                c = gg(c, d, a, b, k[15], 14, -660478335);
                b = gg(b, c, d, a, k[4], 20, -405537848);
                a = gg(a, b, c, d, k[9], 5, 568446438);
                d = gg(d, a, b, c, k[14], 9, -1019803690);
                c = gg(c, d, a, b, k[3], 14, -187363961);
                b = gg(b, c, d, a, k[8], 20, 1163531501);
                a = gg(a, b, c, d, k[13], 5, -1444681467);
                d = gg(d, a, b, c, k[2], 9, -51403784);
                c = gg(c, d, a, b, k[7], 14, 1735328473);
                b = gg(b, c, d, a, k[12], 20, -1926607734);

                a = hh(a, b, c, d, k[5], 4, -378558);
                d = hh(d, a, b, c, k[8], 11, -2022574463);
                c = hh(c, d, a, b, k[11], 16, 1839030562);
                b = hh(b, c, d, a, k[14], 23, -35309556);
                a = hh(a, b, c, d, k[1], 4, -1530992060);
                d = hh(d, a, b, c, k[4], 11, 1272893353);
                c = hh(c, d, a, b, k[7], 16, -155497632);
                b = hh(b, c, d, a, k[10], 23, -1094730640);
                a = hh(a, b, c, d, k[13], 4, 681279174);
                d = hh(d, a, b, c, k[0], 11, -358537222);
                c = hh(c, d, a, b, k[3], 16, -722521979);
                b = hh(b, c, d, a, k[6], 23, 76029189);
                a = hh(a, b, c, d, k[9], 4, -640364487);
                d = hh(d, a, b, c, k[12], 11, -421815835);
                c = hh(c, d, a, b, k[15], 16, 530742520);
                b = hh(b, c, d, a, k[2], 23, -995338651);

                a = ii(a, b, c, d, k[0], 6, -198630844);
                d = ii(d, a, b, c, k[7], 10, 1126891415);
                c = ii(c, d, a, b, k[14], 15, -1416354905);
                b = ii(b, c, d, a, k[5], 21, -57434055);
                a = ii(a, b, c, d, k[12], 6, 1700485571);
                d = ii(d, a, b, c, k[3], 10, -1894986606);
                c = ii(c, d, a, b, k[10], 15, -1051523);
                b = ii(b, c, d, a, k[1], 21, -2054922799);
                a = ii(a, b, c, d, k[8], 6, 1873313359);
                d = ii(d, a, b, c, k[15], 10, -30611744);
                c = ii(c, d, a, b, k[6], 15, -1560198380);
                b = ii(b, c, d, a, k[13], 21, 1309151649);
                a = ii(a, b, c, d, k[4], 6, -145523070);
                d = ii(d, a, b, c, k[11], 10, -1120210379);
                c = ii(c, d, a, b, k[2], 15, 718787259);
                b = ii(b, c, d, a, k[9], 21, -343485551);

                x[0] = add32(a, x[0]);
                x[1] = add32(b, x[1]);
                x[2] = add32(c, x[2]);
                x[3] = add32(d, x[3]);
            }

            function cmn(q, a, b, x, s, t) {
                a = add32(add32(a, q), add32(x, t));
                return add32((a << s) | (a >>> (32 - s)), b);
            }

            function ff(a, b, c, d, x, s, t) {
                return cmn((b & c) | ((~b) & d), a, b, x, s, t);
            }

            function gg(a, b, c, d, x, s, t) {
                return cmn((b & d) | (c & (~d)), a, b, x, s, t);
            }

            function hh(a, b, c, d, x, s, t) {
                return cmn(b ^ c ^ d, a, b, x, s, t);
            }

            function ii(a, b, c, d, x, s, t) {
                return cmn(c ^ (b | (~d)), a, b, x, s, t);
            }

            function add32(a, b) {
                return (a + b) & 0xFFFFFFFF;
            }

            function md5(s) {
                var txt = unescape(encodeURIComponent(s));
                var n = txt.length,
                    state = [1732584193, -271733879, -1732584194, 271733878],
                    i;
                var tail = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                for (i = 64; i <= n; i += 64) {
                    for (var j = 0; j < 16; j++) {
                        tail[j] = txt.charCodeAt(i - 64 + (j * 4)) | (txt.charCodeAt(i - 64 + (j * 4) + 1) << 8) | (txt
                            .charCodeAt(i - 64 + (j * 4) + 2) << 16) | (txt.charCodeAt(i - 64 + (j * 4) + 3) << 24);
                    }
                    md5Cycle(state, tail);
                }
                tail = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                var rem = n % 64;
                for (i = 0; i < rem; i++) tail[i >> 2] |= txt.charCodeAt(n - rem + i) << ((i % 4) << 3);
                tail[rem >> 2] |= 0x80 << ((rem % 4) << 3);
                if (rem > 55) {
                    md5Cycle(state, tail);
                    tail = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                }
                tail[14] = n * 8;
                md5Cycle(state, tail);
                return state.map(b => (b < 0 ? b + 0x100000000 : b).toString(16).padStart(8, '0').match(/../g).reverse().join(
                    '')).join('');
            }

            async function generateAllHashes(val) {
                if (!val) {
                    val = '';
                }
                document.getElementById('sha256Display').innerText = await hashString(val, 'SHA-256');
                document.getElementById('sha512Display').innerText = await hashString(val, 'SHA-512');
                document.getElementById('sha1Display').innerText = await hashString(val, 'SHA-1');
                document.getElementById('md5Display').innerText = md5(val);
            }
        </script>
    @endpush
@endsection
