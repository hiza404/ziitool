<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MicroToolsPlatformTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test home page returns 200 and has title.
     */
    public function test_home_page_renders_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('ZiiTool');
    }

    /**
     * Test all registered tool routes render with 200 OK.
     */
    public function test_all_tools_render_successfully(): void
    {
        $tools = config('tools.list', []);
        $this->assertCount(15, $tools);

        foreach ($tools as $slug => $tool) {
            $response = $this->get("/tool/{$slug}");
            $response->assertStatus(200);
            $response->assertSee($tool['title']);
        }
    }

    /**
     * Test AI Background Remover & Replacer tool renders.
     */
    public function test_ai_background_remover_tool_renders(): void
    {
        $response = $this->get('/tool/xoa-phong-anh');
        $response->assertStatus(200);
        $response->assertSee('Xóa Phông & Đổi Nền Ảnh Bằng AI');
        $response->assertSee('Trong Suốt');
        $response->assertSee('Phông Màu Thẻ');
        $response->assertSee('Mờ Bokeh');
        $response->assertSee('Ghép Nền Mới');

        // Test alias redirect
        $alias = $this->get('/tool/doi-phong-anh');
        $alias->assertRedirect('/tool/xoa-phong-anh');
    }

    /**
     * Test SnapTik TikTok & Multi-Platform video downloader tool renders.
     */
    public function test_tiktok_downloader_tool_renders(): void
    {
        $response = $this->get('/tool/tai-video-tiktok');
        $response->assertStatus(200);
        $response->assertSee('Tải Video Đa Nền Tảng (TikTok, YouTube, Facebook)');
        $response->assertSee('Tải Xuống');
        $response->assertSee('Tải Video Không Logo (HD)');
        $response->assertSee('Tải Âm Thanh Gốc (MP3)');

        // Test alias redirects
        $alias = $this->get('/tool/snaptik');
        $alias->assertRedirect('/tool/tai-video-tiktok');

        $aliasYt = $this->get('/tool/tai-video-youtube');
        $aliasYt->assertRedirect('/tool/tai-video-tiktok');

        $aliasFb = $this->get('/tool/tai-video-facebook');
        $aliasFb->assertRedirect('/tool/tai-video-tiktok');

        $aliasMulti = $this->get('/tool/tai-video-da-nen-tang');
        $aliasMulti->assertRedirect('/tool/tai-video-tiktok');
    }

    /**
     * Test Video parse API validates required URL and format.
     */
    public function test_tiktok_downloader_parse_api_validations(): void
    {
        $empty = $this->postJson('/tool/tai-video-tiktok/parse', []);
        $empty->assertStatus(422);

        $invalid = $this->postJson('/tool/tai-video-tiktok/parse', ['url' => 'https://twitter.com/someuser/status/123']);
        $invalid->assertStatus(422);
        $invalid->assertJsonFragment(['success' => false]);
    }

    /**
     * Test Video direct download attachment route.
     */
    public function test_video_downloader_streaming_attachment_headers(): void
    {
        $response = $this->get('/tool/video/download?url=https://example.com/sample.mp4&title=Test+Video&platform=tiktok&ext=mp4');
        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', 'attachment; filename="Ziitool_tiktok_test-video.mp4"');
    }

    /**
     * Test Pricing & VietQR page renders.
     */
    public function test_pricing_page_renders(): void
    {
        $response = $this->get('/pricing');
        $response->assertStatus(200);
        $response->assertSee('VietQR');
    }

    /**
     * Test VietQR dynamic generation endpoint.
     */
    public function test_vietqr_endpoint_generates_valid_payload(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/payment/vietqr', [
            'plan' => 'monthly',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'amount' => 49000,
        ]);
        $response->assertJsonStructure(['qr_image_url', 'order_code', 'memo']);
    }

    /**
     * Test License Code Verification.
     */
    public function test_license_code_verification(): void
    {
        // Valid code
        $validResponse = $this->postJson('/payment/verify-license', [
            'license_code' => 'PRO-SUPER-2026',
        ]);
        $validResponse->assertStatus(200);
        $validResponse->assertJson(['success' => true, 'is_pro' => true]);

        // Invalid code
        $invalidResponse = $this->postJson('/payment/verify-license', [
            'license_code' => 'WRONG-KEY-XYZ',
        ]);
        $invalidResponse->assertStatus(422);
        $invalidResponse->assertJson(['success' => false]);
    }

    /**
     * Test SEO sitemap and robots.
     */
    public function test_seo_sitemap_and_robots(): void
    {
        $sitemapRes = $this->get('/sitemap.xml');
        $sitemapRes->assertStatus(200);
        $sitemapRes->assertHeader('Content-Type', 'application/xml');
        $sitemapRes->assertSee('<urlset', false);

        $robotsRes = $this->get('/robots.txt');
        $robotsRes->assertStatus(200);
        $robotsRes->assertSee('User-agent: *');
        $robotsRes->assertSee('sitemap.xml');
    }

    /**
     * Test Backend API for Tax Calculation.
     */
    public function test_api_tax_calculation(): void
    {
        $response = $this->postJson('/api/v1/tax/calculate', [
            'income' => 20000000,
            'dependents' => 0,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
        ]);
        $response->assertJsonStructure([
            'data' => [
                'gross_income',
                'insurance',
                'deductions',
                'taxable_income',
                'tax',
                'net_income',
            ],
        ]);
    }

    /**
     * Test Backend API for Compound Interest.
     */
    public function test_api_compound_interest(): void
    {
        $response = $this->postJson('/api/v1/interest/calculate', [
            'principal' => 10000000,
            'monthly_deposit' => 1000000,
            'annual_rate' => 10,
            'years' => 3,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
        ]);
        $response->assertJsonStructure([
            'data' => [
                'final_balance',
                'total_deposited',
                'total_profit',
                'timeline',
            ],
        ]);
    }

    /**
     * Test Backend API for Hashing.
     */
    public function test_api_hashing(): void
    {
        $response = $this->postJson('/api/v1/hash', [
            'text' => 'MicroTools2026',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'data' => [
                'input' => 'MicroTools2026',
                'md5' => md5('MicroTools2026'),
            ],
        ]);
    }

    /**
     * Test Language Switcher Route and Session persistence.
     */
    public function test_language_switch_route_sets_locale_in_session(): void
    {
        $response = $this->get('/lang/en');
        $response->assertRedirect('/');
        $response->assertSessionHas('locale', 'en');

        $viResponse = $this->get('/lang/vi');
        $viResponse->assertRedirect('/');
        $viResponse->assertSessionHas('locale', 'vi');
    }

    /**
     * Test English translations render on home page.
     */
    public function test_english_locale_translates_home_page(): void
    {
        $response = $this->withSession(['locale' => 'en'])->get('/');
        $response->assertStatus(200);
        $response->assertSee('Online Productivity Tools');
        $response->assertSee('All Tools');
        $response->assertSee('100% Free');
    }

    /**
     * Test ziigames.online link is present, admin links and version text are removed from public layout.
     */
    public function test_ziigames_link_rendered_and_admin_version_removed(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('ziigames.online');
        $response->assertSee('favicon.svg');
        $response->assertSee('favicon.ico');
        $response->assertSee('id="mobileDrawer"', false);
        $response->assertDontSee('Phiên bản v2.0');
        $response->assertDontSee('/admin/login');
        $response->assertDontSee('/admin/dashboard');
    }

    /**
     * Test SEO Rich Snippets, Crawlers, and Googlebot directives.
     */
    public function test_seo_metadata_and_rich_snippets_rendered(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('name="googlebot"', false);
        $response->assertSee('hreflang="vi"', false);
        $response->assertSee('hreflang="en"', false);
        $response->assertSee('SearchAction', false);

        // Tool page rich snippet with AggregateRating
        $toolResponse = $this->get('/tool/tai-video-tiktok');
        $toolResponse->assertStatus(200);
        $toolResponse->assertSee('AggregateRating', false);
        $toolResponse->assertSee('BreadcrumbList', false);
    }

    /**
     * Test Quiz Maker tool renders and aliases work.
     */
    public function test_quiz_tool_renders_and_aliases_work(): void
    {
        $response = $this->get('/tool/tao-de-trac-nghiem-tu-file');
        $response->assertStatus(200);
        $response->assertSee('Tạo Đề Trắc Nghiệm Từ File');
        $response->assertSee('Gemini');
        $response->assertSee('Bắt Đầu Làm Bài Thi');

        // Test alias redirect
        $aliasResponse = $this->get('/tool/trac-nghiem-online');
        $aliasResponse->assertRedirect('/tool/tao-de-trac-nghiem-tu-file');
    }

    /**
     * Test Quiz sample API returns questions.
     */
    public function test_quiz_sample_api(): void
    {
        $response = $this->get('/tool/trac-nghiem/sample');
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $data = $response->json();
        $this->assertNotEmpty($data['questions']);
        $this->assertEquals('C', $data['questions'][0]['correct']);
    }

    /**
     * Test Quiz parse API with raw text.
     */
    public function test_quiz_parse_api_with_text(): void
    {
        $rawText = "Câu 1: Mặt trời mọc ở hướng nào?\nA. Tây\nB. Đông\nC. Nam\nD. Bắc\nĐáp án: B\n\nCâu 2: Một tuần có mấy ngày?\nA. 5 ngày\nB. 6 ngày\nC. 7 ngày\nD. 8 ngày\nĐáp án: C";

        $response = $this->post('/tool/trac-nghiem/parse', [
            'text' => $rawText,
            'mode' => 'local',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'total_questions' => 2,
        ]);

        $data = $response->json();
        $this->assertEquals('B', $data['questions'][0]['correct']);
        $this->assertEquals('C', $data['questions'][1]['correct']);
    }
}
