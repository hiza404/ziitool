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
        $fullTitle = $title.' - '.$siteName;
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

        // SoftwareApplication / WebApplication Schema
        if (! empty($data['title'])) {
            $schemas[] = [
                '@context' => 'https://schema.org',
                '@type' => 'WebApplication',
                'name' => $data['title'],
                'url' => $url,
                'description' => $data['description'] ?? '',
                'applicationCategory' => 'UtilitiesApplication',
                'operatingSystem' => 'All',
                'browserRequirements' => 'Requires JavaScript. Requires HTML5.',
                'offers' => [
                    '@type' => 'Offer',
                    'price' => '0',
                    'priceCurrency' => 'VND',
                ],
            ];
        }

        // HowTo Schema if steps provided
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

        // FAQPage Schema if faq provided
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
