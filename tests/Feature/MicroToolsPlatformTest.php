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

    /**
     * Test Quiz parse automatically removes footers, watermarks, emails, and page numbers.
     */
    public function test_quiz_parse_removes_footers_and_watermarks(): void
    {
        $dirtyText = "Câu 1: Thủ đô của Việt Nam là gì?\nA. Hà Nội\nB. Đà Nẵng\nDownloaded by Khanh Linh Vuong (vuongkhanhlinhcute@gmail.com)\nlOMoARcPSD|35141182\nScan to open on Studocu\nTrang 1 / 10\nC. TP Hồ Chí Minh\nD. Cần Thơ\nĐáp án: A";

        $response = $this->post('/tool/trac-nghiem/parse', [
            'text' => $dirtyText,
            'mode' => 'local',
        ]);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertEquals(1, $data['total_questions']);

        $q = $data['questions'][0];
        $this->assertEquals('A', $q['correct']);
        $this->assertEquals('Hà Nội', $q['options']['A']);
        $this->assertEquals('Đà Nẵng', $q['options']['B']);
        $this->assertEquals('TP Hồ Chí Minh', $q['options']['C']);
        $this->assertEquals('Cần Thơ', $q['options']['D']);

        // Assert no watermark leak in options or question
        $allText = $q['question'].' '.implode(' ', $q['options']);
        $this->assertStringNotContainsString('Downloaded', $allText);
        $this->assertStringNotContainsString('Studocu', $allText);
        $this->assertStringNotContainsString('lOMoARcPSD', $allText);
        $this->assertStringNotContainsString('gmail.com', $allText);
        $this->assertStringNotContainsString('Trang 1', $allText);
    }

    /**
     * Test Quiz parse handles answer tables and protects math integration constants.
     */
    public function test_quiz_parse_with_answer_table_and_math_constant(): void
    {
        $text = "Câu 1. Cho hàm số có nguyên hàm F(x) + C. Khẳng định đúng là:\n A. F(x) + 1\n B. F(x) + 2\n C. F(x) + 3\n D. F(x) + 4\n\nCâu 2. Giá trị biểu thức bằng:\n A. 1\n B. 2\n C. 3\n D. 4\n\nBẢNG ĐÁP ÁN\n1 | C\n2 | B\n";

        $response = $this->post('/tool/trac-nghiem/parse', [
            'text' => $text,
            'mode' => 'local',
        ]);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertEquals(2, $data['total_questions']);
        $this->assertEquals('C', $data['questions'][0]['correct']);
        $this->assertEquals('B', $data['questions'][1]['correct']);
        $this->assertEquals('F(x) + 1', $data['questions'][0]['options']['A']);
        $this->assertEquals('F(x) + 3', $data['questions'][0]['options']['C']);
    }

    /**
     * Test saving public quiz generates unique code and shareable URL.
     */
    public function test_save_public_quiz_generates_code_and_share_url(): void
    {
        $payload = [
            'title' => 'Đề Thi Thử Địa Lý',
            'is_public' => true,
            'questions' => [
                [
                    'id' => 1,
                    'question' => 'Thủ đô của Pháp là gì?',
                    'options' => ['A' => 'Paris', 'B' => 'London', 'C' => 'Berlin', 'D' => 'Rome'],
                    'correct' => 'A',
                ],
            ],
        ];

        $response = $this->postJson('/tool/trac-nghiem/save', $payload);
        $response->assertStatus(200);
        $data = $response->json();

        $this->assertTrue($data['success']);
        $this->assertStringStartsWith('ZT-', $data['code']);
        $this->assertTrue($data['is_public']);
        $this->assertStringContainsString($data['code'], $data['share_url']);

        // Load quiz by code as guest
        $loadResp = $this->getJson('/tool/trac-nghiem/load/'.$data['code']);
        $loadResp->assertStatus(200);
        $loadData = $loadResp->json();
        $this->assertEquals('Đề Thi Thử Địa Lý', $loadData['title']);
        $this->assertCount(1, $loadData['questions']);
    }

    /**
     * Test saving private quiz requires authentication and enforces access control.
     */
    public function test_private_quiz_requires_auth_and_enforces_permissions(): void
    {
        $payload = [
            'title' => 'Đề Thi Riêng Tư Khách Sạn',
            'is_public' => false,
            'questions' => [
                [
                    'id' => 1,
                    'question' => 'Câu hỏi bí mật?',
                    'options' => ['A' => 'Đúng', 'B' => 'Sai'],
                    'correct' => 'A',
                ],
            ],
        ];

        // Guest cannot save private quiz
        $guestSave = $this->postJson('/tool/trac-nghiem/save', $payload);
        $guestSave->assertStatus(401);
        $guestSave->assertJson(['require_login' => true]);

        // Create user and save private quiz
        $user = User::factory()->create();
        $userSave = $this->actingAs($user)->postJson('/tool/trac-nghiem/save', $payload);
        $userSave->assertStatus(200);
        $code = $userSave->json('code');
        $this->assertNotEmpty($code);

        // Guest cannot load private quiz
        $this->app['auth']->logout();
        $guestLoad = $this->getJson('/tool/trac-nghiem/load/'.$code);
        $guestLoad->assertStatus(403);
        $guestLoad->assertJson(['require_login' => true]);

        // Another user cannot load private quiz
        $otherUser = User::factory()->create();
        $otherLoad = $this->actingAs($otherUser)->getJson('/tool/trac-nghiem/load/'.$code);
        $otherLoad->assertStatus(403);

        // Owner can load private quiz
        $ownerLoad = $this->actingAs($user)->getJson('/tool/trac-nghiem/load/'.$code);
        $ownerLoad->assertStatus(200);
        $ownerData = $ownerLoad->json();
        $this->assertTrue($ownerData['is_owner']);
        $this->assertFalse($ownerData['is_public']);

        // Owner can toggle visibility to public
        $toggleResp = $this->actingAs($user)->postJson('/tool/trac-nghiem/visibility', [
            'code' => $code,
            'is_public' => true,
        ]);
        $toggleResp->assertStatus(200);
        $this->assertTrue($toggleResp->json('is_public'));

        // Now guest can load it
        $this->app['auth']->logout();
        $guestLoadAfter = $this->getJson('/tool/trac-nghiem/load/'.$code);
        $guestLoadAfter->assertStatus(200);
    }

    /**
     * Test authenticated user can list their quizzes and delete them.
     */
    public function test_user_my_quizzes_and_deletion(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->postJson('/tool/trac-nghiem/save', [
            'title' => 'Đề Toán Học Đại Số',
            'is_public' => true,
            'questions' => [
                ['id' => 1, 'question' => '1 + 1 = ?', 'options' => ['A' => '2', 'B' => '3'], 'correct' => 'A'],
            ],
        ]);

        $listResp = $this->actingAs($user)->getJson('/tool/trac-nghiem/my-quizzes');
        $listResp->assertStatus(200);
        $quizzes = $listResp->json('quizzes');
        $this->assertCount(1, $quizzes);
        $code = $quizzes[0]['code'];

        // Delete quiz
        $delResp = $this->actingAs($user)->postJson('/tool/trac-nghiem/delete', ['code' => $code]);
        $delResp->assertStatus(200);
        $this->assertTrue($delResp->json('success'));

        // Verify quiz is deleted
        $afterList = $this->actingAs($user)->getJson('/tool/trac-nghiem/my-quizzes');
        $this->assertCount(0, $afterList->json('quizzes'));
    }
}
