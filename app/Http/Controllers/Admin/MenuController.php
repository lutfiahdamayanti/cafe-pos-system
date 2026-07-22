<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;
use App\Models\AuditLog;
use App\Models\MenuOption;
use App\Models\MenuOptionValue;

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
            'large_price' => 'required|numeric',
            'rating' => 'required|numeric|min:1|max:5',
            'stock' => 'required|integer',
            'preparation_time' => 'required|integer',
        ]);

        $image = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $image);
        }

        $menu = Menu::create([
            'category_id'       => $request->category_id,
            'name'              => $request->name,
            'description'       => $request->description,
            'ingredients'       => $request->ingredients,
            'price'             => $request->price,
            'large_price'       => $request->large_price,
            'stock'             => $request->stock,
            'rating'            => $request->rating,
            'preparation_time'  => $request->preparation_time,
            'calories'          => $request->calories,
            'allergen'          => $request->allergen,
            'promo'             => $request->has('promo'),
            'best_seller'       => $request->has('best_seller'),
            'is_new'            => $request->has('new'),
            'is_available'      => $request->has('is_active'),
            'image'             => $image,
        ]);

        if($request->has('options')){
            foreach($request->options as $option){
                $menuOption = $menu->options()->create([
                    'name'=>$option['name']
                ]);
                if(isset($option['values'])){
                    foreach($option['values'] as $value){
                        $menuOption->values()->create([
                            'value'=>$value['value'],
                            'extra_price'=>$value['price'] ?? 0
                        ]);
                    }
                }
            }
        }

        // Audit Log
        AuditLog::create([
            'user' => 'Admin',
            'activity' => 'Menambahkan menu: ' . $menu->name,
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
        $menu = Menu::with('options.values')->findOrFail($id);
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
            'large_price' => 'required|numeric',
            'rating' => 'required|numeric|min:1|max:5',
            'stock' => 'required|integer',
            'preparation_time' => 'required|integer',
        ]);

        $menu = Menu::findOrFail($id);
        $image = $menu->image;
        if ($request->hasFile('image')) {
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
            'ingredients' => $request->ingredients,
            'price' => $request->price,
            'large_price' => $request->large_price,
            'stock' => $request->stock,
            'preparation_time' => $request->preparation_time,
            'rating' => $request->rating,

            'calories' => $request->calories,
            'allergen' => $request->allergen,

            'promo' => $request->has('promo'),
            'best_seller' => $request->has('best_seller'),
        ]);

        $menu->options()->delete();
        if($request->has('options')){
            foreach($request->options as $option){
                if(empty($option['name'])) continue;
                $menuOption = $menu->options()->create([
                    'name'=>$option['name']
                ]);

                if(isset($option['values'])){
                    foreach($option['values'] as $value){
                        if(empty($value['value'])) continue;
                        $menuOption->values()->create([
                            'value'=>$value['value'],
                            'extra_price'=>$value['price'] ?? 0
                        ]);
                    }
                }
            }
        }

        // Audit Log
        AuditLog::create([
            'user' => 'Admin',  
            'activity' => 'Mengubah menu: ' . $menu->name,
        ]);
        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $menu = Menu::findOrFail($id);

        // Audit Log
        AuditLog::create([
            'user' => 'Admin',
            'activity' => 'Menghapus menu: ' . $menu->name,
        ]);
        if ($menu->image && file_exists(public_path('images/' . $menu->image))) {
            unlink(public_path('images/' . $menu->image));
        }
        $menu->delete();
        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu berhasil dihapus.');
    }
}