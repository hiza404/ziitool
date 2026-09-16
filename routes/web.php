<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Homepage - Danh sách toàn bộ công cụ & tìm kiếm
Route::get('/', [ToolController::class, 'index'])->name('home');

// Trang chi tiết từng công cụ tiện ích
Route::get('/tool/{slug}', [ToolController::class, 'show'])->name('tool.show');

// Gói Pro & Thanh toán VietQR
Route::get('/pricing', [PaymentController::class, 'pricing'])->name('pricing');
Route::post('/payment/vietqr', [PaymentController::class, 'generateVietQr'])->name('payment.vietqr');
Route::post('/payment/verify-license', [PaymentController::class, 'verifyProCode'])->name('payment.verify_license');

// Tài liệu API cho Developer
Route::get('/api-docs', [ApiController::class, 'docs'])->name('api.docs');

// SEO Routes
Route::get('/sitemap.xml', [ToolController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [ToolController::class, 'robots'])->name('robots');
