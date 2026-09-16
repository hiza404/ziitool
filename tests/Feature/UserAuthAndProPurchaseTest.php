<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\ProLicense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAuthAndProPurchaseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest can view login and register pages.
     */
    public function test_guest_can_view_login_and_register_pages(): void
    {
        $loginRes = $this->get('/login');
        $loginRes->assertStatus(200);
        $loginRes->assertSee('Đăng Nhập Tài Khoản');

        $registerRes = $this->get('/register');
        $registerRes->assertStatus(200);
        $registerRes->assertSee('Đăng Ký Tài Khoản Mới');
    }

    /**
     * Test user can register successfully and is automatically authenticated.
     */
    public function test_user_can_register_successfully(): void
    {
        $response = $this->post('/register', [
            'name' => 'Nguyễn Văn Minh',
            'email' => 'minh@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'redirect' => '/pricing',
        ]);

        $response->assertRedirect('/pricing');
        $this->assertAuthenticated();

        $user = User::where('email', 'minh@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Nguyễn Văn Minh', $user->name);
        $this->assertFalse($user->is_pro);
        $this->assertFalse($user->is_admin);
    }

    /**
     * Test user registration validation rules.
     */
    public function test_user_registration_validation(): void
    {
        // Existing email
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->post('/register', [
            'name' => '',
            'email' => 'existing@example.com',
            'password' => 'short',
            'password_confirmation' => 'mismatch',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
        $this->assertGuest();
    }

    /**
     * Test user login and logout.
     */
    public function test_user_can_login_and_logout(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($user);

        // Logout
        $logoutRes = $this->post('/logout');
        $logoutRes->assertRedirect('/');
        $this->assertGuest();
    }

    /**
     * Test guest CANNOT generate VietQR order without logging in.
     */
    public function test_guest_cannot_generate_vietqr_without_login(): void
    {
        $response = $this->postJson('/payment/vietqr', [
            'plan' => 'monthly',
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'success' => false,
            'require_auth' => true,
        ]);
    }

    /**
     * Test authenticated user can generate VietQR order linked to their account.
     */
    public function test_authenticated_user_can_generate_vietqr_order(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/payment/vietqr', [
            'plan' => 'monthly',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'order_code',
            'amount',
            'qr_image_url',
        ]);

        $orderCode = $response->json('order_code');
        $order = Order::where('order_code', $orderCode)->first();
        $this->assertNotNull($order);
        $this->assertEquals($user->id, $order->user_id);
        $this->assertEquals('pending', $order->status);
    }

    /**
     * Test confirming payment activates Pro on user account.
     */
    public function test_user_can_confirm_payment_and_activate_pro(): void
    {
        $user = User::factory()->create();

        $order = Order::create([
            'user_id' => $user->id,
            'order_code' => 'PROTEST999',
            'plan' => 'yearly',
            'amount' => 399000,
            'bank_code' => 'MB',
            'status' => 'pending',
            'customer_name' => $user->name,
            'customer_phone' => $user->email,
        ]);

        $response = $this->actingAs($user)->postJson('/payment/confirm', [
            'order_code' => 'PROTEST999',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $user->refresh();
        $this->assertTrue($user->is_pro);
        $this->assertEquals('yearly', $user->pro_plan);
        $this->assertNotNull($user->pro_expires_at);

        $order->refresh();
        $this->assertEquals('paid', $order->status);

        $license = ProLicense::where('user_id', $user->id)->first();
        $this->assertNotNull($license);
        $this->assertTrue($license->is_active);
    }

    /**
     * Test user can redeem license key in account page.
     */
    public function test_user_can_redeem_license_key(): void
    {
        $user = User::factory()->create();

        $license = ProLicense::create([
            'code' => 'PRO-TEST-REDEEM-KEY',
            'plan' => 'yearly',
            'is_active' => true,
            'expires_at' => now()->addYear(),
        ]);

        $response = $this->actingAs($user)->post('/account/redeem', [
            'license_code' => 'PRO-TEST-REDEEM-KEY',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue($user->is_pro);
        $this->assertEquals('yearly', $user->pro_plan);

        $license->refresh();
        $this->assertEquals($user->id, $license->user_id);
        $this->assertNotNull($license->used_at);
    }

    /**
     * Test guest cannot access account page.
     */
    public function test_guest_cannot_access_account_page(): void
    {
        $response = $this->get('/account');
        $response->assertRedirect('/login');
    }

    /**
     * Test authenticated user can view account page with details.
     */
    public function test_authenticated_user_can_view_account_page(): void
    {
        $user = User::factory()->pro()->create([
            'name' => 'Vip Member User',
        ]);

        $response = $this->actingAs($user)->get('/account');
        $response->assertStatus(200);
        $response->assertSee('Vip Member User');
        $response->assertSee('Gói Pro Không Giới Hạn');
    }
}
