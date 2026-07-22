<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Favorite;
use App\Models\MenuOption;
use App\Models\MenuOptionValue; 

class MenuController extends Controller
{
    public function index(Request $request)
    {
        // Simpan nomor meja dari QR
        if ($request->has('table')) {
            session(['table_number' => $request->table]);
        }

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
        $favorites = Favorite::pluck('menu_id')->toArray();
        return view('customer.menu', compact(
            'menus',
            'categories',
            'favorites'
        ));
    }

    public function detail($id)
    {
        $menu = Menu::with([
            'category',
            'options.values'
        ])->findOrFail($id);

        $recommended = Menu::where('category_id', $menu->category_id)
            ->where('id', '!=', $menu->id)
            ->take(4)
            ->get();

        return view('customer.menu-detail', compact(
            'menu',
            'recommended'
        ));
    }

    public function search(Request $request)
    {
        $menus = Menu::with('category')
            ->where('name', 'LIKE', '%' . $request->keyword . '%')
            ->get();

        return response()->json($menus);
    }

    public function favorite($id)
    {
        $favorite = Favorite::where('menu_id', $id)->first();

        if ($favorite) {
            $favorite->delete();

            return response()->json([
                'status' => 'removed'
            ]);
        }

        Favorite::create([
            'menu_id' => $id,
        ]);

        return response()->json([
            'status' => 'added'
        ]);
    }
}