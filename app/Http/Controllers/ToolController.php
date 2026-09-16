<?php

namespace App\Http\Controllers;

use App\Services\SeoService;
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

        if ($selectedCategory && isset($categories[$selectedCategory])) {
            $filteredTools = array_filter($tools, fn ($t) => ($t['category'] ?? '') === $selectedCategory);
        } else {
            $filteredTools = $tools;
        }

        $seo = SeoService::getMetadata([
            'title' => 'Web Tiện Ích Online Miễn Phí 100% - Siêu Tốc & Bảo Mật',
            'description' => 'Trọn bộ công cụ tiện ích trực tuyến tốt nhất: Chuyển đổi và nén ảnh WebP/PNG, Format JSON & SQL, tính thuế TNCN, tính lãi kép, tạo mã QR và Mockup thiết bị.',
            'keywords' => 'web tiện ích, micro tools online, nén ảnh online, json formatter, tính thuế tncn 2026, tính lãi kép, tạo qr code',
        ]);

        return view('pages.home', [
            'categories' => $categories,
            'tools' => $filteredTools,
            'allTools' => $tools,
            'selectedCategory' => $selectedCategory,
            'seo' => $seo,
        ]);
    }

    /**
     * Display a specific micro-tool page.
     */
    public function show(string $slug): View
    {
        $allTools = config('tools.list', []);

        if (! isset($allTools[$slug])) {
            abort(404, 'Công cụ tiện ích không tồn tại.');
        }

        $tool = $allTools[$slug];
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
}
