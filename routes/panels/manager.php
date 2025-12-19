<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\ManagerController;

Route::get('/', [ManagerController::class, 'index'])->name('manager.dashboard');
