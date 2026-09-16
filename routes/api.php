<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    Route::get('/tools', [ApiController::class, 'listTools'])->name('api.tools');
    Route::post('/tax/calculate', [ApiController::class, 'calculateTax'])->name('api.tax');
    Route::post('/interest/calculate', [ApiController::class, 'calculateInterest'])->name('api.interest');
    Route::post('/hash', [ApiController::class, 'hash'])->name('api.hash');
});
