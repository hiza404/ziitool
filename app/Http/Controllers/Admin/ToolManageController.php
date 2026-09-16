<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ToolOverride;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ToolManageController extends Controller
{
    /**
     * List all tools with their override settings.
     */
    public function index(): View
    {
        $allTools = config('tools.list', []);
        $categories = config('tools.categories', []);
        $overrides = ToolOverride::all()->keyBy('slug');

        $toolItems = [];
        foreach ($allTools as $slug => $tool) {
            $ov = $overrides[$slug] ?? null;
            $toolItems[] = [
                'slug' => $slug,
                'category' => $tool['category'] ?? '',
                'category_name' => $categories[$tool['category']]['name'] ?? '',
                'icon' => $tool['icon'] ?? 'wrench',
                'original_title' => $tool['title'],
                'original_badge' => $tool['badge'] ?? 'Tiện ích',
                'original_desc' => $tool['short_desc'] ?? '',
                'is_active' => $ov ? $ov->is_active : true,
                'custom_title' => $ov?->custom_title ?: $tool['title'],
                'custom_badge' => $ov?->custom_badge ?: ($tool['badge'] ?? 'Tiện ích'),
                'custom_desc' => $ov?->custom_desc ?: ($tool['short_desc'] ?? ''),
            ];
        }

        return view('admin.tools.index', [
            'tools' => $toolItems,
            'categories' => $categories,
        ]);
    }

    /**
     * Toggle active status of a tool via AJAX.
     */
    public function toggleStatus(Request $request, string $slug): JsonResponse
    {
        $allTools = config('tools.list', []);
        if (! isset($allTools[$slug])) {
            return response()->json(['success' => false, 'message' => 'Công cụ không tồn tại.'], 404);
        }

        $override = ToolOverride::firstOrCreate(['slug' => $slug], ['is_active' => true]);
        $override->is_active = ! $override->is_active;
        $override->save();

        $statusStr = $override->is_active ? 'Đã bật hoạt động' : 'Đã tạm tắt';

        return response()->json([
            'success' => true,
            'is_active' => $override->is_active,
            'message' => "{$statusStr} công cụ thành công!",
        ]);
    }

    /**
     * Update title, badge, and description of a tool.
     */
    public function update(Request $request, string $slug): RedirectResponse
    {
        $allTools = config('tools.list', []);
        if (! isset($allTools[$slug])) {
            return back()->with('error', 'Công cụ không tồn tại.');
        }

        $validated = $request->validate([
            'custom_title' => ['required', 'string', 'max:150'],
            'custom_badge' => ['nullable', 'string', 'max:30'],
            'custom_desc' => ['nullable', 'string', 'max:300'],
        ]);

        $override = ToolOverride::firstOrCreate(['slug' => $slug]);
        $override->custom_title = $validated['custom_title'];
        $override->custom_badge = $validated['custom_badge'];
        $override->custom_desc = $validated['custom_desc'];
        $override->save();

        return back()->with('success', 'Đã cập nhật thông tin công cụ thành công!');
    }
}
