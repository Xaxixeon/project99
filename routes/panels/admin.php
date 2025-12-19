<?php

use Illuminate\Support\Facades\Route;

// Dashboard
use App\Http\Controllers\Admin\DashboardController;

// Core Modules
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;

// Pricing Module
use App\Http\Controllers\Admin\PricingController;
use App\Http\Controllers\Admin\ProductVariantController;

// Printing Module
use App\Http\Controllers\Admin\PrintingMaterialController;
use App\Http\Controllers\Admin\PrintingFinishingController;

// Others
use App\Http\Controllers\Admin\InstansiController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CustomerSpecialPriceController;

// Dashboard root
Route::get('/', [DashboardController::class, 'index'])
    ->name('admin.dashboard');

// PRODUCT & ORDER CRUD
Route::resource('products', ProductController::class)
    ->names('admin.products');

Route::resource('orders', OrderController::class)
    ->names('admin.orders');

Route::post('orders/price-preview', [OrderController::class, 'pricePreview'])
    ->name('admin.orders.price-preview');


// Varian Produk
Route::resource('product-variants', ProductVariantController::class)
    ->names('admin.product-variants');

// PRICING MODULE
Route::prefix('pricing')->group(function () {

    Route::get('/', [PricingController::class, 'index'])
        ->name('admin.pricing.index');

    Route::get('/member/{type}', [PricingController::class, 'editMember'])
        ->name('admin.pricing.member');
    Route::post('/member/{type}', [PricingController::class, 'updateMember']);

    Route::get('/instansi/{id}', [PricingController::class, 'editInstansi'])
        ->name('admin.pricing.instansi');
    Route::post('/instansi/{id}', [PricingController::class, 'updateInstansi']);

    Route::get('/customer/{id}', [PricingController::class, 'editCustomer'])
        ->name('admin.pricing.customer');
    Route::post('/customer/{id}', [PricingController::class, 'updateCustomer']);

    Route::resource('product-variants', ProductVariantController::class)
        ->names('admin.product-variants');
});

// Material Cetak
Route::resource('printing-materials', PrintingMaterialController::class)
    ->names('admin.printing-materials');

// Finishing
Route::resource('printing-finishings', PrintingFinishingController::class)
    ->names('admin.printing-finishings');

// INSTANSI CRUD
Route::resource('instansi', InstansiController::class)
    ->names('admin.instansi');

// PAID
Route::post(
    'orders/{order}/mark-paid',
    [OrderController::class, 'markAsPaid']
)->name('admin.orders.markPaid');

// PDF INVOICE
Route::get('orders/{order}/invoice-pdf', [OrderController::class, 'invoicePdf'])
    ->name('orders.invoice.pdf');
