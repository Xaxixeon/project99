<?php

use Illuminate\Support\Facades\Route;

// ========= CONTROLLERS =========
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderWorkflowController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\FinanceReportController;
use App\Http\Controllers\QcController;
use App\Http\Controllers\OrderOperationController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Auth\StaffAuthController;
use App\Http\Controllers\Auth\CustomerEmailVerificationPromptController;
use App\Http\Controllers\Auth\CustomerVerifyEmailController;
use App\Http\Controllers\Auth\CustomerEmailVerificationNotificationController;
use App\Http\Controllers\Auth\CustomerPasswordResetLinkController;
use App\Http\Controllers\Auth\CustomerNewPasswordController;
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\Customer\CustomerOrderController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PreorderController;
use App\Http\Controllers\Admin\StaffController as AdminStaffController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DisplayGroupController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\PrintingMaterialController;
use App\Http\Controllers\Admin\PrintingFinishingController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\InstansiController;
use App\Http\Controllers\Admin\MemberTypeController;
use App\Http\Controllers\Admin\PricingController;
use App\Http\Controllers\Admin\CustomerSpecialPriceController;
use App\Http\Controllers\Admin\AdminSearchController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\QueueMonitorController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Dashboard\WarehouseController;
use App\Http\Controllers\Dashboard\StaffDashboardController;
use App\Http\Controllers\TakingOrderController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Workflow\WorkflowKanbanController;
use App\Http\Controllers\OrderFileController;
use App\Http\Controllers\OrderChatController;
use App\Http\Controllers\Pos\CsPosController;
use App\Http\Controllers\Dashboard\{
    AdminDashboardController,
    CSDashboardController,
    DesignerDashboardController,
    ProductionDashboardController,
    CashierDashboardController,
    WarehouseDashboardController,
    MarketingDashboardController,
    ManagerDashboardController
};

/* ============================================================
 |  PUBLIC PAGE ROUTES
 * ============================================================ */

// Halaman publik utama (landing page)
Route::get('/', [LandingController::class, 'index'])->name('home');

// Katalog tetap
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/{sku}', [ProductController::class, 'show'])->name('product.show');
// Preview standalone UI (legacy path now redirects to staff dashboard)
Route::redirect('/previewHVA', '/staff/dashboard')->name('preview.hva');
Route::view('/previewhwA', 'previewhwA')->name('preview.hwa');

/* ============================================================
 |  ORDER - PUBLIC (NO AUTH)
 * ============================================================ */
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/create', fn() => view('order.create'))->name('order.create');
Route::post('/upload-design', [UploadController::class, 'store'])->name('upload.design');
Route::get('/order/thanks/{id}', [OrderController::class, 'thanks'])->name('orders.thanks');
Route::post('/order/estimate', [OrderController::class, 'estimate'])->name('order.estimate');

/* ============================================================
 |  AUTH ROUTES (STAFF AS DEFAULT GUARD)
 * ============================================================ */
require __DIR__ . '/auth.php';

// Friendly staff login URL
Route::middleware('guest:staff')->group(function () {
    Route::get('/staff/login', [StaffAuthController::class, 'showLoginForm'])->name('staff.login');
    Route::post('/staff/login', [StaffAuthController::class, 'login'])->name('staff.login.post');
});
Route::post('/staff/logout', [StaffAuthController::class, 'logout'])
    ->middleware('auth:staff')
    ->name('staff.logout');

// Login portal (pilih customer atau staff)
Route::get('/auth', fn() => view('auth.portal'))->name('auth.portal');
// Alias /login diarahkan ke portal (guest customer/staff)
Route::middleware('guest')->get('/login', fn() => view('auth.portal'))->name('login');

/* ============================================================
 |  CUSTOMER AUTH & DASHBOARD
 * ============================================================ */
Route::prefix('customer')->name('customer.')->group(function () {

    Route::middleware('guest:customer')->group(function () {
        Route::get('/login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [CustomerAuthController::class, 'login']);
        Route::get('/register', [CustomerAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [CustomerAuthController::class, 'register']);

        // Forgot / Reset Password
        Route::get('/forgot-password', [CustomerPasswordResetLinkController::class, 'create'])->name('password.request');
        Route::post('/forgot-password', [CustomerPasswordResetLinkController::class, 'store'])->name('password.email');
        Route::get('/reset-password/{token}', [CustomerNewPasswordController::class, 'create'])->name('password.reset');
        Route::post('/reset-password', [CustomerNewPasswordController::class, 'store'])->name('password.update');
    });

    // DASHBOARD & ORDER (tanpa wajib verifikasi email)
    Route::middleware(['auth:customer'])->group(function () {
        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

        Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/files', [OrderFileController::class, 'storeForCustomer'])
            ->name('customer.orders.files.store');
        Route::post('/orders/{order}/chat', [OrderChatController::class, 'store'])
            ->name('customer.orders.chat.store');
    });
});

/* ============================================================
 |  STAFF / INTERNAL AREA
 * ============================================================ */
Route::middleware('auth:staff')->group(function () {

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // STAFF DASHBOARD (panel preview) + legacy kanban page
    Route::view('/staff/dashboard', 'previewHVA')->name('staff.dashboard');
    Route::get('/staff/kanban', [StaffDashboardController::class, 'index'])->name('staff.kanban');

    // GLOBAL ADMIN SEARCH (AJAX)
    Route::get('/admin/search', [AdminSearchController::class, 'search'])->name('admin.search');

    // SYSTEM OVERVIEW (SUPERADMIN)
    Route::get('/admin/system', [SystemController::class, 'index'])
        ->name('admin.system')
        ->middleware('role:superadmin');

    // NOTIFICATION POLLING
    Route::get('/admin/notif/poll', function () {
        return \App\Models\ActivityLog::latest()->limit(5)->get(['action', 'created_at']);
    })->name('admin.notif.poll');

    Route::post('/staff/orders/{order}/files', [OrderFileController::class, 'storeForStaff'])
        ->name('staff.orders.files.store');

    Route::post('/staff/order-files/{file}/approve', [OrderFileController::class, 'approve'])
        ->name('staff.order-files.approve');

    Route::post('/staff/orders/{order}/chat', [OrderChatController::class, 'store'])
        ->name('staff.orders.chat.store');

    Route::post('/orders/{order}/qc', [QcController::class, 'store'])
        ->name('orders.qc.store');
    Route::post('/qc/{qc}/toggle', [QcController::class, 'toggle'])
        ->name('orders.qc.toggle');

    Route::post('/orders/{order}/operations', [OrderOperationController::class, 'store'])
        ->name('orders.operations.store');
    Route::post('/operations/{operation}/start', [OrderOperationController::class, 'start'])
        ->name('orders.operations.start');
    Route::post('/operations/{operation}/finish', [OrderOperationController::class, 'finish'])
        ->name('orders.operations.finish');
    Route::post('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])
        ->name('orders.update-status');
    Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])
        ->name('orders.status');
    Route::post('/orders/reorder', [OrderController::class, 'reorder'])
        ->name('orders.reorder');
    Route::patch('/orders/{order}', [OrderController::class, 'update'])
        ->name('orders.update');
    Route::get('/orders/{order}/activity', [OrderController::class, 'activity'])
        ->name('orders.activity');
    Route::get('/orders/{order}/timeline', [OrderController::class, 'timeline'])
        ->name('orders.timeline');
    Route::get('/orders/board', [OrderController::class, 'board'])
        ->name('orders.board');
    Route::get('/api/orders/board', [OrderController::class, 'boardJson'])
        ->name('orders.board.json');
    Route::get('/staff/home', function () {
        return view('staff.home');
    })->name('staff.home');

    Route::middleware('role:manager')->group(function () {
        Route::get('/manager/dashboard', [\App\Http\Controllers\ManagerDashboardController::class, 'index'])
            ->name('manager.dashboard');
    });

    // TAKING ORDER (create order + tasks)
    Route::get('/orders/create', [TakingOrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [TakingOrderController::class, 'store'])->name('orders.store');
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');
    Route::post('/preorder/store', [PreorderController::class, 'store'])->name('preorder.store');
    Route::post('/preorder/delete', [PreorderController::class, 'delete'])->name('preorder.delete');
    Route::post('/tasks/merge', [TaskController::class, 'mergeTasks'])->name('tasks.merge');

    // WAREHOUSE INVENTORY ACCESS (warehouse/admin/superadmin)
    Route::middleware('role:warehouse,admin,superadmin')->prefix('dashboard/warehouse')->name('warehouse.')->group(function () {
        Route::get('/', [WarehouseController::class, 'index'])->name('dashboard');
        Route::post('/inventory/{product}/adjust', [WarehouseController::class, 'adjust'])->name('adjust');
    });

    // QUEUE MONITOR
    Route::get('/admin/queue-monitor', [QueueMonitorController::class, 'index'])
        ->middleware('role:superadmin')
        ->name('admin.queue.monitor');
    Route::get('/admin/queue-monitor/poll', [QueueMonitorController::class, 'poll'])
        ->middleware('role:superadmin')
        ->name('admin.queue.monitor.poll');

    // WORKFLOW KANBAN
    Route::get('/workflow', [WorkflowKanbanController::class, 'index'])
        ->name('workflow.kanban');
    Route::post('/workflow/update-status', [WorkflowKanbanController::class, 'updateStatus'])
        ->name('workflow.update-status');
    Route::post('/workflow/action', [WorkflowKanbanController::class, 'action'])
        ->name('workflow.action');

    /* ------------------------------------------------------------
     | AFTER LOGIN ROLE REDIRECT
     * ------------------------------------------------------------ */
    Route::get('/role-redirect', function () {
        $user = auth('staff')->user();
        if (!$user) {
            return redirect()->route('staff.login');
        }

        $role = $user->role ?? null;

        return match ($role) {
            'superadmin', 'admin' => redirect()->route('dashboard.admin'),
            'customer_service'    => redirect()->route('dashboard.cs'),
            'designer'            => redirect()->route('dashboard.designer'),
            'production'          => redirect()->route('dashboard.production'),
            'finishing'           => redirect()->route('dashboard.finishing'),
            'qc'                  => redirect()->route('dashboard.qc'),
            'cashier'             => redirect()->route('dashboard.cashier'),
            'warehouse'           => redirect()->route('dashboard.warehouse'),
            'marketing'           => redirect()->route('dashboard.marketing'),
            'manager'             => redirect()->route('dashboard.manager'),
            default               => redirect()->route('home'),
        };
    })->name('role.redirect');

    /* ------------------------------------------------------------
     | DASHBOARD ROUTES BY ROLE
     * ------------------------------------------------------------ */
    Route::prefix('dashboard')->group(function () {
        Route::get('/admin', [AdminDashboardController::class, 'index'])
            ->middleware('role:admin')
            ->name('dashboard.admin');

        Route::get('/customer-service', [CSDashboardController::class, 'index'])
            ->middleware('role:customer_service')
            ->name('dashboard.cs');

        Route::get('/designer', [DesignerDashboardController::class, 'index'])
            ->middleware('role:designer')
            ->name('dashboard.designer');

        Route::get('/production', [ProductionDashboardController::class, 'index'])
            ->middleware('role:production')
            ->name('dashboard.production');

        Route::get('/finishing', fn() => view('dashboard.finishing'))
            ->middleware('role:finishing')
            ->name('dashboard.finishing');

        Route::get('/qc', fn() => view('dashboard.qc'))
            ->middleware('role:qc')
            ->name('dashboard.qc');

        Route::get('/cashier', [CashierDashboardController::class, 'index'])
            ->middleware('role:cashier')
            ->name('dashboard.cashier');

        Route::get('/warehouse', [WarehouseDashboardController::class, 'index'])
            ->middleware('role:warehouse')
            ->name('dashboard.warehouse');

        Route::get('/marketing', [MarketingDashboardController::class, 'index'])
            ->middleware('role:marketing')
            ->name('dashboard.marketing');

        Route::get('/manager', [ManagerDashboardController::class, 'index'])
            ->middleware('role:manager')
            ->name('dashboard.manager');
    });

    /* ------------------------------------------------------------
     | ORDER LIST + ORDER DETAIL
     * ------------------------------------------------------------ */
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/order/{order}', [OrderDetailController::class, 'show'])->name('order.detail');

    /* ------------------------------------------------------------
     | ORDER WORKFLOW (ACTION BUTTONS)
     * ------------------------------------------------------------ */
    Route::post('/order/{order}/assign-designer', [OrderWorkflowController::class, 'assignDesigner']);
    Route::post('/order/{order}/start-design', [OrderWorkflowController::class, 'startDesign']);
    Route::post('/order/{order}/finish-design', [OrderWorkflowController::class, 'finishDesign']);
    Route::post('/order/{order}/start-production', [OrderWorkflowController::class, 'startProduction']);
    Route::post('/order/{order}/print', [OrderWorkflowController::class, 'print']);
    Route::post('/order/{order}/finish', [OrderWorkflowController::class, 'finishJob']);
    Route::post('/order/{order}/ready', [OrderWorkflowController::class, 'markReady']);
    Route::post('/order/{order}/pay', [OrderWorkflowController::class, 'pay']);
    Route::post('/order/{order}/pack', [OrderWorkflowController::class, 'pack']);
    Route::post('/order/{order}/ship', [OrderWorkflowController::class, 'ship']);
    Route::post('/order/{order}/complete', [OrderWorkflowController::class, 'complete']);

    // =====================================================================
    // ADMIN PANEL (PROTECTED)
    // =====================================================================
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        // Dashboard
        Route::get('/dashboard', function () {
            return 'Dashboard';
        })->name('dashboard');

        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

        // Preview UI page (temporary/testing)
        Route::get('/staff/ui-preview', fn() => view('admin.staff.ui-preview'))->name('staff.preview');

        Route::resource('materials', PrintingMaterialController::class);
        Route::resource('finishings', PrintingFinishingController::class);

        // STAFF MANAGEMENT
        Route::resource('staff', AdminStaffController::class);
        Route::post('staff/{id}/reset-password', [AdminStaffController::class, 'resetPassword'])
            ->name('staff.reset-password');
        Route::post('staff/{id}/toggle', [AdminStaffController::class, 'toggle'])
            ->name('staff.toggle');

        // CUSTOMER MANAGEMENT
        Route::resource('customers', AdminCustomerController::class);
        Route::post('customers/{id}/toggle', [AdminCustomerController::class, 'toggle'])
            ->name('customers.toggle');
        Route::get('customers/{customer}/special-prices', [CustomerSpecialPriceController::class, 'edit'])
            ->name('customers.special-prices');
        Route::post('customers/{customer}/special-prices', [CustomerSpecialPriceController::class, 'update'])
            ->name('customers.special-prices.update');

        // INSTANSI MANAGEMENT
        Route::resource('instansi', InstansiController::class);

        // PRODUCTS
        Route::resource('products', AdminProductController::class);
        Route::resource('display-groups', DisplayGroupController::class)->except(['show']);
        Route::resource('products.variants', ProductVariantController::class)->shallow();
        Route::post('display-groups/sort', [DisplayGroupController::class, 'sort'])->name('display-groups.sort');
    });

    // MEMBER & PRICING MANAGEMENT (Admin / Marketing / Superadmin)
    Route::middleware('role:admin,marketing,superadmin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('member', MemberTypeController::class);

        Route::get('/pricing', [PricingController::class, 'index'])->name('pricing.index');
        Route::post('/pricing/update', [PricingController::class, 'update'])->name('pricing.update');
        Route::get('/pricing/logs', [PricingController::class, 'logs'])->name('pricing.logs');
        Route::get('/pricing/customer/{customer}', [PricingController::class, 'customer'])
            ->name('pricing.customer');
        Route::post('/pricing/customer/{customer}', [PricingController::class, 'customerUpdate'])
            ->name('pricing.customer.update');
    });

    // INVOICE MANAGEMENT (ADMIN / CASHIER)
    Route::middleware('role:admin,cashier,customer_service')->group(function () {
        Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
        Route::post('/orders/{order}/invoice', [InvoiceController::class, 'createFromOrder'])
            ->name('orders.invoice.create');
        Route::post('/invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])
            ->name('invoices.mark-paid');
        Route::post('/invoices/{invoice}/mark-unpaid', [InvoiceController::class, 'markUnpaid'])
            ->name('invoices.mark-unpaid');
    });

    Route::middleware('role:admin,manager,superadmin')->group(function () {
        Route::get('/reports/finance', [FinanceReportController::class, 'summary'])
            ->name('reports.finance.summary');
    });

    // CS POS
    Route::middleware('role:customer_service,cashier,admin,superadmin')
        ->prefix('cs')
        ->name('cs.')
        ->group(function () {
            Route::get('/pos', [CsPosController::class, 'index'])->name('pos.index');
            Route::post('/pos/order', [CsPosController::class, 'store'])->name('pos.order.store');
        });
});

// ADMIN DASHBOARD ROUTE (legacy, sebelum ada role-redirect)
Route::middleware(['auth:staff', 'role:admin'])
    ->get('/admin/dashboard', [DashboardController::class, 'index'])
    ->name('admin.dashboard');

/* ============================================================
 |  TaskController ROUTES  
 * ============================================================ */

Route::post('/tasks/merge', [TaskController::class, 'mergeTasks'])
    ->name('tasks.merge');
Route::post('/tasks/{task}/status', [TaskController::class, 'updateStatus'])
    ->name('tasks.status');
Route::post('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])
    ->name('orders.update-status');
Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])
    ->name('orders.status');
Route::post('/operations/{operation}/finish', [OrderOperationController::class, 'finish'])
    ->name('orders.operations.finish');
Route::post('/operations/{operation}/start', [OrderOperationController::class, 'start'])
    ->name('orders.operations.start');
Route::post('/orders/{order}/operations', [OrderOperationController::class, 'store'])
    ->name('orders.operations.store');
Route::post('/orders/{order}/qc', [QcController::class, 'store'])
    ->name('orders.qc.store');
Route::post('/staff/orders/{order}/chat', [OrderChatController::class, 'store'])
    ->name('staff.orders.chat.store');
Route::post('/staff/order-files/{file}/approve', [OrderFileController::class,   'approve'])
    ->name('staff.order-files.approve');
Route::post('/staff/orders/{order}/files', [OrderFileController::class, 'storeForStaff'])
    ->name('staff.orders.files.store');
Route::post('/customer/orders/{order}/files', [OrderFileController::class, 'storeForCustomer'])
    ->name('customer.orders.files.store');
