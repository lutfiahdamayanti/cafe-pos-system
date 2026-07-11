<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\MenuController;
use App\Http\Controllers\Customer\FacilitiesController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\OrderController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\KitchenController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\QrController;

/*
|--------------------------------------------------------------------------
| CUSTOMER
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/menu/{id}', [MenuController::class, 'detail'])->name('menu.detail');

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


/*
|--------------------------------------------------------------------------
| CUSTOMER AUTH
|--------------------------------------------------------------------------
*/

Route::view('/login', 'auth.login')
    ->name('login');

Route::view('/register', 'auth.register')
    ->name('register');


/*
|--------------------------------------------------------------------------
| ADMIN AUTH
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [AuthController::class, 'login'])
    ->name('admin.login');

Route::post('/admin/login', [AuthController::class, 'authenticate'])
    ->name('admin.authenticate');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| ADMIN PANEL
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | OWNER
        |--------------------------------------------------------------------------
        */

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

        });


        /*
        |--------------------------------------------------------------------------
        | OWNER & MANAGER
        |--------------------------------------------------------------------------
        */

        Route::middleware('role:owner,manager')->group(function () {

            Route::get('/', [DashboardController::class, 'index'])
                ->name('dashboard');

            Route::get('/reports', [ReportController::class, 'index'])
                ->name('reports.index');
    
        });


        /*
        |--------------------------------------------------------------------------
        | OWNER, MANAGER & CASHIER
        |--------------------------------------------------------------------------
        */

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

        });


        /*
        |--------------------------------------------------------------------------
        | OWNER, MANAGER, CASHIER & KITCHEN
        |--------------------------------------------------------------------------
        */

        Route::middleware('role:owner,manager,cashier,kitchen')->group(function () {

            Route::patch(
                'orders/{order}/status',
                [AdminOrderController::class, 'updateStatus']
            )->name('orders.status');

        });


        /*
        |--------------------------------------------------------------------------
        | OWNER, MANAGER & KITCHEN
        |--------------------------------------------------------------------------
        */

        Route::middleware('role:owner,manager,kitchen')->group(function () {

            Route::resource('kitchen', KitchenController::class)
                ->only(['index']);

            Route::get(
                'kitchen/check-new',
                [KitchenController::class, 'checkNew']
            )->name('kitchen.check');

        });

    });
    