<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\MarketingController;

Route::get('/', [MarketingController::class, 'index'])->name('marketing.dashboard');
