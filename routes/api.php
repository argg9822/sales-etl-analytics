<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ImportController;
use App\Http\Controllers\Api\V1\SaleController;

Route::prefix('v1')->group(function () {
    Route::apiResource('/imports', ImportController::class);
    Route::get('/imports/{import}/errors', [ImportController::class, 'errors'])->name('imports.errors');
    Route::controller(SaleController::class)->group(function () {
        Route::get('reports/summary', 'summary');
    });
});
