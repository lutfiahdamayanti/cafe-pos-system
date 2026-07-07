<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index(Request $request)
    {
    $query = Menu::with('category');

    // Search
    if ($request->search) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // Filter Category
    if ($request->category) {
        $query->whereHas('category', function ($q) use ($request) {
            $q->where('id', $request->category);
        });
    }

    $menus = $query->latest()->get();

    $categories = Category::all();

    return view('customer.menu', compact(
        'menus',
        'categories'
    ));
    }

    public function detail($id)
{
    $menu = Menu::with('category')->findOrFail($id);

    $recommended = Menu::where('category_id', $menu->category_id)
        ->where('id', '!=', $menu->id)
        ->take(4)
        ->get();

    return view('customer.menu-detail', [
        'menu' => $menu,
        'recommended' => $recommended
    ]);
}
}