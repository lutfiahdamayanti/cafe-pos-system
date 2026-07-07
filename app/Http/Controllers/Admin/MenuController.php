<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('category')
                    ->latest()
                    ->get();

        return view('admin.menu.index', compact('menus'));
    }

    public function create()
    {
    $categories = Category::all();

    return view('admin.menu.create', compact('categories'));
    }

    public function store(Request $request)
    {
    $request->validate([
        'category_id' => 'required',
        'name' => 'required',
        'description' => 'required',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'preparation_time' => 'required|integer',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $image = null;

    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $image = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('images'), $image);
    }
    Menu::create([
        'category_id' => $request->category_id,
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        'stock' => $request->stock,
        'preparation_time' => $request->preparation_time,
        'promo' => $request->has('promo'),
        'best_seller' => $request->has('best_seller'),
        'image' => $image,
    ]);

    return redirect()->route('admin.menu.index')
        ->with('success', 'Menu berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        
    $menu = Menu::findOrFail($id);
    $categories = Category::all();

    return view('admin.menu.edit', compact('menu', 'categories'));
}

public function update(Request $request, string $id)
{
    $request->validate([
        'category_id' => 'required',
        'name' => 'required',
        'description' => 'required',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'preparation_time' => 'required|integer',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $menu = Menu::findOrFail($id);

    $image = $menu->image;

    if ($request->hasFile('image')) {

        // Hapus gambar lama
        if ($menu->image && file_exists(public_path('images/' . $menu->image))) {
            unlink(public_path('images/' . $menu->image));
        }

        $file = $request->file('image');
        $image = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images'), $image);
    }

    $menu->update([
        'category_id' => $request->category_id,
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        'stock' => $request->stock,
        'preparation_time' => $request->preparation_time,
        'promo' => $request->has('promo'),
        'best_seller' => $request->has('best_seller'),
        'image' => $image,
    ]);

    return redirect()->route('admin.menu.index')
        ->with('success', 'Menu berhasil diperbarui.');
}
    }

    // public function destroy(string $id)
    // {
    //     //
    // }
