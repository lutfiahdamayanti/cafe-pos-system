<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();

        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'icon' => 'nullable'
        ]);

        Category::create([
            'name' => $request->name,
            'icon' => $request->icon
        ]);

        return redirect()->route('admin.category.index')
            ->with('success','Kategori berhasil ditambahkan');
    }

    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required',
            'icon' => 'nullable'
        ]);

        $category->update([
            'name' => $request->name,
            'icon' => $request->icon
        ]);

        return redirect()
            ->route('admin.category.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()
            ->route('admin.category.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}