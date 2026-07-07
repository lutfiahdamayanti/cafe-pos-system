<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\MenuController;
use App\Http\Controllers\Customer\FacilitiesController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\CategoryController;


Route::get('/', function () {
    return view('customer.home');
});
Route::view('/menu', 'customer.menu')->name('menu');
Route::view('/about', 'customer.about')->name('about');
Route::view('/contact', 'customer.contact')->name('contact');
Route::view('/cart', 'customer.cart')->name('cart');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/store', [CartController::class, 'store'])->name('cart.store');
Route::get('/checkout', [CheckoutController::class,'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class,'store'])->name('checkout.store');

Route::get('/', [HomeController::class,'index'])->name('home');
Route::get('/menu', [MenuController::class,'index'])->name('menu');
Route::get('/menu/{id}', [MenuController::class,'detail'])->name('menu.detail');
Route::resource('admin/category', CategoryController::class);
Route::get('/facilities',[FacilitiesController::class,'index'])
        ->name('facilities');
        
// ========== LOGIN =============
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');
Route::view('/admin/login', 'admin.login')->name('admin.login');

// ========== ADMIN =============
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard',[DashboardController::class,'index'])
        ->name('dashboard');

});

Route::prefix('admin')->name('admin.')->group(function () {

    Route::resource('menu', AdminMenuController::class);

    Route::resource('category', CategoryController::class);

});