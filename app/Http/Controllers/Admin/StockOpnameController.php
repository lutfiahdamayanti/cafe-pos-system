<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\StockOpname;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class StockOpnameController extends Controller
{
    public function index()
    {
        $inventoryItems = InventoryItem::orderBy('name')->get();
        $opnames = StockOpname::with('inventoryItem')
            ->latest()
            ->paginate(10);
        return view('admin.inventory.opname', compact(
            'inventoryItems',
            'opnames'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'actual_stock' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $opname = DB::transaction(function () use ($validated) {
            $item = InventoryItem::whereKey(
                $validated['inventory_item_id']
            )->lockForUpdate()->firstOrFail();
            $systemStock = (float) $item->stock;
            $actualStock = (float) $validated['actual_stock'];
            $difference = $actualStock - $systemStock;
            $opname = StockOpname::create([
                'inventory_item_id' => $item->id,
                'item_name' => $item->name,
                'unit' => $item->unit,
                'system_stock' => $systemStock,
                'actual_stock' => $actualStock,
                'difference' => $difference,
                'notes' => $validated['notes'] ?? null,
            ]);
            $item->update([
                'stock' => $actualStock,
            ]);

            return $opname;
        });

        return redirect()
            ->route('admin.inventory.opname')
            ->with(
                'success',
                'Stok opname '.$opname->item_name.' berhasil disimpan.'
            );
    }

    public function destroy(StockOpname $opname)
    {
        $opname->delete();
        return redirect()
            ->route('admin.inventory.opname')
            ->with('success', 'Riwayat stok opname berhasil dihapus.');
    }
}
