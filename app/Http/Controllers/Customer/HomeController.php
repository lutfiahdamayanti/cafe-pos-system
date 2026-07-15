<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        // Promo
        $promotions = Menu::where('promo', 1)
            ->where('stock', '>', 0)
            ->latest()
            ->take(3)
            ->get();

        // Best Seller
        $bestSellers = Menu::with('category')
            ->where('best_seller', 1)
            ->where('stock', '>', 0)
            ->take(6)
            ->get();

        // Menu Terbaru
        $newMenus = Menu::with('category')
            ->where('stock', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::all();

        return view('customer.home', compact(
            'promotions',
            'bestSellers',
            'newMenus',
            'categories'
        ));
    }
}