<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\ToolOverride;
use App\Services\QuizParserService;
use App\Services\SeoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
            'is_home' => true,
            'title' => 'Web Tiện Ích Online Miễn Phí 100% | SnapTik Tải Video TikTok, Xóa Phông AI, Nén Ảnh',
            'description' => 'Trọn bộ công cụ tiện ích trực tuyến miễn phí 100%: Tải video TikTok không logo Full HD (SnapTik), tải video YouTube & Facebook, xóa phông ảnh AI, nén ảnh siêu nhẹ, format JSON, tính thuế TNCN.',
            'keywords' => 'ziitool, snaptik, tải video tiktok, tải video tiktok không logo, tai video tiktok, download video tiktok, tải video youtube, tải video facebook, xóa phông ảnh, tách nền ảnh, nén ảnh online, format json, tính thuế tncn 2026, tạo mã qr',
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
            'doi-phong-anh' => 'xoa-phong-anh',
            'xoa-doi-phong-anh' => 'xoa-phong-anh',
            'snaptik' => 'tai-video-tiktok',
            'tai-video-douyin' => 'tai-video-tiktok',
            'tai-video-youtube' => 'tai-video-tiktok',
            'tai-video-facebook' => 'tai-video-tiktok',
            'tai-video-da-nen-tang' => 'tai-video-tiktok',
            'trac-nghiem-online' => 'tao-de-trac-nghiem-tu-file',
            'thi-trac-nghiem-ai' => 'tao-de-trac-nghiem-tu-file',
            'doc-file-trac-nghiem' => 'tao-de-trac-nghiem-tu-file',
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
            'xoa-phong-anh' => 'tools.ai-background-remover',
            'doi-phong-anh' => 'tools.ai-background-remover',
            'xoa-doi-phong-anh' => 'tools.ai-background-remover',
            'tai-video-tiktok' => 'tools.tiktok-downloader',
            'snaptik' => 'tools.tiktok-downloader',
            'tao-de-trac-nghiem-tu-file' => 'tools.quiz-maker',
        ];

        $viewName = $viewMap[$slug] ?? 'tools.generic';

        $initialQuiz = null;
        if ($slug === 'tao-de-trac-nghiem-tu-file' && request()->filled('code')) {
            $quizCode = trim((string) request()->query('code'));
            $foundQuiz = Quiz::where('code', $quizCode)->first();
            if ($foundQuiz) {
                $this->claimSessionQuizzes($foundQuiz);

                if ($foundQuiz->is_public || (Auth::check() && Auth::id() === $foundQuiz->user_id)) {
                    $foundQuiz->increment('attempts_count');
                    $initialQuiz = [
                        'code' => $foundQuiz->code,
                        'title' => $foundQuiz->title,
                        'is_public' => $foundQuiz->is_public,
                        'is_owner' => Auth::check() && Auth::id() === $foundQuiz->user_id,
                        'total_questions' => $foundQuiz->total_questions,
                        'questions' => $foundQuiz->questions,
                    ];
                } else {
                    session()->flash('quiz_error', __('Đề thi này được đặt ở chế độ Riêng tư (Chỉ chủ sở hữu tài khoản mới có quyền mở).'));
                }
            } else {
                session()->flash('quiz_error', __('Không tìm thấy đề thi với mã: :code', ['code' => $quizCode]));
            }
        }

        return view($viewName, [
            'tool' => $tool,
            'categoryInfo' => $categoryInfo,
            'relatedTools' => $relatedTools,
            'allTools' => $allTools,
            'seo' => $seo,
            'hasSystemGeminiKey' => ! empty(config('services.gemini.key')),
            'initialQuiz' => $initialQuiz,
        ]);
    }

    /**
     * Generate dynamic sitemap.xml for SEO indexing.
     */
    public function sitemap(): Response
    {
        $tools = config('tools.list', []);
        $baseUrl = url('/');
        $today = date('Y-m-d');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">';

        // Home Page (Highest Priority)
        $xml .= '<url>';
        $xml .= '<loc>'.htmlspecialchars($baseUrl).'</loc>';
        $xml .= '<lastmod>'.$today.'</lastmod>';
        $xml .= '<changefreq>daily</changefreq>';
        $xml .= '<priority>1.0</priority>';
        $xml .= '<xhtml:link rel="alternate" hreflang="vi" href="'.htmlspecialchars($baseUrl).'?lang=vi" />';
        $xml .= '<xhtml:link rel="alternate" hreflang="en" href="'.htmlspecialchars($baseUrl).'?lang=en" />';
        $xml .= '<xhtml:link rel="alternate" hreflang="x-default" href="'.htmlspecialchars($baseUrl).'" />';
        $xml .= '</url>';

        // API Docs
        $xml .= '<url>';
        $xml .= '<loc>'.htmlspecialchars($baseUrl.'/api-docs').'</loc>';
        $xml .= '<lastmod>'.$today.'</lastmod>';
        $xml .= '<changefreq>weekly</changefreq>';
        $xml .= '<priority>0.8</priority>';
        $xml .= '</url>';

        // Tools (High Priority for Trending Tools)
        $trendingSlugs = ['tai-video-tiktok', 'xoa-phong-anh', 'nen-anh', 'format-json', 'thue-tncn'];
        foreach ($tools as $tool) {
            $toolUrl = route('tool.show', ['slug' => $tool['slug']]);
            $isTrending = in_array($tool['slug'], $trendingSlugs, true);
            $priority = $isTrending ? '0.95' : '0.85';
            $changefreq = $isTrending ? 'daily' : 'weekly';

            $xml .= '<url>';
            $xml .= '<loc>'.htmlspecialchars($toolUrl).'</loc>';
            $xml .= '<lastmod>'.$today.'</lastmod>';
            $xml .= '<changefreq>'.$changefreq.'</changefreq>';
            $xml .= '<priority>'.$priority.'</priority>';
            $xml .= '<xhtml:link rel="alternate" hreflang="vi" href="'.htmlspecialchars($toolUrl).'?lang=vi" />';
            $xml .= '<xhtml:link rel="alternate" hreflang="en" href="'.htmlspecialchars($toolUrl).'?lang=en" />';
            $xml .= '<xhtml:link rel="alternate" hreflang="x-default" href="'.htmlspecialchars($toolUrl).'" />';
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
        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /payment/\n";
        $content .= "Disallow: /account/\n";
        $content .= "Disallow: /tool/video/download\n\n";
        $content .= "User-agent: Googlebot\n";
        $content .= "Allow: /\n\n";
        $content .= "User-agent: Googlebot-Image\n";
        $content .= "Allow: /\n\n";
        $content .= 'Sitemap: '.url('/sitemap.xml')."\n";

        return response($content, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }

    /**
     * Parse video from TikTok, YouTube, or Facebook and return download streams.
     */
    public function parseVideo(Request $request): JsonResponse
    {
        $request->validate([
            'url' => 'required|string|max:1000',
        ]);

        $rawUrl = trim($request->input('url'));

        // Extract http/https URL if user pasted text containing a URL (e.g., from mobile share sheet)
        if (preg_match('/https?:\/\/[^\s]+/i', $rawUrl, $match)) {
            $rawUrl = $match[0];
        } elseif (! str_starts_with($rawUrl, 'http://') && ! str_starts_with($rawUrl, 'https://')) {
            $rawUrl = 'https://'.$rawUrl;
        }

        $rawUrl = rtrim($rawUrl, ".,;!?)>\"'\t\n\r");

        // Resolve shortened links (vt.tiktok.com, vm.tiktok.com, fb.watch, youtu.be)
        if (preg_match('/(vt\.tiktok\.com|vm\.tiktok\.com|fb\.watch|youtu\.be)/i', $rawUrl)) {
            $ch = curl_init($rawUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_6 like Mac OS X)');
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_exec($ch);
            $effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            curl_close($ch);
            if (! empty($effectiveUrl) && filter_var($effectiveUrl, FILTER_VALIDATE_URL)) {
                $rawUrl = $effectiveUrl;
            }
        }

        if (preg_match('/(tiktok\.com|douyin\.com)/i', $rawUrl)) {
            return $this->parseTiktokEngine($rawUrl);
        } elseif (preg_match('/(youtube\.com|youtu\.be)/i', $rawUrl)) {
            return $this->parseYoutubeEngine($rawUrl);
        } elseif (preg_match('/(facebook\.com|fb\.watch|fb\.com)/i', $rawUrl)) {
            return $this->parseFacebookEngine($rawUrl);
        }

        return response()->json([
            'success' => false,
            'message' => 'Liên kết không được hỗ trợ. Vui lòng nhập liên kết hợp lệ từ TikTok, YouTube hoặc Facebook.',
        ], 422);
    }

    /**
     * Stream download video/audio file with attachment headers so the browser saves it directly.
     */
    public function downloadVideo(Request $request): StreamedResponse|Response
    {
        $url = $request->query('url');
        $rawTitle = $request->query('title', 'video');
        $platform = strtolower($request->query('platform', 'video'));
        $type = $request->query('type', 'video');
        $ext = strtolower($request->query('ext', 'mp4'));
        $videoId = $request->query('video_id', '');

        if (! in_array($ext, ['mp4', 'mp3', 'jpg', 'jpeg', 'webm', 'm4a'])) {
            $ext = 'mp4';
        }

        $safeTitle = Str::slug($rawTitle) ?: 'video';
        if (strlen($safeTitle) > 50) {
            $safeTitle = substr($safeTitle, 0, 50);
        }
        $filename = "Ziitool_{$platform}_{$safeTitle}.{$ext}";

        $contentType = match ($ext) {
            'mp3', 'm4a' => 'audio/mpeg',
            'jpg', 'jpeg' => 'image/jpeg',
            default => 'video/mp4',
        };

        // If platform is YouTube, stream directly from yt-dlp to bypass googlevideo IP restrictions
        if ($platform === 'youtube' && ! empty($videoId)) {
            $binPath = storage_path('app/bin/yt-dlp');
            if (file_exists($binPath)) {
                $ytUrl = "https://www.youtube.com/watch?v={$videoId}";
                $format = ($type === 'audio') ? '140/ba/b' : '18/b[ext=mp4]/best[ext=mp4]/best';

                $cmd = sprintf(
                    '%s --js-runtimes node:/usr/bin/node -f %s --no-warnings -o - %s',
                    escapeshellarg($binPath),
                    escapeshellarg($format),
                    escapeshellarg($ytUrl)
                );

                return response()->stream(function () use ($cmd) {
                    set_time_limit(0);
                    $proc = popen($cmd, 'r');
                    if ($proc) {
                        while (! feof($proc)) {
                            $buf = fread($proc, 1024 * 64);
                            if ($buf !== false && strlen($buf) > 0) {
                                echo $buf;
                                if (ob_get_level() > 0) {
                                    ob_flush();
                                }
                                flush();
                            }
                        }
                        pclose($proc);
                    }
                }, 200, [
                    'Content-Type' => $contentType,
                    'Content-Disposition' => 'attachment; filename="'.$filename.'"',
                    'Cache-Control' => 'no-cache, no-store, must-revalidate',
                    'Pragma' => 'no-cache',
                    'Expires' => '0',
                ]);
            }
        }

        // For direct CDN URL (TikTok, Facebook, Images, etc.)
        if (empty($url) || ! filter_var($url, FILTER_VALIDATE_URL)) {
            return response('Không tìm thấy liên kết tệp để tải về.', 404);
        }

        return response()->stream(function () use ($url) {
            set_time_limit(0);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36');
            curl_setopt($ch, CURLOPT_REFERER, 'https://www.tiktok.com/');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 180);
            curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $chunk) {
                echo $chunk;
                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();

                return strlen($chunk);
            });
            curl_exec($ch);
            curl_close($ch);
        }, 200, [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Backward-compatible alias for TikTok parsing.
     */
    public function parseTiktok(Request $request): JsonResponse
    {
        return $this->parseVideo($request);
    }

    /**
     * Engine for extracting TikTok and Douyin videos without watermark.
     */
    private function parseTiktokEngine(string $url): JsonResponse
    {
        try {
            $ch = curl_init('https://www.tikwm.com/api/');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'url' => $url,
                'hd' => 1,
            ]));
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($response === false || $httpCode !== 200) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể kết nối đến máy chủ TikTok. Lỗi: '.($curlError ?: 'HTTP '.$httpCode),
                ], 502);
            }

            $data = json_decode($response, true);

            if (! isset($data['code']) || $data['code'] !== 0 || empty($data['data'])) {
                return response()->json([
                    'success' => false,
                    'message' => $data['msg'] ?? 'Không tìm thấy video hoặc video đang ở chế độ riêng tư/bị xóa.',
                ], 404);
            }

            $item = $data['data'];
            $item['platform'] = 'tiktok';
            $item['platform_name'] = 'TikTok';

            return response()->json([
                'success' => true,
                'data' => $item,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi xử lý TikTok: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Engine for extracting YouTube and Shorts videos.
     */
    private function parseYoutubeEngine(string $url): JsonResponse
    {
        try {
            $binPath = storage_path('app/bin/yt-dlp');
            if (! file_exists($binPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Engine bóc tách video chưa được cài đặt trên hệ thống.',
                ], 500);
            }

            $cmd = sprintf(
                '%s --no-check-certificates --js-runtimes node:/usr/bin/node --socket-timeout 10 --dump-single-json --skip-download --no-warnings %s 2>&1',
                escapeshellarg($binPath),
                escapeshellarg($url)
            );

            $output = shell_exec($cmd);
            $info = json_decode($output, true);

            if (! $info || empty($info['id'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể bóc tách video YouTube. Vui lòng kiểm tra lại đường dẫn video hoặc trạng thái công khai của video.',
                ], 404);
            }

            $formats = $info['formats'] ?? [];
            $hdVideoUrl = null;
            $sdVideoUrl = null;
            $audioUrl = null;
            $hdSize = null;
            $sdSize = null;

            foreach ($formats as $f) {
                if (empty($f['url'])) {
                    continue;
                }
                $vcodec = $f['vcodec'] ?? 'none';
                $acodec = $f['acodec'] ?? 'none';
                $height = $f['height'] ?? 0;

                // Best audio
                if ($vcodec === 'none' && $acodec !== 'none') {
                    if (! $audioUrl || ($f['abr'] ?? 0) > 120) {
                        $audioUrl = $f['url'];
                    }
                }

                // Progressive video + audio
                if ($vcodec !== 'none' && $acodec !== 'none') {
                    if ($height >= 720) {
                        $hdVideoUrl = $f['url'];
                        $hdSize = $f['filesize'] ?? null;
                    } else {
                        $sdVideoUrl = $f['url'];
                        $sdSize = $f['filesize'] ?? null;
                    }
                }
            }

            // Fallback video URL if no combined format was found
            if (! $hdVideoUrl) {
                foreach ($formats as $f) {
                    if (! empty($f['url']) && ($f['vcodec'] ?? 'none') !== 'none') {
                        $hdVideoUrl = $f['url'];
                        $hdSize = $f['filesize'] ?? null;
                        break;
                    }
                }
            }

            $result = [
                'platform' => 'youtube',
                'platform_name' => 'YouTube',
                'id' => $info['id'],
                'title' => $info['title'] ?? 'YouTube Video',
                'author' => [
                    'nickname' => $info['uploader'] ?? $info['channel'] ?? 'YouTube Creator',
                    'avatar' => $info['thumbnail'] ?? '',
                    'unique_id' => $info['uploader_id'] ?? $info['id'],
                ],
                'cover' => $info['thumbnail'] ?? "https://i.ytimg.com/vi/{$info['id']}/maxresdefault.jpg",
                'duration' => $info['duration'] ?? 0,
                'digg_count' => $info['like_count'] ?? 0,
                'comment_count' => $info['comment_count'] ?? 0,
                'share_count' => $info['view_count'] ?? 0,
                'play' => $sdVideoUrl ?: $hdVideoUrl,
                'hdplay' => $hdVideoUrl,
                'wmplay' => $sdVideoUrl ?: $hdVideoUrl,
                'music' => $audioUrl,
                'size' => $sdSize,
                'hd_size' => $hdSize,
                'embed_url' => "https://www.youtube.com/embed/{$info['id']}",
            ];

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi xử lý YouTube: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Engine for extracting Facebook videos and reels.
     */
    private function parseFacebookEngine(string $url): JsonResponse
    {
        try {
            $binPath = storage_path('app/bin/yt-dlp');
            $info = null;

            if (file_exists($binPath)) {
                $cmd = sprintf(
                    '%s --no-check-certificates --socket-timeout 8 --dump-single-json --skip-download --no-warnings %s 2>&1',
                    escapeshellarg($binPath),
                    escapeshellarg($url)
                );
                $output = shell_exec($cmd);
                $info = json_decode($output, true);
            }

            if ($info && ! empty($info['title'])) {
                $formats = $info['formats'] ?? [];
                $hdUrl = null;
                $sdUrl = null;
                foreach ($formats as $f) {
                    if (empty($f['url'])) {
                        continue;
                    }
                    $formatId = strtolower($f['format_id'] ?? '');
                    if (str_contains($formatId, 'hd') || ($f['height'] ?? 0) >= 720) {
                        $hdUrl = $f['url'];
                    } else {
                        $sdUrl = $f['url'];
                    }
                }

                $result = [
                    'platform' => 'facebook',
                    'platform_name' => 'Facebook',
                    'id' => $info['id'] ?? uniqid('fb_'),
                    'title' => $info['title'] ?? 'Facebook Video',
                    'author' => [
                        'nickname' => $info['uploader'] ?? 'Facebook User',
                        'avatar' => $info['thumbnail'] ?? '',
                        'unique_id' => $info['uploader_id'] ?? 'facebook',
                    ],
                    'cover' => $info['thumbnail'] ?? '',
                    'duration' => $info['duration'] ?? 0,
                    'digg_count' => $info['like_count'] ?? 0,
                    'comment_count' => $info['comment_count'] ?? 0,
                    'share_count' => $info['view_count'] ?? 0,
                    'play' => $sdUrl ?: $hdUrl,
                    'hdplay' => $hdUrl ?: $sdUrl,
                    'wmplay' => $sdUrl ?: $hdUrl,
                    'music' => null,
                ];

                return response()->json([
                    'success' => true,
                    'data' => $result,
                ]);
            }

            // Fallback scraping via cURL
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 8);
            $html = curl_exec($ch);
            curl_close($ch);

            preg_match('/"(playable_url_quality_hd|browser_native_hd_url|hd_src)":"(https:[^"]+)"/i', $html ?: '', $hdMatch);
            preg_match('/"(playable_url|browser_native_sd_url|sd_src)":"(https:[^"]+)"/i', $html ?: '', $sdMatch);
            preg_match('/"(preferred_thumbnail|thumbnail)":\{"image":\{"uri":"(https:[^"]+)"/i', $html ?: '', $thumbMatch);

            $cleanHd = ! empty($hdMatch[2]) ? stripslashes($hdMatch[2]) : null;
            $cleanSd = ! empty($sdMatch[2]) ? stripslashes($sdMatch[2]) : null;
            $cleanThumb = ! empty($thumbMatch[2]) ? stripslashes($thumbMatch[2]) : null;

            if ($cleanHd || $cleanSd) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'platform' => 'facebook',
                        'platform_name' => 'Facebook',
                        'id' => uniqid('fb_'),
                        'title' => 'Facebook Video',
                        'author' => [
                            'nickname' => 'Facebook User',
                            'avatar' => $cleanThumb ?: '',
                            'unique_id' => 'facebook',
                        ],
                        'cover' => $cleanThumb ?: '',
                        'duration' => 0,
                        'digg_count' => 0,
                        'comment_count' => 0,
                        'share_count' => 0,
                        'play' => $cleanSd ?: $cleanHd,
                        'hdplay' => $cleanHd ?: $cleanSd,
                        'wmplay' => $cleanSd ?: $cleanHd,
                        'music' => null,
                    ],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Không thể bóc tách video Facebook này. Hãy đảm bảo video được chia sẻ ở chế độ Công khai (Public).',
            ], 404);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi xử lý Facebook: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Parse quiz questions from uploaded document or raw text.
     */
    public function parseQuiz(Request $request, QuizParserService $parser): JsonResponse
    {
        @ini_set('max_execution_time', '300');
        @set_time_limit(300);
        @ini_set('memory_limit', '512M');

        $request->validate([
            'file' => 'nullable|file|mimes:pdf|max:51200', // 50MB max PDF
            'text' => 'nullable|string',
            'api_key' => 'nullable|string',
            'model' => 'nullable|string',
            'mode' => 'nullable|string',
        ], [
            'file.mimes' => __('Hệ thống chỉ hỗ trợ tải lên định dạng file PDF. Nếu bạn có file Word (.docx), vui lòng lưu sang file PDF (Save as PDF trong Word) hoặc dán trực tiếp nội dung đề thi vào ô văn bản.'),
        ]);

        if (! $request->hasFile('file') && empty(trim((string) $request->input('text', '')))) {
            return response()->json([
                'success' => false,
                'error' => __('Vui lòng tải lên file tài liệu PDF hoặc dán nội dung đề thi.'),
            ], 422);
        }

        $input = $request->hasFile('file') ? $request->file('file') : (string) $request->input('text');
        $options = [
            'api_key' => $request->input('api_key'),
            'model' => $request->input('model', config('services.gemini.model', 'gemini-2.0-flash')),
            'mode' => $request->input('mode', 'auto'),
        ];

        $result = $parser->parse($input, $options);

        return response()->json($result, ($result['success'] ?? false) ? 200 : 400);
    }

    /**
     * Load pre-packaged sample exam questions.
     */
    public function sampleQuiz(QuizParserService $parser): JsonResponse
    {
        return response()->json($parser->getSampleExam());
    }

    /**
     * Claim unowned quizzes created in this session or unowned target quiz for the authenticated user.
     */
    private function claimSessionQuizzes(?Quiz $quiz = null): void
    {
        if (! Auth::check()) {
            return;
        }

        $userId = Auth::id();

        if ($quiz && $quiz->user_id === null) {
            $quiz->user_id = $userId;
            $quiz->save();
        }

        $sessionCodes = session()->get('my_quiz_codes', []);
        if (! empty($sessionCodes)) {
            Quiz::whereIn('code', $sessionCodes)
                ->whereNull('user_id')
                ->update(['user_id' => $userId]);
            session()->forget('my_quiz_codes');
        }
    }

    /**
     * Save quiz to database with shareable code and public/private visibility.
     */
    public function saveQuiz(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'questions' => 'required|array|min:1',
            'is_public' => 'nullable|boolean',
            'code' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        $isPublic = $request->boolean('is_public', true);

        if (! $isPublic && ! Auth::check()) {
            return response()->json([
                'success' => false,
                'require_login' => true,
                'error' => __('Bạn cần đăng nhập tài khoản để đặt đề thi ở chế độ Riêng Tư (Private).'),
            ], 401);
        }

        $this->claimSessionQuizzes();

        $code = $request->input('code') ? trim(strtoupper($request->input('code'))) : null;
        $quiz = null;

        if ($code) {
            $quiz = Quiz::where('code', $code)->first();
            if ($quiz) {
                // If quiz is unowned and user is logged in: claim it!
                if ($quiz->user_id === null && Auth::check()) {
                    $quiz->user_id = Auth::id();
                } elseif ($quiz->user_id !== null && (! Auth::check() || Auth::id() !== $quiz->user_id)) {
                    // Quiz belongs to another user: DO NOT overwrite someone else's quiz.
                    // Instead, fork / clone as a new quiz for the current user!
                    $quiz = null;
                }
            }
        }

        if (! $quiz) {
            $code = Quiz::generateCode();
            $quiz = new Quiz;
            $quiz->code = $code;
        }

        if ($user) {
            $quiz->user_id = $user->id;
        }

        $quiz->title = $request->input('title');
        $quiz->is_public = $isPublic;
        $quiz->total_questions = count($request->input('questions'));
        $quiz->questions = $request->input('questions');
        $quiz->save();

        // Track session codes so if guest later logs in, they are claimed automatically
        $sessionCodes = session()->get('my_quiz_codes', []);
        $sessionCodes[] = $quiz->code;
        session()->put('my_quiz_codes', array_unique($sessionCodes));

        $shareUrl = route('tool.show', ['slug' => 'tao-de-trac-nghiem-tu-file']).'?code='.$quiz->code;

        return response()->json([
            'success' => true,
            'code' => $quiz->code,
            'title' => $quiz->title,
            'is_public' => $quiz->is_public,
            'is_owner' => Auth::check() && Auth::id() === $quiz->user_id,
            'share_url' => $shareUrl,
            'message' => $quiz->is_public
                ? __('Đã lưu đề thi thành công! Bất kỳ ai có mã hoặc link đều có thể mở làm đề này.')
                : __('Đã lưu đề thi Riêng Tư thành công! Chỉ tài khoản của bạn mới có thể mở đề này.'),
        ]);
    }

    /**
     * Load a saved quiz by its code.
     */
    public function loadQuizByCode(Request $request, string $code): JsonResponse
    {
        $code = trim(strtoupper($code));
        $quiz = Quiz::where('code', $code)->first();

        if (! $quiz) {
            return response()->json([
                'success' => false,
                'error' => __('Không tìm thấy đề thi với mã: :code', ['code' => $code]),
            ], 404);
        }

        // If unowned and user is logged in, claim ownership!
        $this->claimSessionQuizzes($quiz);

        if (! $quiz->is_public) {
            if (! Auth::check()) {
                return response()->json([
                    'success' => false,
                    'require_login' => true,
                    'error' => __('Đề thi này được đặt ở chế độ Riêng Tư. Vui lòng đăng nhập tài khoản chủ sở hữu để truy cập.'),
                ], 403);
            }

            if (Auth::id() !== $quiz->user_id) {
                return response()->json([
                    'success' => false,
                    'error' => __('Đề thi này được đặt ở chế độ Riêng Tư. Bạn không có quyền truy cập đề thi của người khác.'),
                ], 403);
            }
        }

        $quiz->increment('attempts_count');

        return response()->json([
            'success' => true,
            'code' => $quiz->code,
            'title' => $quiz->title,
            'is_public' => $quiz->is_public,
            'is_owner' => Auth::check() && Auth::id() === $quiz->user_id,
            'total_questions' => $quiz->total_questions,
            'questions' => $quiz->questions,
        ]);
    }

    /**
     * Toggle or update quiz visibility (Public <-> Private).
     */
    public function toggleQuizVisibility(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
            'is_public' => 'required|boolean',
        ]);

        if (! Auth::check()) {
            return response()->json([
                'success' => false,
                'require_login' => true,
                'error' => __('Vui lòng đăng nhập để thay đổi quyền riêng tư của đề thi.'),
            ], 401);
        }

        $code = trim(strtoupper($request->input('code')));
        $quiz = Quiz::where('code', $code)->first();
        if (! $quiz) {
            return response()->json(['success' => false, 'error' => __('Không tìm thấy đề thi.')], 404);
        }

        // If the quiz is unowned (e.g. created as guest or sample), claim it for the current logged-in user!
        if ($quiz->user_id === null) {
            $quiz->user_id = Auth::id();
            $quiz->save();
        }

        // If the quiz belongs to another user, auto-clone as a personal copy with requested visibility
        if ($quiz->user_id !== Auth::id()) {
            $clonedQuiz = new Quiz;
            $clonedQuiz->code = Quiz::generateCode();
            $clonedQuiz->user_id = Auth::id();
            $clonedQuiz->title = $quiz->title;
            $clonedQuiz->description = $quiz->description;
            $clonedQuiz->is_public = $request->boolean('is_public');
            $clonedQuiz->total_questions = $quiz->total_questions;
            $clonedQuiz->questions = $quiz->questions;
            $clonedQuiz->save();

            return response()->json([
                'success' => true,
                'cloned' => true,
                'code' => $clonedQuiz->code,
                'is_owner' => true,
                'is_public' => $clonedQuiz->is_public,
                'share_url' => route('tool.show', ['slug' => 'tao-de-trac-nghiem-tu-file']).'?code='.$clonedQuiz->code,
                'message' => __('Đề thi gốc thuộc về người khác. Hệ thống đã lưu một bản sao mới (Mã: :code) vào tài khoản của bạn!', ['code' => $clonedQuiz->code]),
            ]);
        }

        $quiz->is_public = $request->boolean('is_public');
        $quiz->save();

        return response()->json([
            'success' => true,
            'code' => $quiz->code,
            'is_owner' => true,
            'is_public' => $quiz->is_public,
            'share_url' => route('tool.show', ['slug' => 'tao-de-trac-nghiem-tu-file']).'?code='.$quiz->code,
            'message' => $quiz->is_public ? __('Đã chuyển sang chế độ Công Khai (Public).') : __('Đã chuyển sang chế độ Riêng Tư (Private).'),
        ]);
    }

    /**
     * Get list of quizzes created by authenticated user.
     */
    public function myQuizzes(): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json(['success' => true, 'quizzes' => []]);
        }

        $this->claimSessionQuizzes();

        $quizzes = Quiz::where('user_id', Auth::id())
            ->orderByDesc('id')
            ->limit(50)
            ->get(['id', 'code', 'title', 'is_public', 'total_questions', 'attempts_count', 'created_at']);

        return response()->json([
            'success' => true,
            'quizzes' => $quizzes->map(fn ($q) => [
                'id' => $q->id,
                'code' => $q->code,
                'title' => $q->title,
                'is_public' => $q->is_public,
                'total_questions' => $q->total_questions,
                'attempts_count' => $q->attempts_count,
                'created_at' => $q->created_at?->format('d/m/Y H:i'),
                'share_url' => route('tool.show', ['slug' => 'tao-de-trac-nghiem-tu-file']).'?code='.$q->code,
            ]),
        ]);
    }

    /**
     * Delete a saved quiz owned by the authenticated user.
     */
    public function deleteQuiz(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        if (! Auth::check()) {
            return response()->json([
                'success' => false,
                'require_login' => true,
                'error' => __('Vui lòng đăng nhập để xóa đề thi.'),
            ], 401);
        }

        $code = trim(strtoupper($request->input('code')));
        $quiz = Quiz::where('code', $code)->first();
        if (! $quiz) {
            return response()->json([
                'success' => true,
                'message' => __('Đã xóa đề thi thành công.'),
            ]);
        }

        // If quiz is unowned, allow logged-in user who has the code to delete it
        if ($quiz->user_id === null) {
            $quiz->delete();

            return response()->json([
                'success' => true,
                'message' => __('Đã xóa đề thi thành công.'),
            ]);
        }

        if ($quiz->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'error' => __('Bạn không có quyền xóa đề thi của người khác.')], 403);
        }

        $quiz->delete();

        return response()->json([
            'success' => true,
            'message' => __('Đã xóa đề thi thành công.'),
        ]);
    }
}
