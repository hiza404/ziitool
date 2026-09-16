@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">

    <!-- Header Title -->
    <div class="mb-12">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-300 text-xs font-semibold mb-3">
            <span>🚀 ZiiTool Developer REST API v1</span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white mb-3">
            Tài Liệu REST API Dành Cho Lập Trình Viên
        </h1>
        <p class="text-sm text-slate-600 dark:text-slate-400">
            Tích hợp toàn bộ chức năng tiện ích vào ứng dụng web, mobile app hoặc backend của bạn. Tốc độ cao, chuẩn JSON RESTful.
        </p>
    </div>

    <!-- API Overview Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-12">
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
            <span class="text-xs text-slate-400 block mb-1">Base URL</span>
            <span class="font-mono text-xs font-bold text-indigo-600 dark:text-indigo-400">{{ url('/api/v1') }}</span>
        </div>
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
            <span class="text-xs text-slate-400 block mb-1">Định dạng phản hồi</span>
            <span class="font-mono text-xs font-bold text-emerald-600 dark:text-emerald-400">application/json</span>
        </div>
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
            <span class="text-xs text-slate-400 block mb-1">Gói Pro API Key</span>
            <a href="{{ route('pricing') }}" class="font-mono text-xs font-bold text-amber-500 hover:underline">Nhận API Key Pro →</a>
        </div>
    </div>

    <!-- API Endpoints List -->
    <div class="space-y-8">

        <!-- Endpoint 1: Tax Calculator -->
        <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4 pb-4 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-3">
                    <span class="px-2.5 py-1 text-xs font-bold rounded bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 font-mono">POST</span>
                    <span class="font-mono text-sm font-semibold text-slate-900 dark:text-white">/api/v1/tax/calculate</span>
                </div>
                <span class="text-xs text-slate-500">Tính Thuế TNCN & Lương Net/Gross</span>
            </div>

            <div class="space-y-4">
                <div>
                    <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Request Body (JSON)</h4>
                    <pre class="p-4 rounded-xl bg-slate-900 text-slate-100 font-mono text-xs overflow-x-auto"><code>{
  "income": 25000000,
  "type": "gross",
  "dependents": 1
}</code></pre>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Example Response (200 OK)</h4>
                    <pre class="p-4 rounded-xl bg-slate-900 text-slate-100 font-mono text-xs overflow-x-auto"><code>{
  "status": "success",
  "data": {
    "gross_income": 25000000,
    "insurance": {
      "bhxh": 2000000,
      "bhyt": 375000,
      "bhtn": 250000,
      "total": 2625000
    },
    "deductions": {
      "self": 11000000,
      "dependents": 4400000,
      "total": 15400000
    },
    "taxable_income": 6975000,
    "tax": 447500,
    "net_income": 21927500
  }
}</code></pre>
                </div>
            </div>
        </div>

        <!-- Endpoint 2: Compound Interest Calculator -->
        <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4 pb-4 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-3">
                    <span class="px-2.5 py-1 text-xs font-bold rounded bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 font-mono">POST</span>
                    <span class="font-mono text-sm font-semibold text-slate-900 dark:text-white">/api/v1/interest/calculate</span>
                </div>
                <span class="text-xs text-slate-500">Tính Lãi Kép & Tăng Trưởng Tài Sản</span>
            </div>

            <div class="space-y-4">
                <div>
                    <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Request Body (JSON)</h4>
                    <pre class="p-4 rounded-xl bg-slate-900 text-slate-100 font-mono text-xs overflow-x-auto"><code>{
  "principal": 50000000,
  "monthly_deposit": 5000000,
  "annual_rate": 8.5,
  "years": 5
}</code></pre>
                </div>
            </div>
        </div>

        <!-- Endpoint 3: Hash Generator -->
        <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4 pb-4 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-3">
                    <span class="px-2.5 py-1 text-xs font-bold rounded bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 font-mono">POST</span>
                    <span class="font-mono text-sm font-semibold text-slate-900 dark:text-white">/api/v1/hash</span>
                </div>
                <span class="text-xs text-slate-500">Tạo Mã Băm MD5, SHA-256, Base64</span>
            </div>

            <div class="space-y-4">
                <div>
                    <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-2">Request Body (JSON)</h4>
                    <pre class="p-4 rounded-xl bg-slate-900 text-slate-100 font-mono text-xs overflow-x-auto"><code>{
  "text": "Hello ZiiTool 2026"
}</code></pre>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

