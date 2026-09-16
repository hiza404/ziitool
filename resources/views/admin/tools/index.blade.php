@extends('admin.layouts.admin')

@section('title', 'Quản Lý Công Cụ Tiện Ích')

@section('content')
<div class="space-y-6">

    <!-- Header Description -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-base font-bold text-white mb-1">Danh Sách 12 Công Cụ Tiện Ích</h3>
            <p class="text-xs text-slate-400">Bạn có thể tạm ẩn bất kỳ công cụ nào khỏi trang chủ hoặc tùy biến tiêu đề, huy hiệu (badge).</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs text-slate-400">Bật tắt hoạt động tức thì (AJAX)</span>
        </div>
    </div>

    <!-- Tools Table -->
    <div class="rounded-3xl bg-slate-950 border border-slate-800 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-900 border-b border-slate-800 text-slate-400 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">Công Cụ</th>
                        <th class="py-3.5 px-4">Danh Mục</th>
                        <th class="py-3.5 px-4">Huy Hiệu (Badge)</th>
                        <th class="py-3.5 px-4">Trạng Thái</th>
                        <th class="py-3.5 px-4 text-right">Thao Tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @foreach($tools as $t)
                        <tr class="hover:bg-slate-900/40 transition">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center font-bold">
                                        <i data-lucide="{{ $t['icon'] }}" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <span class="font-bold text-white block">{{ $t['custom_title'] }}</span>
                                        <span class="text-[11px] text-slate-500 font-mono">/tool/{{ $t['slug'] }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full bg-slate-900 text-slate-300 font-medium">
                                    {{ $t['category_name'] }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20 font-semibold text-[10px]">
                                    {{ $t['custom_badge'] }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" onchange="toggleToolStatus('{{ $t['slug'] }}', this)" {{ $t['is_active'] ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-9 h-5 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                                    <span class="ml-2 text-xs font-medium text-slate-400" id="statusLabel_{{ $t['slug'] }}">
                                        {{ $t['is_active'] ? 'Bật' : 'Tắt' }}
                                    </span>
                                </label>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" onclick="openEditModal('{{ $t['slug'] }}', '{{ addslashes($t['custom_title']) }}', '{{ addslashes($t['custom_badge']) }}', '{{ addslashes($t['custom_desc']) }}')" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-white font-semibold transition flex items-center gap-1">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Sửa
                                    </button>
                                    <a href="{{ route('tool.show', ['slug' => $t['slug']]) }}" target="_blank" class="p-1.5 text-slate-400 hover:text-indigo-400" title="Xem trên web">
                                        <i data-lucide="external-link" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Edit Tool Modal -->
<div id="editToolModal" class="fixed inset-0 z-50 hidden bg-slate-950/70 backdrop-blur-sm p-4 overflow-y-auto flex items-center justify-center">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-md shadow-2xl p-6 relative">
        <button onclick="closeEditModal()" class="absolute top-4 right-4 text-slate-400 hover:text-white">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>

        <h3 class="text-base font-bold text-white mb-1">Chỉnh Sửa Thông Tin Công Cụ</h3>
        <p class="text-xs text-slate-400 mb-4" id="modalToolSlug"></p>

        <form id="editToolForm" method="POST" action="" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Tiêu Đề Công Cụ</label>
                <input type="text" id="modalTitle" name="custom_title" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-800 text-white text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Huy Hiệu (Badge)</label>
                <input type="text" id="modalBadge" name="custom_badge" placeholder="Hot, Mới, Phổ biến, Dev Tool..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-700 bg-slate-800 text-white text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Mô Tả Ngắn</label>
                <textarea id="modalDesc" name="custom_desc" rows="3" class="w-full p-3 rounded-xl border border-slate-700 bg-slate-800 text-white text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
            </div>

            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition">
                    Lưu Thay Đổi
                </button>
                <button type="button" onclick="closeEditModal()" class="px-4 py-2.5 border border-slate-700 text-slate-300 hover:bg-slate-800 text-xs rounded-xl">
                    Hủy
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    async function toggleToolStatus(slug, checkbox) {
        const label = document.getElementById(`statusLabel_${slug}`);
        try {
            const res = await fetch(`/admin/tools/${slug}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            const data = await res.json();
            if (data.success) {
                label.innerText = data.is_active ? 'Bật' : 'Tắt';
            } else {
                checkbox.checked = !checkbox.checked;
                alert(data.message || 'Lỗi khi cập nhật trạng thái');
            }
        } catch (e) {
            checkbox.checked = !checkbox.checked;
            alert('Không thể kết nối máy chủ!');
        }
    }

    function openEditModal(slug, title, badge, desc) {
        document.getElementById('modalToolSlug').innerText = `Đường dẫn: /tool/${slug}`;
        document.getElementById('modalTitle').value = title;
        document.getElementById('modalBadge').value = badge;
        document.getElementById('modalDesc').value = desc;
        document.getElementById('editToolForm').action = `/admin/tools/${slug}/update`;
        document.getElementById('editToolModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editToolModal').classList.add('hidden');
    }
</script>
@endpush
@endsection

