<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\AdSenseController as AdminAdSenseController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\ToolManageController as AdminToolManageController;
use App\Http\Controllers\Admin\VietQrManageController as AdminVietQrManageController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PaymentController;
/*
|--------------------------------------------------------------------------
| Public Web Routes
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\ToolController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Homepage - Danh sách toàn bộ công cụ & tìm kiếm
Route::get('/', [ToolController::class, 'index'])->name('home');

// Trang chi tiết từng công cụ tiện ích
Route::get('/tool/{slug}', [ToolController::class, 'show'])->name('tool.show');
Route::post('/tool/tai-video-da-nen-tang/parse', [ToolController::class, 'parseVideo'])->name('tool.video.parse');
Route::post('/tool/tai-video-tiktok/parse', [ToolController::class, 'parseVideo'])->name('tool.tiktok.parse');
Route::get('/tool/video/download', [ToolController::class, 'downloadVideo'])->name('tool.video.download');

// Chuyển đổi ngôn ngữ Tiếng Việt & Tiếng Anh
Route::get('/lang/{locale}', function (Request $request, string $locale) {
    if (in_array($locale, ['vi', 'en'], true)) {
        session(['locale' => $locale]);
        cookie()->queue(cookie()->forever('locale', $locale));
    }

    $referer = $request->header('referer');
    if ($referer && ! str_contains($referer, '/lang/')) {
        return redirect()->to($referer);
    }

    return redirect()->route('home');
})->name('lang.switch');

// Gói Pro & Thanh toán VietQR
Route::get('/pricing', [PaymentController::class, 'pricing'])->name('pricing');
Route::post('/payment/vietqr', [PaymentController::class, 'generateVietQr'])->name('payment.vietqr');
Route::post('/payment/verify-license', [PaymentController::class, 'verifyProCode'])->name('payment.verify_license');

// Client Authentication
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User Account & Pro Management (Require Login)
Route::middleware('auth')->group(function () {
    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::post('/account/redeem', [AccountController::class, 'redeemLicense'])->name('account.redeem');
    Route::post('/payment/confirm', [PaymentController::class, 'confirmOrder'])->name('payment.confirm');
});

// Tài liệu API cho Developer
Route::get('/api-docs', [ApiController::class, 'docs'])->name('api.docs');

// SEO Routes
Route::get('/sitemap.xml', [ToolController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [ToolController::class, 'robots'])->name('robots');

/*
|--------------------------------------------------------------------------
| Admin Management Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Auth
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected Admin Panel
    Route::middleware('admin')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Tool Management
        Route::get('/tools', [AdminToolManageController::class, 'index'])->name('tools.index');
        Route::post('/tools/{slug}/toggle', [AdminToolManageController::class, 'toggleStatus'])->name('tools.toggle');
        Route::post('/tools/{slug}/update', [AdminToolManageController::class, 'update'])->name('tools.update');

        // AdSense & Monetization
        Route::get('/adsense', [AdminAdSenseController::class, 'index'])->name('adsense.index');
        Route::post('/adsense', [AdminAdSenseController::class, 'update'])->name('adsense.update');

        // VietQR & Pro Plans
        Route::get('/vietqr', [AdminVietQrManageController::class, 'index'])->name('vietqr.index');
        Route::post('/vietqr/bank', [AdminVietQrManageController::class, 'updateBank'])->name('vietqr.bank');
        Route::post('/vietqr/price', [AdminVietQrManageController::class, 'updatePrice'])->name('vietqr.price');
        Route::post('/vietqr/license', [AdminVietQrManageController::class, 'generateLicense'])->name('vietqr.license');
        Route::post('/vietqr/license/{id}/toggle', [AdminVietQrManageController::class, 'toggleLicense'])->name('vietqr.license.toggle');
        Route::post('/vietqr/order/{id}/status', [AdminVietQrManageController::class, 'updateOrderStatus'])->name('vietqr.order.status');

        // System & SEO Settings
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/clear-cache', [AdminSettingController::class, 'clearCache'])->name('settings.clear_cache');
    });
});
