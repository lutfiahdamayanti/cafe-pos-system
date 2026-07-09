<?php

use Illuminate\Support\Facades\Route;
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

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/store', [CartController::class, 'store'])->name('cart.store');
Route::get('/checkout', [CheckoutController::class,'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class,'store'])->name('checkout.store');
Route::get('/tracking', [OrderController::class, 'tracking'])
    ->name('tracking');

Route::get('/', [HomeController::class,'index'])->name('home');
Route::get('/menu', [MenuController::class,'index'])->name('menu');
Route::get('/menu/{id}', [MenuController::class,'detail'])->name('menu.detail');
Route::resource('admin/category', CategoryController::class);
Route::get('/facilities',[FacilitiesController::class,'index'])->name('facilities');
        
// ========== LOGIN =============
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');
Route::view('/admin/login', 'admin.login')->name('admin.login');

// ========== ADMIN =============
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('menu', AdminMenuController::class);

    Route::resource('category', CategoryController::class);
    Route::resource('orders', AdminOrderController::class);

    Route::patch(
        'orders/{order}/status',
        [AdminOrderController::class,'updateStatus']
    )->name('orders.status');

    Route::patch(
        'orders/{order}/refund',
        [AdminOrderController::class,'refund']
    )->name('orders.refund');

    Route::patch(
        'orders/{order}/cancel',
        [AdminOrderController::class,'cancel']
    )->name('orders.cancel');

    Route::patch(
        'orders/{order}/void',
        [AdminOrderController::class,'void']
    )->name('orders.void');

    Route::get('/history',[AdminOrderController::class,'history'])
        ->name('history');
    
    // CETAK STRUK PDF
    Route::get(
        'orders/{order}/receipt',
        [AdminOrderController::class,'receipt']
    )->name('orders.receipt');

    Route::resource('kitchen', KitchenController::class)
    ->only(['index']);

    Route::get(
        'kitchen/check-new',
        [KitchenController::class, 'checkNew']
    )->name('kitchen.check');

    Route::get('/reports', [ReportController::class,'index'])
        ->name('reports.index');

});