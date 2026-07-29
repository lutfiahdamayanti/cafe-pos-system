<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\MenuController;
use App\Http\Controllers\Customer\FacilitiesController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\FavoriteController;
use App\Http\Controllers\Customer\AboutController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\KitchenController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\QrController;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PosController;

/* ========================================= CUSTOMER ========================================*/
Route::get('/', [MenuController::class, 'index'])
    ->name('home');Route::view('/about', 'customer.about')->name('about');
Route::view('/contact', 'customer.contact')->name('contact');
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/menu/{id}', [MenuController::class, 'detail'])->name('menu.detail');
Route::get('/search-menu', [MenuController::class, 'search'])
    ->name('menu.search');
Route::get('/facilities', [FacilitiesController::class, 'index'])
    ->name('facilities');
Route::get('/cart', [CartController::class, 'index'])
    ->name('cart.index');
Route::post('/cart/store', [CartController::class, 'store'])
    ->name('cart.store');
Route::get('/checkout', [CheckoutController::class, 'index'])
    ->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])
    ->name('checkout.store');
Route::get('/tracking', [OrderController::class, 'tracking'])
    ->name('tracking');
Route::post('/cart/{id}/qty', [CartController::class, 'updateQty'])
    ->name('cart.qty');
Route::post('/favorite/{id}', [MenuController::class,'favorite'])
    ->name('favorite.toggle');
Route::get('/reset-table', function () {
    session()->forget('table_number');
    return redirect('/');
});

/* ===================================== CUSTOMER AUTH ==========================================*/
Route::view('/login', 'auth.login')
    ->name('login');
Route::view('/register', 'auth.register')
    ->name('register');

/* =================================== ADMIN AUTH =============================================*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])
        ->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])
        ->name('authenticate');
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});

/* ======================== SUPER ADMIN =====================*/
Route::prefix('super-admin')
    ->name('superadmin.')
    ->middleware('role:super_admin')
    ->group(function () {

        Route::get('/dashboard', [SuperAdminController::class, 'index'])
            ->name('dashboard');

        Route::resource('users', AdminUserController::class);

});

/* ================================= ADMIN PANEL ====================================================*/
Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

    // Route::middleware('role:super_admin')->group(function () {
    //     Route::resource(
    //         'users',
    //         \App\Http\Controllers\Admin\AdminUserController::class
    //     );
    // });

        /* ======================================== OWNER ========================================*/
        Route::middleware('role:owner')->group(function () {
            Route::resource('menu', AdminMenuController::class);
            Route::resource('category', CategoryController::class);
            Route::get('/audit-logs', [AuditLogController::class, 'index'])
                ->name('audit.index');
            Route::get('/audit/export/csv', [AuditLogController::class, 'exportCsv'])
                ->name('audit.export.csv');
            Route::get('/audit/backup', [AuditLogController::class, 'backup'])
                ->name('audit.backup');
            Route::post('/audit-logs/restore', [AuditLogController::class, 'restore'])
                ->name('audit.restore');
            Route::get('/qr-ordering', [QrController::class, 'index'])
                ->name('qr.index');
            Route::get('/qr-ordering/print', [QrController::class, 'print'])
                ->name('qr.print');
        });


        /* =================================== OWNER & MANAGER =========================================*/
        Route::middleware('role:owner,manager')->group(function () {
            Route::get('/', [DashboardController::class, 'index'])
                ->name('dashboard');
            Route::get('/reports', [ReportController::class, 'index'])
                ->name('reports.index');
            Route::get('/', [DashboardController::class,'index'])
                ->name('dashboard');
            Route::get('/reports',[ReportController::class,'index'])
                ->name('reports.index');
            Route::resource('customers', CustomerController::class)
                ->only(['index']);
        });

        /* ======================================= OWNER, MANAGER & CASHIER ====================================*/
        Route::middleware('role:owner,manager,cashier')->group(function () {
            Route::resource('orders', AdminOrderController::class);
            Route::patch(
                'orders/{order}/refund',
                [AdminOrderController::class, 'refund']
            )->name('orders.refund');
            Route::patch(
                'orders/{order}/cancel',
                [AdminOrderController::class, 'cancel']
            )->name('orders.cancel');
            Route::patch(
                'orders/{order}/void',
                [AdminOrderController::class, 'void']
            )->name('orders.void');
            Route::get('/history', [AdminOrderController::class, 'history'])
                ->name('history');
            Route::get(
                'orders/{order}/receipt',
                [AdminOrderController::class, 'receipt']
            )->name('orders.receipt');
            Route::get('/pos', [PosController::class,'index'])
                ->name('pos.index');
            Route::post('/pos', [PosController::class,'store'])
                ->name('pos.store');
            Route::resource('kitchen', KitchenController::class)
                ->only(['index']);
            Route::get(
                'kitchen/check-new',
                [KitchenController::class, 'checkNew']
            )->name('kitchen.check');
            Route::get(
                'orders/{order}/kitchen-ticket',
                [AdminOrderController::class, 'kitchenTicket']
            )->name('orders.kitchen-ticket');
        });

        /* ============================== OWNER, MANAGER, CASHIER & KITCHEN =========================*/
        Route::middleware('role:owner,manager,cashier,kitchen')->group(function () {
            Route::patch(
                'orders/{order}/status',
                [AdminOrderController::class, 'updateStatus']
            )->name('orders.status');

        });

        /* ============================= OWNER, MANAGER & KITCHEN =====================*/
        // Route::middleware('role:owner,manager,kitchen')->group(function () {
        //     Route::resource('kitchen', KitchenController::class)
        //         ->only(['index']);
        //     Route::get(
        //         'kitchen/check-new',
        //         [KitchenController::class, 'checkNew']
        //     )->name('kitchen.check');
        // });
    });