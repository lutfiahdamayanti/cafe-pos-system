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
use App\Http\Controllers\Admin\PromoLoyaltyController;

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
Route::get('/tracking/status/{id}', [OrderController::class,'status'])
    ->name('tracking.status');
Route::delete('/cart/{id}', [CartController::class, 'destroy'])
    ->name('cart.destroy');

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
            Route::get('/audit/backup', [AuditLogController::class, 'backup'])
                ->name('audit.backup');
            Route::post('/audit-logs/restore', [AuditLogController::class, 'restore'])
                ->name('audit.restore');
            Route::get('/qr-ordering', [QrController::class, 'index'])
                ->name('qr.index');
            Route::get('/qr-ordering/print', [QrController::class, 'print'])
                ->name('qr.print');
        });


        /* =================================== OWNER & MANAGER (CRM & LAPORAN) =========================================*/
        Route::middleware('role:owner,manager')->group(function () {
            Route::get('/', [DashboardController::class, 'index'])
                ->name('dashboard');
            Route::get('/reports', [ReportController::class, 'index'])
                ->name('reports.index');

            // ========================= CRM PELANGGAN SUB-MENUS =========================
            Route::get('/customers/database-pelanggan', [CustomerController::class, 'databasePelanggan'])
                ->name('customers.database');
            Route::get('/customers/riwayat-pembelian', [CustomerController::class, 'riwayatPembelian'])
                ->name('customers.riwayat-pembelian');
            Route::get('/customers/membership-tier', [CustomerController::class, 'membershipTier'])
                ->name('customers.membership-tier');
            Route::post('/customers/{customer}/update-tier', [CustomerController::class, 'updateMemberTier'])
                ->name('customers.update-tier');
            Route::get('/customers/poin-pelanggan', [CustomerController::class, 'poinPelanggan'])
                ->name('customers.poin-pelanggan');
            Route::get('/customers/menu-favorit', [CustomerController::class, 'menuFavorit'])
                ->name('customers.menu-favorit');
            Route::get('/customers/birthday-reminder', [CustomerController::class, 'birthdayReminder'])
                ->name('customers.birthday-reminder');
            Route::get('/customers/birthdays', [CustomerController::class, 'birthdayReminder'])
                ->name('customers.birthdays');

            Route::get('/customers/export/csv', [CustomerController::class, 'exportCsv'])
                ->name('customers.export.csv');
            Route::post('/customers/recalculate-tiers', [CustomerController::class, 'recalculateTiers'])
                ->name('customers.recalculate-tiers');
            Route::post('/customers/{customer}/adjust-points', [CustomerController::class, 'adjustPoints'])
                ->name('customers.adjust-points');
            Route::resource('customers', CustomerController::class);

            // ========================= PROMO & LOYALTY SUB-MENUS =========================
            Route::prefix('promo')->name('promo.')->group(function () {
                // 1. Voucher & Kupon Promo (Fitur 1 & 2)
                Route::get('/vouchers', [PromoLoyaltyController::class, 'vouchers'])->name('vouchers');
                Route::post('/vouchers', [PromoLoyaltyController::class, 'storeVoucher'])->name('vouchers.store');
                Route::put('/vouchers/{voucher}', [PromoLoyaltyController::class, 'updateVoucher'])->name('vouchers.update');
                Route::patch('/vouchers/{voucher}/toggle', [PromoLoyaltyController::class, 'toggleVoucher'])->name('vouchers.toggle');
                Route::delete('/vouchers/{voucher}', [PromoLoyaltyController::class, 'destroyVoucher'])->name('vouchers.destroy');

                // 2. Point Reward (Fitur 3)
                Route::get('/point-rewards', [PromoLoyaltyController::class, 'pointRewards'])->name('point-rewards');
                Route::post('/point-rewards', [PromoLoyaltyController::class, 'storePointReward'])->name('point-rewards.store');
                Route::put('/point-rewards/{pointReward}', [PromoLoyaltyController::class, 'updatePointReward'])->name('point-rewards.update');
                Route::delete('/point-rewards/{pointReward}', [PromoLoyaltyController::class, 'destroyPointReward'])->name('point-rewards.destroy');
                Route::post('/point-rewards/claim', [PromoLoyaltyController::class, 'claimReward'])->name('point-rewards.claim');
                Route::patch('/point-rewards/claims/{claim}', [PromoLoyaltyController::class, 'updateClaimStatus'])->name('point-rewards.claim-status');

                // 3. Stamp Card Digital (Fitur 4)
                Route::get('/stamp-cards', [PromoLoyaltyController::class, 'stampCards'])->name('stamp-cards');
                Route::put('/stamp-cards/program/{program}', [PromoLoyaltyController::class, 'updateStampProgram'])->name('stamp-cards.program.update');
                Route::post('/stamp-cards/add', [PromoLoyaltyController::class, 'addCustomerStamp'])->name('stamp-cards.add');
                Route::post('/stamp-cards/{customerStamp}/redeem', [PromoLoyaltyController::class, 'redeemCustomerStamp'])->name('stamp-cards.redeem');

                // 4. Program Cashback (Fitur 5)
                Route::get('/cashback', [PromoLoyaltyController::class, 'cashback'])->name('cashback');
                Route::post('/cashback', [PromoLoyaltyController::class, 'storeCashbackRule'])->name('cashback.store');
                Route::patch('/cashback/{rule}/toggle', [PromoLoyaltyController::class, 'toggleCashbackRule'])->name('cashback.toggle');
                Route::delete('/cashback/{rule}', [PromoLoyaltyController::class, 'destroyCashbackRule'])->name('cashback.destroy');
                Route::post('/cashback/grant', [PromoLoyaltyController::class, 'grantCashback'])->name('cashback.grant');

                // 5. Referral Program (Fitur 6)
                Route::get('/referrals', [PromoLoyaltyController::class, 'referrals'])->name('referrals');
                Route::post('/referrals', [PromoLoyaltyController::class, 'storeReferral'])->name('referrals.store');
            });
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