<?php

namespace App\Http\Controllers;

use App\Models\ToolOverride;
use App\Services\SeoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ToolController extends Controller
{
    /**
     * Display the home page with all tools, categorized and searchable.
     */
    public function index(Request $request): View
    {
        $categories = config('tools.categories', []);
        $tools = config('tools.list', []);
        $selectedCategory = $request->query('category');
        $overrides = ToolOverride::all()->keyBy('slug');

        // Apply overrides
        foreach ($tools as $slug => &$t) {
            if (isset($overrides[$slug])) {
                $t['is_active'] = $overrides[$slug]->is_active;
                if (! empty($overrides[$slug]->custom_title)) {
                    $t['title'] = $overrides[$slug]->custom_title;
                }
                if (! empty($overrides[$slug]->custom_badge)) {
                    $t['badge'] = $overrides[$slug]->custom_badge;
                }
                if (! empty($overrides[$slug]->custom_desc)) {
                    $t['short_desc'] = $overrides[$slug]->custom_desc;
                }
            } else {
                $t['is_active'] = true;
            }
        }
        unset($t);

        // Filter inactive tools from public display
        $activeTools = array_filter($tools, fn ($t) => ($t['is_active'] ?? true) === true);

        if ($selectedCategory && isset($categories[$selectedCategory])) {
            $filteredTools = array_filter($activeTools, fn ($t) => ($t['category'] ?? '') === $selectedCategory);
        } else {
            $filteredTools = $activeTools;
        }

        $seo = SeoService::getMetadata([
            'title' => 'Web Tiện Ích Online Miễn Phí 100% - Siêu Tốc & Bảo Mật',
            'description' => 'Trọn bộ công cụ tiện ích trực tuyến tốt nhất: Chuyển đổi và nén ảnh WebP/PNG, Format JSON & SQL, tính thuế TNCN, tính lãi kép, tạo mã QR và Mockup thiết bị.',
            'keywords' => 'web tiện ích, micro tools online, nén ảnh online, json formatter, tính thuế tncn 2026, tính lãi kép, tạo qr code',
        ]);

        return view('pages.home', [
            'categories' => $categories,
            'tools' => $filteredTools,
            'allTools' => $activeTools,
            'selectedCategory' => $selectedCategory,
            'seo' => $seo,
        ]);
    }

    /**
     * Display a specific micro-tool page.
     */
    public function show(string $slug): View|RedirectResponse
    {
        $redirects = [
            'tao-slide-thuyet-trinh' => 'bai-thuyet-trinh',
            'chinh-anh-meitu' => 'bai-thuyet-trinh',
        ];

        if (isset($redirects[$slug])) {
            return redirect()->route('tool.show', ['slug' => $redirects[$slug]], 301);
        }

        $allTools = config('tools.list', []);

        if (! isset($allTools[$slug])) {
            abort(404, 'Công cụ tiện ích không tồn tại.');
        }

        $tool = $allTools[$slug];
        $override = ToolOverride::where('slug', $slug)->first();

        if ($override && ! $override->is_active) {
            abort(404, 'Công cụ này hiện đang được tạm khóa để nâng cấp bảo trì.');
        }

        if ($override) {
            if (! empty($override->custom_title)) {
                $tool['title'] = $override->custom_title;
            }
            if (! empty($override->custom_badge)) {
                $tool['badge'] = $override->custom_badge;
            }
            if (! empty($override->custom_desc)) {
                $tool['short_desc'] = $override->custom_desc;
            }
        }

        $categories = config('tools.categories', []);
        $categoryInfo = $categories[$tool['category']] ?? null;

        // Get related tools in the same category
        $relatedTools = array_filter($allTools, fn ($t) => $t['category'] === $tool['category'] && $t['slug'] !== $slug);

        $seo = SeoService::getMetadata([
            'title' => $tool['seo_title'] ?? $tool['title'],
            'description' => $tool['seo_desc'] ?? $tool['short_desc'],
            'keywords' => $tool['keywords'] ?? '',
            'how_to' => $tool['how_to'] ?? [],
            'faq' => $tool['faq'] ?? [],
        ]);

        // Map slug to blade view file
        $viewMap = [
            'chuyen-doi-anh' => 'tools.image-converter',
            'nen-anh' => 'tools.image-compressor',
            'resize-anh' => 'tools.image-resizer',
            'json-formatter' => 'tools.json-formatter',
            'sql-formatter' => 'tools.sql-formatter',
            'css-formatter' => 'tools.css-formatter',
            'base64-hash' => 'tools.base64-hash',
            'tao-ma-qr' => 'tools.qr-generator',
            'tinh-thue-tncn' => 'tools.tax-calculator',
            'tinh-lai-kep' => 'tools.compound-interest',
            'tao-mockup-thiet-bi' => 'tools.device-mockup',
            'trich-xuat-bang-mau' => 'tools.color-palette',
            'pdf-sang-word' => 'tools.pdf-to-word',
            'chuyen-sang-excel' => 'tools.format-to-excel',
            'chuyen-sang-word' => 'tools.format-to-word',
            'xoa-phong-anh' => 'tools.ai-background-remover',
            'nang-chat-luong-anh' => 'tools.ai-image-enhancer',
            'bai-thuyet-trinh' => 'tools.slide-presentation-maker',
            'tao-slide-thuyet-trinh' => 'tools.slide-presentation-maker',
            'chinh-anh-meitu' => 'tools.slide-presentation-maker',
        ];

        $viewName = $viewMap[$slug] ?? 'tools.generic';

        return view($viewName, [
            'tool' => $tool,
            'categoryInfo' => $categoryInfo,
            'relatedTools' => $relatedTools,
            'allTools' => $allTools,
            'seo' => $seo,
        ]);
    }

    /**
     * Generate dynamic sitemap.xml for SEO indexing.
     */
    public function sitemap(): Response
    {
        $tools = config('tools.list', []);
        $baseUrl = url('/');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Home
        $xml .= '<url>';
        $xml .= '<loc>'.htmlspecialchars($baseUrl).'</loc>';
        $xml .= '<changefreq>daily</changefreq>';
        $xml .= '<priority>1.0</priority>';
        $xml .= '</url>';

        // Pricing
        $xml .= '<url>';
        $xml .= '<loc>'.htmlspecialchars($baseUrl.'/pricing').'</loc>';
        $xml .= '<changefreq>weekly</changefreq>';
        $xml .= '<priority>0.8</priority>';
        $xml .= '</url>';

        // API Docs
        $xml .= '<url>';
        $xml .= '<loc>'.htmlspecialchars($baseUrl.'/api-docs').'</loc>';
        $xml .= '<changefreq>weekly</changefreq>';
        $xml .= '<priority>0.8</priority>';
        $xml .= '</url>';

        // Each Tool
        foreach ($tools as $tool) {
            $toolUrl = route('tool.show', ['slug' => $tool['slug']]);
            $xml .= '<url>';
            $xml .= '<loc>'.htmlspecialchars($toolUrl).'</loc>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.9</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    /**
     * Generate dynamic robots.txt.
     */
    public function robots(): Response
    {
        $content = "User-agent: *\nAllow: /\nSitemap: ".url('/sitemap.xml')."\n";

        return response($content, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }

    /**
     * High-Fidelity Server Engine for PDF to Word conversion.
     */
    public function convertPdfToDocx(Request $request)
    {
        $request->validate([
            'pdf_file' => 'required|file|mimes:pdf|max:51200',
        ]);

        $file = $request->file('pdf_file');
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $tempId = uniqid('pdf_conv_', true);
        $tempDir = storage_path('app/temp/'.$tempId);
        @mkdir($tempDir.'/profile', 0777, true);

        $pdfPath = $tempDir.'/input.pdf';
        $file->move($tempDir, 'input.pdf');

        $cmd = sprintf(
            'HOME=%s soffice "-env:UserInstallation=file://%s/profile" --headless --infilter="writer_pdf_import" --convert-to docx %s --outdir %s 2>&1',
            escapeshellarg($tempDir),
            $tempDir,
            escapeshellarg($pdfPath),
            escapeshellarg($tempDir)
        );

        exec($cmd, $output, $exitCode);

        $docxFiles = glob($tempDir.'/*.docx');
        if ($exitCode === 0 && ! empty($docxFiles) && file_exists($docxFiles[0])) {
            $docxPath = $docxFiles[0];
            $downloadName = ($originalName ?: 'document').'_ziitool.docx';

            return response()->download($docxPath, $downloadName)->deleteFileAfterSend(true);
        }

        // Cleanup if failed
        @unlink($pdfPath);

        return response()->json([
            'error' => 'Không thể chuyển đổi tệp bằng engine máy chủ. Vui lòng chuyển sang sử dụng Engine Trình Duyệt để bóc tách thông minh.',
            'details' => implode("\n", $output),
        ], 422);
    }
}
