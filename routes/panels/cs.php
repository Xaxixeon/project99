<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\CSController;

Route::get('/', [CSController::class, 'index'])->name('cs.dashboard');
Route::get('/pending', [CSController::class, 'pending'])->name('cs.pending');
Route::post('/assign/{order}', [CSController::class, 'setAssigned'])->name('cs.assign');
