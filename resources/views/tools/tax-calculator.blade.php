@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-indigo-600">Trang chủ</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span>{{ $categoryInfo['name'] ?? 'Tài chính' }}</span>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="text-slate-800 dark:text-slate-200 font-semibold">{{ $tool['title'] }}</span>
    </nav>

    <!-- Tool Header -->
    <div class="mb-8">
        <div class="flex flex-wrap items-center gap-2 mb-2">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">
                {{ $tool['title'] }}
            </h1>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-amber-100 dark:bg-amber-900/60 text-amber-700 dark:text-amber-300 font-semibold">
                Biểu Lũy Tiến 7 Bậc
            </span>
            <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 font-semibold">
                Quy Định Mới Nhất
            </span>
        </div>
        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 max-w-3xl">
            {{ $tool['short_desc'] }} Tự động tính các khoản đóng bảo hiểm (10.5%), giảm trừ gia cảnh và số thuế TNCN phải nộp chính xác từng đồng.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        
        <!-- Input Form (1 col) -->
        <div class="space-y-6">
            <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-5 shadow-sm">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="calculator" class="w-4 h-4 text-amber-500"></i> Thông Tin Thu Nhập
                </h3>

                <!-- Salary Input -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Mức Lương (VNĐ/tháng)</label>
                    <div class="relative">
                        <input type="number" id="salaryInput" value="25000000" step="500000" oninput="calculateTaxVietnam()" class="w-full pl-3.5 pr-12 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white font-mono font-bold text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none">
                        <span class="absolute right-3.5 top-3.5 text-xs text-slate-400 font-semibold">đ</span>
                    </div>
                    <div class="flex gap-1.5 mt-2 overflow-x-auto text-[11px]">
                        <button type="button" onclick="setPresetSalary(15000000)" class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-600 dark:text-slate-300">15Tr</button>
                        <button type="button" onclick="setPresetSalary(25000000)" class="px-2 py-0.5 rounded bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300 font-semibold">25Tr</button>
                        <button type="button" onclick="setPresetSalary(40000000)" class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-600 dark:text-slate-300">40Tr</button>
                        <button type="button" onclick="setPresetSalary(70000000)" class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-600 dark:text-slate-300">70Tr</button>
                    </div>
                </div>

                <!-- Dependents -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Số Người Phụ Thuộc</label>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="adjustDependents(-1)" class="w-10 h-10 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center justify-center font-bold text-base hover:bg-slate-100 dark:hover:bg-slate-800">-</button>
                        <input type="number" id="dependentsInput" value="0" min="0" max="20" oninput="calculateTaxVietnam()" class="w-20 text-center py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 font-bold text-sm">
                        <button type="button" onclick="adjustDependents(1)" class="w-10 h-10 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center justify-center font-bold text-base hover:bg-slate-100 dark:hover:bg-slate-800">+</button>
                        <span class="text-xs text-slate-400">người (4.4tr/người)</span>
                    </div>
                </div>

                <!-- Region Minimum wage for Insurance -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Vùng làm việc (Đóng bảo hiểm)</label>
                    <select id="regionSelect" onchange="calculateTaxVietnam()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs">
                        <option value="1">Vùng I (Hà Nội, TP.HCM: 4.960.000đ)</option>
                        <option value="2">Vùng II (Đà Nẵng, Cần Thơ: 4.410.000đ)</option>
                        <option value="3">Vùng III (3.860.000đ)</option>
                        <option value="4">Vùng IV (3.450.000đ)</option>
                    </select>
                </div>

                <div class="pt-2 text-[11px] text-slate-400 space-y-1 border-t border-slate-100 dark:border-slate-800">
                    <div>• Giảm trừ bản thân: <strong>11.000.000 đ</strong></div>
                    <div>• Giảm trừ phụ thuộc: <strong>4.400.000 đ/người</strong></div>
                    <div>• BHXH: <strong>8%</strong> | BHYT: <strong>1.5%</strong> | BHTN: <strong>1%</strong></div>
                </div>
            </div>

            <x-ad-banner slot="sidebar" />
        </div>

        <!-- Calculation Results (2 cols) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Summary Top Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                    <span class="text-[11px] font-bold text-slate-400 uppercase block mb-1">Lương Gross</span>
                    <span id="resGross" class="text-lg font-black text-slate-900 dark:text-white font-mono">25.000.000 đ</span>
                </div>
                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                    <span class="text-[11px] font-bold text-rose-500 uppercase block mb-1">Thuế TNCN Phải Nộp</span>
                    <span id="resTax" class="text-lg font-black text-rose-500 font-mono">1.135.000 đ</span>
                </div>
                <div class="p-5 rounded-2xl bg-gradient-to-br from-emerald-500/10 to-teal-500/10 border border-emerald-500/30">
                    <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 uppercase block mb-1">Lương Thực Nhận (Net)</span>
                    <span id="resNet" class="text-xl font-black text-emerald-600 font-mono">21.240.000 đ</span>
                </div>
            </div>

            <!-- Detailed Breakdown Table -->
            <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-4">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Chi Tiết Diễn Giải Khấu Trừ</h3>

                <div class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                    <div class="py-2.5 flex items-center justify-between">
                        <span class="text-slate-600 dark:text-slate-400">1. Lương Gross ban đầu:</span>
                        <span id="tblGross" class="font-bold font-mono">25.000.000 đ</span>
                    </div>
                    <div class="py-2.5 flex items-center justify-between text-slate-500">
                        <span>2. Bảo hiểm bắt buộc (Tổng 10.5%):</span>
                        <span id="tblInsurance" class="font-mono text-rose-500">-2.625.000 đ</span>
                    </div>
                    <div class="py-2.5 flex items-center justify-between pl-4 text-slate-400 text-[11px]">
                        <span>• BHXH (8%):</span>
                        <span id="tblBhxh" class="font-mono">-2.000.000 đ</span>
                    </div>
                    <div class="py-2.5 flex items-center justify-between pl-4 text-slate-400 text-[11px]">
                        <span>• BHYT (1.5%):</span>
                        <span id="tblBhyt" class="font-mono">-375.000 đ</span>
                    </div>
                    <div class="py-2.5 flex items-center justify-between pl-4 text-slate-400 text-[11px]">
                        <span>• BHTN (1%):</span>
                        <span id="tblBhtn" class="font-mono">-250.000 đ</span>
                    </div>
                    <div class="py-2.5 flex items-center justify-between">
                        <span class="text-slate-600 dark:text-slate-400">3. Thu nhập sau khi trừ bảo hiểm:</span>
                        <span id="tblAfterInsurance" class="font-bold font-mono">22.375.000 đ</span>
                    </div>
                    <div class="py-2.5 flex items-center justify-between text-slate-500">
                        <span>4. Giảm trừ gia cảnh:</span>
                        <span id="tblDeductions" class="font-mono text-emerald-600">-11.000.000 đ</span>
                    </div>
                    <div class="py-2.5 flex items-center justify-between">
                        <span class="font-bold text-slate-700 dark:text-slate-300">5. Thu nhập tính thuế (Taxable):</span>
                        <span id="tblTaxable" class="font-bold font-mono text-indigo-600">11.375.000 đ</span>
                    </div>
                </div>

                <!-- 7 Brackets Detail -->
                <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-2">Số Thuế Đóng Theo Từng Bậc (Lũy tiến 7 bậc)</span>
                    <div id="bracketsContainer" class="space-y-1.5 font-mono text-[11px]"></div>
                </div>
            </div>

            <x-ad-banner slot="in_tool" />

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
    function formatVND(num) {
        return Math.round(num).toLocaleString('vi-VN') + ' đ';
    }

    function setPresetSalary(val) {
        document.getElementById('salaryInput').value = val;
        calculateTaxVietnam();
    }

    function adjustDependents(delta) {
        const input = document.getElementById('dependentsInput');
        let current = parseInt(input.value) || 0;
        current = Math.max(0, current + delta);
        input.value = current;
        calculateTaxVietnam();
    }

    function calculateTaxVietnam() {
        const gross = parseFloat(document.getElementById('salaryInput').value) || 0;
        const dependents = parseInt(document.getElementById('dependentsInput').value) || 0;

        // Insurance calculation with wage caps (mức trần đóng bảo hiểm)
        // Mức lương cơ sở = 2.340.000đ (Trần BHXH/BHYT = 20 lần = 46.800.000đ)
        const maxWageBhxh = 46800000;
        const wageForBhxh = Math.min(gross, maxWageBhxh);
        const bhxh = wageForBhxh * 0.08;
        const bhyt = wageForBhxh * 0.015;

        // Trần BHTN = 20 lần lương tối thiểu vùng I (4.960.000 x 20 = 99.200.000đ)
        const maxWageBhtn = 99200000;
        const wageForBhtn = Math.min(gross, maxWageBhtn);
        const bhtn = wageForBhtn * 0.01;

        const totalInsurance = bhxh + bhyt + bhtn;
        const afterInsurance = Math.max(0, gross - totalInsurance);

        const selfDeduction = 11000000;
        const dependentDeduction = dependents * 4400000;
        const totalDeductions = selfDeduction + dependentDeduction;

        const taxableIncome = Math.max(0, afterInsurance - totalDeductions);

        // 7 Tax Brackets
        const brackets = [
            { name: 'Bậc 1: Đến 5 triệu', limit: 5000000, rate: 0.05 },
            { name: 'Bậc 2: Trên 5 đến 10 triệu', limit: 5000000, rate: 0.10 },
            { name: 'Bậc 3: Trên 10 đến 18 triệu', limit: 8000000, rate: 0.15 },
            { name: 'Bậc 4: Trên 18 đến 32 triệu', limit: 14000000, rate: 0.20 },
            { name: 'Bậc 5: Trên 32 đến 52 triệu', limit: 20000000, rate: 0.25 },
            { name: 'Bậc 6: Trên 52 đến 80 triệu', limit: 28000000, rate: 0.30 },
            { name: 'Bậc 7: Trên 80 triệu', limit: Infinity, rate: 0.35 }
        ];

        let totalTax = 0;
        let remTaxable = taxableIncome;
        let bracketHtml = '';

        brackets.forEach((b, idx) => {
            if (remTaxable <= 0) {
                bracketHtml += `<div class="flex justify-between text-slate-400 py-1"><span>${b.name} (${b.rate*100}%):</span><span>0 đ</span></div>`;
                return;
            }
            const taxableInThis = Math.min(remTaxable, b.limit);
            const taxInThis = taxableInThis * b.rate;
            totalTax += taxInThis;
            remTaxable -= taxableInThis;

            bracketHtml += `
                <div class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-800">
                    <span class="text-slate-700 dark:text-slate-300">${b.name} (${b.rate*100}%):</span>
                    <span class="font-bold text-rose-500">${formatVND(taxInThis)}</span>
                </div>
            `;
        });

        const net = gross - totalInsurance - totalTax;

        // Render UI
        document.getElementById('resGross').innerText = formatVND(gross);
        document.getElementById('resTax').innerText = formatVND(totalTax);
        document.getElementById('resNet').innerText = formatVND(net);

        document.getElementById('tblGross').innerText = formatVND(gross);
        document.getElementById('tblInsurance').innerText = '-' + formatVND(totalInsurance);
        document.getElementById('tblBhxh').innerText = '-' + formatVND(bhxh);
        document.getElementById('tblBhyt').innerText = '-' + formatVND(bhyt);
        document.getElementById('tblBhtn').innerText = '-' + formatVND(bhtn);
        document.getElementById('tblAfterInsurance').innerText = formatVND(afterInsurance);
        document.getElementById('tblDeductions').innerText = '-' + formatVND(totalDeductions);
        document.getElementById('tblTaxable').innerText = formatVND(taxableIncome);

        document.getElementById('bracketsContainer').innerHTML = bracketHtml;
    }

    window.addEventListener('DOMContentLoaded', calculateTaxVietnam);
</script>
@endpush
@endsection
