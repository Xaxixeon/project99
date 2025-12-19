<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\ProductionController;

Route::get('/', [ProductionController::class, 'index'])->name('production.dashboard');
Route::post('/update/{order}', [ProductionController::class, 'updateStatus'])->name('production.update');
