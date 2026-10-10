<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\Supplier;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $inventoryItems = InventoryItem::latest()->get();
        $totalBahan = $inventoryItems->count();
        $stokMenipis = $inventoryItems->filter(function ($item) {
            return $item->stock > 0 && $item->stock <= $item->minimum_stock;
        })->count();
        $stokHabis = $inventoryItems->filter(function ($item) {
            return $item->stock <= 0;
        })->count();
        return view('admin.inventory.index', compact(
            'inventoryItems',
            'totalBahan',
            'stokMenipis',
            'stokHabis'
        ));
    }

    public function restock()
    {
        $inventoryItems = InventoryItem::with('suppliers')
            ->orderBy('name')
            ->get();
        return view(
            'admin.inventory.restock',
            compact('inventoryItems')
        );
    }

    public function storeRestock(Request $request)
    {
        $validated = $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|numeric|gt:0',
        ]);
        $inventoryItem = InventoryItem::findOrFail(
            $validated['inventory_item_id']
        );
        $supplierTerhubung = $inventoryItem->suppliers()
            ->where('suppliers.id', $validated['supplier_id'])
            ->exists();
        if (!$supplierTerhubung) {
            return back()
                ->withErrors([
                    'supplier_id' => 'Supplier tersebut belum terhubung dengan bahan ini.',
                ])
                ->withInput();
        }
        $supplier = Supplier::findOrFail($validated['supplier_id']);
        $inventoryItem->increment(
            'stock',
            $validated['quantity']
        );
        return redirect()
            ->route('admin.inventory.restock')
            ->with(
                'success',
                'Stok ' . $inventoryItem->name .
                ' berhasil ditambahkan dari supplier ' . $supplier->name . '.'
            );
    }

    public function alert()
    {
        $inventoryItems = InventoryItem::whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock', 'asc')
            ->get();
        $totalAlert = $inventoryItems->count();
        $totalHabis = $inventoryItems
            ->where('stock', '<=', 0)
            ->count();
        $totalMenipis = $inventoryItems
            ->where('stock', '>', 0)
            ->count();
        return view(
            'admin.inventory.alert',
            compact(
                'inventoryItems',
                'totalAlert',
                'totalMenipis',
                'totalHabis'
            )
        );
    }

    public function create()
    {
        return view('admin.inventory.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'unit' => 'required|string|max:50',
            'stock' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'cost_per_unit' => 'required|numeric|min:0',
        ]);
        InventoryItem::create([
            'name' => $request->name,
            'category' => $request->category,
            'unit' => $request->unit,
            'stock' => $request->stock,
            'minimum_stock' => $request->minimum_stock,
            'cost_per_unit' => $request->cost_per_unit,
            'is_active' => true,
        ]);
        return redirect()
            ->route('admin.inventory.index')
            ->with('success', 'Bahan baku berhasil ditambahkan.');
    }

    public function edit(InventoryItem $inventoryItem)
    {
        return view('admin.inventory.edit', compact('inventoryItem'));
    }

    public function update(Request $request, InventoryItem $inventoryItem)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'unit' => 'required|string|max:50',
            'stock' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'cost_per_unit' => 'required|numeric|min:0',
        ]);
        $inventoryItem->update([
            'name' => $request->name,
            'category' => $request->category,
            'unit' => $request->unit,
            'stock' => $request->stock,
            'minimum_stock' => $request->minimum_stock,
            'cost_per_unit' => $request->cost_per_unit,
        ]);
        return redirect()
            ->route('admin.inventory.index')
            ->with('success', 'Bahan baku berhasil diperbarui.');
    }

    public function destroy(InventoryItem $inventoryItem)
    {
        $inventoryItem->delete();
        return redirect()
            ->route('admin.inventory.index')
            ->with('success', 'Bahan baku berhasil dihapus.');
    }
}