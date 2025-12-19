<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\WarehouseController;

Route::get('/', [WarehouseController::class, 'index'])->name('warehouse.dashboard');
Route::post('/inventory/{product}/adjust', [WarehouseController::class, 'adjust'])->name('warehouse.adjust');
