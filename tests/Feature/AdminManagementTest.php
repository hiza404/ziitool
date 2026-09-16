<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\ProLicense;
use App\Models\Setting;
use App\Models\ToolOverride;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::firstOrCreate(
            ['email' => 'admin@microtools.com'],
            [
                'name' => 'Quản Trị Viên',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
            ]
        );
    }

    /**
     * Test guest cannot access admin dashboard.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/admin/login');
    }

    /**
     * Test admin login page renders.
     */
    public function test_admin_login_page_renders(): void
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
        $response->assertSee('ZiiTool Quản Trị');
    }

    /**
     * Test admin login with valid credentials.
     */
    public function test_admin_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@microtools.com',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($this->adminUser);
    }

    /**
     * Test non-admin user cannot access admin panel.
     */
    public function test_non_admin_cannot_access_admin_panel(): void
    {
        $normalUser = User::factory()->create([
            'is_admin' => false,
        ]);

        $response = $this->actingAs($normalUser)->get('/admin');
        $response->assertRedirect('/admin/login');
    }

    /**
     * Test admin dashboard renders with metrics.
     */
    public function test_admin_dashboard_renders(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Bảng Điều Khiển');
    }

    /**
     * Test admin tools list and toggle tool status.
     */
    public function test_admin_can_manage_tools(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/tools');
        $response->assertStatus(200);
        $response->assertSee('Danh Sách 12 Công Cụ');

        // Toggle tool
        $toggleRes = $this->actingAs($this->adminUser)->postJson('/admin/tools/nen-anh/toggle');
        $toggleRes->assertStatus(200);
        $toggleRes->assertJson(['success' => true]);

        $override = ToolOverride::where('slug', 'nen-anh')->first();
        $this->assertNotNull($override);
        $this->assertFalse($override->is_active);

        // Verify inactive tool is excluded or blocked
        $toolPageRes = $this->get('/tool/nen-anh');
        $toolPageRes->assertStatus(404);

        // Re-enable
        $this->actingAs($this->adminUser)->postJson('/admin/tools/nen-anh/toggle');
        $this->assertTrue(ToolOverride::where('slug', 'nen-anh')->first()->is_active);
    }

    /**
     * Test admin can update AdSense configuration.
     */
    public function test_admin_can_update_adsense_settings(): void
    {
        $response = $this->actingAs($this->adminUser)->post('/admin/adsense', [
            'ads_enabled' => '1',
            'ads_demo_mode' => '1',
            'adsense_client_id' => 'ca-pub-1111222233334444',
            'ads_slot_top' => '998877',
            'announcement_banner' => 'Test Announcement Banner',
        ]);

        $response->assertRedirect();
        $this->assertEquals('ca-pub-1111222233334444', Setting::get('adsense_client_id'));
        $this->assertEquals('Test Announcement Banner', Setting::get('announcement_banner'));
    }

    /**
     * Test admin can update VietQR bank settings and pricing.
     */
    public function test_admin_can_update_vietqr_and_pricing(): void
    {
        $bankRes = $this->actingAs($this->adminUser)->post('/admin/vietqr/bank', [
            'bank_code' => 'VCB',
            'account_number' => '123456789',
            'account_name' => 'NGUYEN VAN B',
        ]);
        $bankRes->assertRedirect();
        $this->assertEquals('VCB', Setting::get('vietqr_bank_code'));
        $this->assertEquals('123456789', Setting::get('vietqr_account_number'));

        $priceRes = $this->actingAs($this->adminUser)->post('/admin/vietqr/price', [
            'price_monthly' => 59000,
            'price_yearly' => 450000,
        ]);
        $priceRes->assertRedirect();
        $this->assertEquals(59000, Setting::get('price_monthly'));
    }

    /**
     * Test admin can generate and toggle Pro License Keys.
     */
    public function test_admin_can_generate_and_toggle_pro_licenses(): void
    {
        $createRes = $this->actingAs($this->adminUser)->post('/admin/vietqr/license', [
            'custom_code' => 'TEST-CUSTOM-KEY-2026',
            'plan' => 'yearly',
            'customer_name' => 'Công Ty XYZ',
        ]);
        $createRes->assertRedirect();

        $license = ProLicense::where('code', 'TEST-CUSTOM-KEY-2026')->first();
        $this->assertNotNull($license);
        $this->assertTrue($license->is_active);

        // Test public user can activate this key
        $verifyRes = $this->postJson('/payment/verify-license', [
            'license_code' => 'TEST-CUSTOM-KEY-2026',
        ]);
        $verifyRes->assertStatus(200);
        $verifyRes->assertJson(['success' => true, 'is_pro' => true]);

        // Toggle license to deactivate
        $toggleRes = $this->actingAs($this->adminUser)->postJson("/admin/vietqr/license/{$license->id}/toggle");
        $toggleRes->assertStatus(200);
        $this->assertFalse(ProLicense::find($license->id)->is_active);
    }

    /**
     * Test admin can update order status.
     */
    public function test_admin_can_update_order_status(): void
    {
        $order = Order::create([
            'order_code' => 'PRO_TEST_123',
            'plan' => 'monthly',
            'amount' => 49000,
            'bank_code' => 'MB',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->adminUser)->post("/admin/vietqr/order/{$order->id}/status", [
            'status' => 'paid',
        ]);
        $response->assertRedirect();
        $this->assertEquals('paid', Order::find($order->id)->status);
    }

    /**
     * Test admin can update system settings and clear cache.
     */
    public function test_admin_can_update_settings_and_clear_cache(): void
    {
        $updateRes = $this->actingAs($this->adminUser)->post('/admin/settings', [
            'site_name' => 'Super Tools Platform',
            'site_tagline' => 'Tiện ích tốt nhất',
            'meta_description' => 'Mô tả mới',
            'contact_email' => 'admin@microtools.com',
        ]);
        $updateRes->assertRedirect();
        $this->assertEquals('Super Tools Platform', Setting::get('site_name'));

        $cacheRes = $this->actingAs($this->adminUser)->post('/admin/settings/clear-cache');
        $cacheRes->assertRedirect();
    }

    /**
     * Test admin logout.
     */
    public function test_admin_logout(): void
    {
        $response = $this->actingAs($this->adminUser)->post('/admin/logout');
        $response->assertRedirect('/admin/login');
        $this->assertGuest();
    }
}
