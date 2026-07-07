<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        // Banner Promo
        $promotions = Menu::where('promo', true)
            ->latest()
            ->take(3)
            ->get();

        // Best Seller
        $bestSellers = Menu::where('best_seller', true)
            ->take(8)
            ->get();

        // Menu Terbaru
        $newMenus = Menu::latest()
            ->take(8)
            ->get();

        // Semua kategori
        $categories = Category::all();

        return view('customer.home', compact(
            'promotions',
            'bestSellers',
            'newMenus',
            'categories'
        ));
    }
}