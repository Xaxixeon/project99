<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\DesignerController;

Route::get('/', [DesignerController::class, 'index'])->name('designer.dashboard');
Route::post('/upload', [DesignerController::class, 'upload'])->name('designer.upload');
Route::get('/revisions', [DesignerController::class, 'revisions'])->name('designer.revisions');
