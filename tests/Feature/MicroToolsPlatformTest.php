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
        $response->assertSee('MicroTools');
    }

    /**
     * Test all 12 tool routes render with 200 OK.
     */
    public function test_all_12_tools_render_successfully(): void
    {
        $tools = config('tools.list', []);
        $this->assertCount(12, $tools);

        foreach ($tools as $slug => $tool) {
            $response = $this->get("/tool/{$slug}");
            $response->assertStatus(200);
            $response->assertSee($tool['title']);
        }
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
