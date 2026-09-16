@extends('admin.layouts.admin')

@section('title', 'Cấu Hình SEO & Hệ Thống')

@section('content')
<div class="max-w-4xl space-y-6">

    <div>
        <h3 class="text-base font-bold text-white mb-1">Cài Đặt SEO & Thông Tin Hệ Thống</h3>
        <p class="text-xs text-slate-400">Tối ưu thẻ meta tìm kiếm Google, cấu hình thông tin liên hệ và công cụ dọn dẹp bộ nhớ đệm.</p>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf

        <div class="p-6 rounded-3xl bg-slate-950 border border-slate-800 space-y-4">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">1. Cấu Hình Nhận Diện & SEO Mặc Định</h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Tên Website (Site Name)</label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-900 text-white text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Khẩu Hiệu (Tagline)</label>
                    <input type="text" name="site_tagline" value="{{ $settings['site_tagline'] }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-900 text-white text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Mô Tả SEO Mặc Định (Meta Description)</label>
                <textarea name="meta_description" rows="3" class="w-full p-3.5 rounded-xl border border-slate-700 bg-slate-900 text-white text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">{{ $settings['meta_description'] }}</textarea>
                <span class="text-[10px] text-slate-500">Mô tả xuất hiện trên kết quả tìm kiếm Google khi người dùng tìm trang chủ.</span>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Email Hỗ Trợ Kỹ Thuật</label>
                <input type="email" name="contact_email" value="{{ $settings['contact_email'] }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-900 text-white text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>

            <div class="pt-2">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Lưu Cấu Hình SEO</span>
                </button>
            </div>
        </div>
    </form>

    <!-- System Maintenance & Cache -->
    <div class="p-6 rounded-3xl bg-slate-950 border border-slate-800 space-y-4">
        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">2. Bảo Trì & Bộ Nhớ Đệm (Cache)</h4>
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-2xl bg-slate-900 border border-slate-800">
            <div>
                <span class="font-bold text-xs text-white block mb-0.5">Xóa Sạch Bộ Nhớ Đệm Toàn Bộ</span>
                <span class="text-[11px] text-slate-400">Dọn dẹp View compiled, Route cache và Application settings cache.</span>
            </div>
            <form method="POST" action="{{ route('admin.settings.clear_cache') }}">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-amber-400 font-semibold text-xs border border-slate-700 transition flex items-center gap-1.5">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    <span>Dọn Dẹp Cache</span>
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 text-xs">
            <div class="p-4 rounded-2xl bg-slate-900 border border-slate-800">
                <span class="text-[10px] text-slate-500 block uppercase font-bold">Phiên Bản PHP</span>
                <span class="font-mono font-bold text-white">{{ phpversion() }}</span>
            </div>
            <div class="p-4 rounded-2xl bg-slate-900 border border-slate-800">
                <span class="text-[10px] text-slate-500 block uppercase font-bold">Framework</span>
                <span class="font-mono font-bold text-white">Laravel {{ app()->version() }}</span>
            </div>
            <div class="p-4 rounded-2xl bg-slate-900 border border-slate-800">
                <span class="text-[10px] text-slate-500 block uppercase font-bold">Cơ Sở Dữ Liệu</span>
                <span class="font-mono font-bold text-emerald-400">SQLite (Siêu nhẹ)</span>
            </div>
        </div>
    </div>

</div>
@endsection

