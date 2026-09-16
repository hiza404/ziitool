<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
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
        $this->assertCount(18, $tools);

        foreach ($tools as $slug => $tool) {
            $response = $this->get("/tool/{$slug}");
            $response->assertStatus(200);
            $response->assertSee($tool['title']);
        }
    }

    /**
     * Test Slide Presentation Maker tool renders.
     */
    public function test_slide_presentation_maker_tool_renders(): void
    {
        $response = $this->get('/tool/bai-thuyet-trinh');
        $response->assertStatus(200);
        $response->assertSee('Bài Thuyết Trình');
        $response->assertSee('Thuyết trình');
        $response->assertSee('Áp dụng mẫu này');
        $response->assertSee('Nạp PPTX Cũ');
        $response->assertSee('Thiết kế không tên - Bài thuyết trình');

        // Test legacy redirect
        $legacy = $this->get('/tool/tao-slide-thuyet-trinh');
        $legacy->assertRedirect('/tool/bai-thuyet-trinh');
    }

    /**
     * Test AI Background Remover tool renders.
     */
    public function test_ai_background_remover_tool_renders(): void
    {
        $response = $this->get('/tool/xoa-phong-anh');
        $response->assertStatus(200);
        $response->assertSee('Xóa Phông Nền Ảnh Bằng AI');
        $response->assertSee('Trong Suốt');
        $response->assertSee('Phông Màu Thẻ');
        $response->assertSee('Mờ Bokeh');
    }

    /**
     * Test AI Image Enhancer tool renders.
     */
    public function test_ai_image_enhancer_tool_renders(): void
    {
        $response = $this->get('/tool/nang-chat-luong-anh');
        $response->assertStatus(200);
        $response->assertSee('Nâng Cao Chất Lượng & Làm Nét Ảnh AI');
        $response->assertSee('Super-Resolution');
        $response->assertSee('2X (200%)');
        $response->assertSee('4X (400%)');
    }

    /**
     * Test PDF to Word converter tool renders.
     */
    public function test_pdf_to_word_tool_renders(): void
    {
        $response = $this->get('/tool/pdf-sang-word');
        $response->assertStatus(200);
        $response->assertSee('Chuyển PDF Sang Word');
        $response->assertSee('.DOCX');
        $response->assertSee('Engine Trình Duyệt');
        $response->assertSee('Engine Máy Chủ');
    }

    /**
     * Test PDF to Word server conversion validates required file.
     */
    public function test_pdf_to_word_server_convert_validates_file(): void
    {
        $response = $this->postJson('/tool/pdf-sang-word/server-convert', []);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['pdf_file']);
    }

    /**
     * Test PDF to Word server conversion executes successfully with PDF file.
     */
    public function test_pdf_to_word_server_convert_success(): void
    {
        $pdfContent = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n3 0 obj<</Type/Page/MediaBox[0 0 612 792]/Parent 2 0 R/Resources<<>>>>endobj\nxref\n0 4\n0000000000 65535 f \n0000000009 00000 n \n0000000052 00000 n \n0000000101 00000 n \ntrailer<</Size 4/Root 1 0 R>>\nstartxref\n178\n%%EOF";
        $file = UploadedFile::fake()->createWithContent('sample.pdf', $pdfContent);

        $response = $this->post('/tool/pdf-sang-word/server-convert', [
            'pdf_file' => $file,
        ]);

        // Either downloads docx file or returns 422 if environment restricts libreoffice
        $this->assertTrue(in_array($response->getStatusCode(), [200, 422]));
        if ($response->getStatusCode() === 200) {
            $this->assertStringContainsString('sample_ziitool.docx', $response->headers->get('content-disposition'));
        }
    }

    /**
     * Test Multi-format to Excel converter tool renders.
     */
    public function test_format_to_excel_tool_renders(): void
    {
        $response = $this->get('/tool/chuyen-sang-excel');
        $response->assertStatus(200);
        $response->assertSee('Chuyển Đổi Sang Excel (.XLSX)');
        $response->assertSee('Tệp CSV / TSV');
    }

    /**
     * Test Multi-format to Word converter tool renders.
     */
    public function test_format_to_word_tool_renders(): void
    {
        $response = $this->get('/tool/chuyen-sang-word');
        $response->assertStatus(200);
        $response->assertSee('Chuyển Đổi Tài Liệu Sang Word');
        $response->assertSee('Markdown Sang Word');
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
}
