<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\CashierController;

Route::get('/', [CashierController::class, 'index'])->name('cashier.dashboard');
Route::post('/pay/{order}', [CashierController::class, 'pay'])->name('cashier.pay');
