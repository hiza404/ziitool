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
                <span
                    class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 font-semibold">
                    {{ $tool['badge'] ?? 'Kỳ Quan Thứ 8' }}
                </span>
                <span
                    class="text-xs px-2.5 py-0.5 rounded-full bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 font-semibold">
                    Biểu Đồ Trực Quan
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 max-w-3xl">
                {{ $tool['short_desc'] }} Xem trước số tiền bạn sẽ tích lũy được trong tương lai thông qua sức mạnh tái đầu
                tư lợi nhuận.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">

            <!-- Input Form (1 col) -->
            <div class="space-y-6">
                <div
                    class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-5 shadow-sm">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i data-lucide="trending-up" class="w-4 h-4 text-emerald-500"></i> Kế Hoạch Đầu Tư
                    </h3>

                    <!-- Principal -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Số Vốn Ban Đầu
                            (đ)</label>
                        <input type="number" id="initPrincipal" value="50000000" step="1000000"
                            oninput="calculateInterest()"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white font-mono font-bold text-xs">
                    </div>

                    <!-- Monthly Deposit -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Gửi Góp Định Kỳ Hàng
                            Tháng (đ)</label>
                        <input type="number" id="monthlyDeposit" value="5000000" step="500000"
                            oninput="calculateInterest()"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white font-mono font-bold text-xs">
                    </div>

                    <!-- Annual Rate -->
                    <div>
                        <div
                            class="flex items-center justify-between text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            <span>Lãi Suất Kỳ Vọng (%/năm)</span>
                            <span id="rateLabel" class="text-emerald-600 font-mono">10%</span>
                        </div>
                        <input type="range" id="annualRate" min="1" max="25" value="10" step="0.5"
                            oninput="document.getElementById('rateLabel').innerText = this.value + '%'; calculateInterest()"
                            class="w-full accent-emerald-600">
                        <div class="flex justify-between text-[10px] text-slate-400 mt-1">
                            <span>Tiết kiệm (6%)</span>
                            <span>Quỹ đầu tư (12%)</span>
                            <span>Cổ phiếu (18%)</span>
                        </div>
                    </div>

                    <!-- Years -->
                    <div>
                        <div
                            class="flex items-center justify-between text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            <span>Thời Gian Đầu Tư</span>
                            <span id="yearsLabel" class="text-indigo-600 font-mono">10 Năm</span>
                        </div>
                        <input type="range" id="investYears" min="1" max="40" value="10"
                            oninput="document.getElementById('yearsLabel').innerText = this.value + ' Năm'; calculateInterest()"
                            class="w-full accent-indigo-600">
                        <div class="grid grid-cols-4 gap-1.5 mt-2 text-[10px] font-semibold text-center">
                            <button type="button" onclick="setYears(5)"
                                class="py-1 rounded bg-slate-100 dark:bg-slate-800 hover:bg-slate-200">5 năm</button>
                            <button type="button" onclick="setYears(10)"
                                class="py-1 rounded bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 font-bold">10
                                năm</button>
                            <button type="button" onclick="setYears(20)"
                                class="py-1 rounded bg-slate-100 dark:bg-slate-800 hover:bg-slate-200">20 năm</button>
                            <button type="button" onclick="setYears(30)"
                                class="py-1 rounded bg-slate-100 dark:bg-slate-800 hover:bg-slate-200">30 năm</button>
                        </div>
                    </div>
                </div>

                <x-ad-banner placement="sidebar" />
            </div>

            <!-- Results & Chart (2 cols) -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Summary Top Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                        <span class="text-[11px] font-bold text-slate-400 uppercase block mb-1">Tổng Vốn Tự Bỏ Ra</span>
                        <span id="totalPrincipalDisp"
                            class="text-lg font-black text-slate-900 dark:text-white font-mono">650.000.000 đ</span>
                    </div>
                    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                        <span class="text-[11px] font-bold text-emerald-600 uppercase block mb-1">Lợi Nhuận Lãi Kép</span>
                        <span id="totalInterestDisp" class="text-lg font-black text-emerald-600 font-mono">485.000.000
                            đ</span>
                    </div>
                    <div
                        class="p-5 rounded-2xl bg-gradient-to-br from-indigo-500/10 to-purple-500/10 border border-indigo-500/30">
                        <span class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 uppercase block mb-1">Tổng
                            Tài Sản Tích Lũy</span>
                        <span id="finalBalanceDisp" class="text-xl font-black text-indigo-600 font-mono">1.135.000.000
                            đ</span>
                    </div>
                </div>

                <!-- Chart Box -->
                <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4">Biểu Đồ Tăng Trưởng Tài Sản</h3>
                    <div class="h-72 w-full relative">
                        <canvas id="growthChart"></canvas>
                    </div>
                </div>

                <!-- Yearly Table Breakdown -->
                <div
                    class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 space-y-3">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">Bảng Sao Kê Dòng Tiền Theo Năm</h3>
                    <div class="max-h-64 overflow-y-auto">
                        <table class="w-full text-xs text-left">
                            <thead
                                class="bg-slate-50 dark:bg-slate-800 sticky top-0 font-bold text-slate-600 dark:text-slate-300">
                                <tr>
                                    <th class="p-2.5">Năm</th>
                                    <th class="p-2.5">Tiền Vốn Đã Góp</th>
                                    <th class="p-2.5">Lãi Tích Lũy</th>
                                    <th class="p-2.5 text-right">Tổng Tài Sản</th>
                                </tr>
                            </thead>
                            <tbody id="timelineTbody" class="divide-y divide-slate-100 dark:divide-slate-800 font-mono">
                            </tbody>
                        </table>
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
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            let chartInstance = null;

            function formatVND(n) {
                return Math.round(n).toLocaleString('vi-VN') + ' đ';
            }

            function setYears(y) {
                document.getElementById('investYears').value = y;
                document.getElementById('yearsLabel').innerText = y + ' Năm';
                calculateInterest();
            }

            function calculateInterest() {
                const principal = parseFloat(document.getElementById('initPrincipal').value) || 0;
                const monthly = parseFloat(document.getElementById('monthlyDeposit').value) || 0;
                const annualRate = (parseFloat(document.getElementById('annualRate').value) || 10) / 100;
                const years = parseInt(document.getElementById('investYears').value) || 10;

                const monthlyRate = annualRate / 12;
                const totalMonths = years * 12;

                let curBalance = principal;
                let curDeposited = principal;

                const labels = ['Năm 0'];
                const principalData = [principal];
                const balanceData = [principal];
                let tbodyHtml = '';

                for (let m = 1; m <= totalMonths; m++) {
                    const interest = curBalance * monthlyRate;
                    curBalance += interest + monthly;
                    curDeposited += monthly;

                    if (m % 12 === 0) {
                        const yr = m / 12;
                        labels.push(`Năm ${yr}`);
                        principalData.push(Math.round(curDeposited));
                        balanceData.push(Math.round(curBalance));

                        const profit = curBalance - curDeposited;
                        tbodyHtml += `
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40">
                        <td class="p-2.5 font-bold text-slate-700 dark:text-slate-300">Năm ${yr}</td>
                        <td class="p-2.5">${formatVND(curDeposited)}</td>
                        <td class="p-2.5 text-emerald-600">+${formatVND(profit)}</td>
                        <td class="p-2.5 text-right font-bold text-indigo-600">${formatVND(curBalance)}</td>
                    </tr>
                `;
                    }
                }

                const totalProfit = curBalance - curDeposited;

                document.getElementById('totalPrincipalDisp').innerText = formatVND(curDeposited);
                document.getElementById('totalInterestDisp').innerText = formatVND(totalProfit);
                document.getElementById('finalBalanceDisp').innerText = formatVND(curBalance);
                document.getElementById('timelineTbody').innerHTML = tbodyHtml;

                renderChart(labels, principalData, balanceData);
            }

            function renderChart(labels, principalData, balanceData) {
                const ctx = document.getElementById('growthChart').getContext('2d');
                if (chartInstance) {
                    chartInstance.destroy();
                }

                chartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                                label: 'Tổng Tài Sản (Vốn + Lãi)',
                                data: balanceData,
                                borderColor: '#4f46e5',
                                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                                fill: true,
                                tension: 0.3
                            },
                            {
                                label: 'Tiền Vốn Tự Bỏ Ra',
                                data: principalData,
                                borderColor: '#94a3b8',
                                backgroundColor: 'rgba(148, 163, 184, 0.1)',
                                borderDash: [5, 5],
                                fill: true,
                                tension: 0.3
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        },
                        scales: {
                            y: {
                                ticks: {
                                    callback: function(value) {
                                        if (value >= 1000000000) return (value / 1000000000).toFixed(1) + ' Tỷ';
                                        if (value >= 1000000) return (value / 1000000).toFixed(0) + ' Tr';
                                        return value;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            window.addEventListener('DOMContentLoaded', calculateInterest);
        </script>
    @endpush
@endsection
