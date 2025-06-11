<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('payment')->group(function () {
    Route::post('qris/create', [PaymentController::class, 'createQrisPayment']);
    Route::post('transfer/create', [PaymentController::class, 'createTransferPayment']);
    Route::post('cash/create', [PaymentController::class, 'createCashPayment']);
    Route::get('status/{paymentId}', [PaymentController::class, 'getPaymentStatus']);
});
Route::post('xendit/webhook', [PaymentController::class, 'handleXenditWebhook']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
