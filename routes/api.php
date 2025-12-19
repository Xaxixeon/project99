<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController as ApiOrderController;

Route::middleware('auth:staff')->group(function () {
    Route::patch('/orders/{order}/status', [ApiOrderController::class, 'updateStatus'])
        ->name('api.orders.status');
});
