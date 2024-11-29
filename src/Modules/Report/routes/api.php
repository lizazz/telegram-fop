<?php

use Illuminate\Support\Facades\Route;
use Modules\Report\Http\Controllers\ReportController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::prefix('v1/report')->group(function () {
    Route::post('/start', [ReportController::class, 'start']);
    Route::get('/getUpdates', [ReportController::class, 'getUpdates']);
    Route::post('/set-webhook', [ReportController::class, 'setWebhook']);
   // Route::apiResource('', ReportController::class)->names('report');
});
