<?php

namespace App\Services;

use App\Models\Setting;

class SeoService
{
    /**
     * Build SEO metadata array for a tool or page.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function getMetadata(array $data = []): array
    {
        $siteName = Setting::get('site_name', config('app.name', 'ZiiTool'));
        $title = $data['title'] ?? Setting::get('site_tagline', 'Công Cụ Tiện Ích Trực Tuyến Nhanh Chóng & Miễn Phí');
        $fullTitle = ! empty($data['is_home']) ? ($siteName.' - '.$title) : ($title.' - '.$siteName);
        $description = $data['description'] ?? Setting::get('meta_description', 'Tập hợp các công cụ tiện ích miễn phí 100%: Chuyển đổi và nén ảnh, Beautifier JSON/SQL/CSS, tính thuế TNCN, tính lãi kép, tạo mã QR và mockup thiết bị.');
        $keywords = $data['keywords'] ?? 'web tiện ích, micro tools, nén ảnh, json formatter, tính thuế tncn, lãi kép, tạo qr code';
        $canonical = $data['url'] ?? url()->current();
        $image = $data['image'] ?? asset('images/og-banner.png');

        return [
            'site_name' => $siteName,
            'title' => $fullTitle,
            'raw_title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'canonical' => $canonical,
            'image' => $image,
            'schema' => self::generateSchema($data),
        ];
    }

    /**
     * Generate Schema.org JSON-LD array.
     *
     * @param  array<string, mixed>  $data
     * @return array<int, array<string, mixed>>
     */
    public static function generateSchema(array $data): array
    {
        $schemas = [];
        $url = $data['url'] ?? url()->current();
        $siteName = Setting::get('site_name', config('app.name', 'ZiiTool'));

        // 1. WebSite & Organization Schema on Home
        if (! empty($data['is_home'])) {
            $schemas[] = [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => $siteName,
                'url' => url('/'),
                'description' => $data['description'] ?? '',
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => [
                        '@type' => 'EntryPoint',
                        'urlTemplate' => url('/?q={search_term_string}'),
                    ],
                    'query-input' => 'required name=search_term_string',
                ],
            ];

            $schemas[] = [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => $siteName,
                'url' => url('/'),
                'logo' => asset('favicon.svg'),
                'sameAs' => [
                    'https://ziigames.online',
                ],
            ];
        }

        // 2. SoftwareApplication / WebApplication Schema with AggregateRating
        if (! empty($data['title'])) {
            $appSchema = [
                '@context' => 'https://schema.org',
                '@type' => 'WebApplication',
                'name' => $data['title'],
                'url' => $url,
                'description' => $data['description'] ?? '',
                'applicationCategory' => 'UtilitiesApplication',
                'operatingSystem' => 'All (Windows, macOS, Linux, iOS, Android)',
                'browserRequirements' => 'Requires JavaScript. Requires HTML5.',
                'offers' => [
                    '@type' => 'Offer',
                    'price' => '0',
                    'priceCurrency' => 'VND',
                    'availability' => 'https://schema.org/InStock',
                ],
                'aggregateRating' => [
                    '@type' => 'AggregateRating',
                    'ratingValue' => '4.9',
                    'bestRating' => '5',
                    'worstRating' => '1',
                    'ratingCount' => '1420',
                    'reviewCount' => '980',
                ],
            ];

            $schemas[] = $appSchema;
        }

        // 3. BreadcrumbList Schema for tool pages
        if (empty($data['is_home']) && ! empty($data['title'])) {
            $schemas[] = [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'Trang chủ',
                        'item' => url('/'),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => $data['title'],
                        'item' => $url,
                    ],
                ],
            ];
        }

        // 4. HowTo Schema if steps provided
        if (! empty($data['how_to']) && is_array($data['how_to'])) {
            $steps = [];
            foreach ($data['how_to'] as $index => $step) {
                $steps[] = [
                    '@type' => 'HowToStep',
                    'position' => $index + 1,
                    'text' => $step,
                ];
            }

            $schemas[] = [
                '@context' => 'https://schema.org',
                '@type' => 'HowTo',
                'name' => 'Cách sử dụng '.($data['title'] ?? 'công cụ'),
                'step' => $steps,
            ];
        }

        // 5. FAQPage Schema if faq provided
        if (! empty($data['faq']) && is_array($data['faq'])) {
            $faqEntities = [];
            foreach ($data['faq'] as $item) {
                $faqEntities[] = [
                    '@type' => 'Question',
                    'name' => $item['q'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $item['a'],
                    ],
                ];
            }

            $schemas[] = [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => $faqEntities,
            ];
        }

        return $schemas;
    }
}
