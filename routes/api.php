<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\ChargeController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SubscriptionsController;
use App\Http\Controllers\WebhookController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('plan')->group(function () {

    Route::post('/create', [PlanController::class, 'create']);
    Route::post('/edit', [PlanController::class, 'edit']);
    Route::delete('/delete', [PlanController::class, 'delete']);

});

Route::prefix('customer')->group(function () {

    Route::post('/create', [CustomerController::class, 'create']);
    Route::get('/{id}', [CustomerController::class, 'search']);
    Route::post('/edit', [CustomerController::class, 'edit']);

});

Route::prefix('subscription')->group(function () {

    Route::post('/create', [SubscriptionsController::class, 'create']);
    Route::delete('/cancel', [SubscriptionsController::class, 'cancel']);
    Route::post('/search', [SubscriptionsController::class, 'search']);
    Route::post('/create-planless', [SubscriptionsController::class, 'planless']);

});

Route::prefix('webhook')->group(function () {

    Route::post('/create', [WebhookController::class]);

});

Route::prefix('order')->group(function () {

    Route::post('create', [OrderController::class, 'create']);
    Route::post('charge', [OrderController::class, 'charge']);
    Route::get('search', [ChargeController::class, 'search']);
    Route::delete('/cancel', [ChargeController::class, 'cancel']);
    Route::post('/retry', [ChargeController::class, 'retry']);

});

Route::prefix('checkout')->group(function () {

    Route::post('create', [CheckoutController::class, 'create']);
});

Route::prefix('card')->group(function () {

    Route::post('create', [CardController::class, 'create']);
    Route::post('search', [CardController::class, 'search']);
    Route::post('edit', [CardController::class, 'edit']);
    Route::delete('delete', [CardController::class, 'delete']);
    Route::post('search-one', [CardController::class, 'searchOne']);
    Route::post('create-token', [CardController::class, 'createToken']);

});
