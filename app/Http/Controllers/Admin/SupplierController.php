<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::with('inventoryItems')
            ->latest()
            ->paginate(10);
        return view(
            'admin.inventory.suppliers.index',
            compact('suppliers')
        );
    }

    public function create()
    {
        $inventoryItems = InventoryItem::orderBy('name')->get();
        return view(
            'admin.inventory.suppliers.create',
            compact('inventoryItems')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255|unique:suppliers,email',
            'address' => 'nullable|string',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);
        $supplyData = $this->prepareSupplyData($request);
        DB::transaction(function () use ($validated, $request, $supplyData) {
            $supplier = Supplier::create([
                'name' => $validated['name'],
                'contact_person' => $validated['contact_person'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'] ?? null,
                'address' => $validated['address'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'is_active' => $request->boolean('is_active'),
            ]);
            $this->saveSupplierSupplies($supplier, $supplyData);
        });
        return redirect()
            ->route('admin.inventory.suppliers.index')
            ->with('success', 'Data supplier berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier)
    {
        $inventoryItems = InventoryItem::orderBy('name')->get();
        $supplier->load('inventoryItems');
        return view(
            'admin.inventory.suppliers.edit',
            compact('supplier', 'inventoryItems')
        );
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('suppliers', 'email')->ignore($supplier->id),
            ],
            'address' => 'nullable|string',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);
        $supplyData = $this->prepareSupplyData($request);
        DB::transaction(function () use (
            $validated,
            $request,
            $supplier,
            $supplyData
        ) {
            $supplier->update([
                'name' => $validated['name'],
                'contact_person' => $validated['contact_person'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'] ?? null,
                'address' => $validated['address'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'is_active' => $request->boolean('is_active'),
            ]);
            $this->saveSupplierSupplies($supplier, $supplyData);
        });
        return redirect()
            ->route('admin.inventory.suppliers.index')
            ->with('success', 'Data supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()
            ->route('admin.inventory.suppliers.index')
            ->with('success', 'Data supplier berhasil dihapus.');
    }

    private function saveSupplierSupplies(
        Supplier $supplier,
        array $supplyData
    ): void {
        DB::table('inventory_item_supplier')
            ->where('supplier_id', $supplier->id)
            ->delete();
        foreach ($supplyData as $inventoryItemId => $data) {
            DB::table('inventory_item_supplier')->insert([
                'inventory_item_id' => $inventoryItemId,
                'supplier_id' => $supplier->id,
                'supply_quantity' => $data['supply_quantity'],
                'supply_unit' => $data['supply_unit'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function prepareSupplyData(Request $request): array
    {
        $allowedUnits = [
            'gram',
            'kg',
            'ml',
            'liter',
            'pcs',
            'pack',
            'dus',
            'karung',
            'botol',
            'sak',
        ];
        $supplies = $request->input('supplies', []);
        $validator = Validator::make($request->all(), [
            'supplies' => 'nullable|array',
            'supplies.*' => 'array',
            'supplies.*.selected' => 'nullable|in:1',
            'supplies.*.quantity' => 'nullable|numeric|min:0',
            'supplies.*.unit' => 'nullable|string|max:50',
        ]);
        $validator->after(function ($validator) use (
            $supplies,
            $allowedUnits
        ) {
            if (!is_array($supplies)) {
                return;
            }
            foreach ($supplies as $itemId => $supply) {
                if (!is_array($supply)) {
                    continue;
                }
                $quantity = $supply['quantity'] ?? '';
                $unit = $supply['unit'] ?? '';
                $hasSupplyInput =
                    trim((string) $quantity) !== '' ||
                    trim((string) $unit) !== '';
                $isSelected =
                    !empty($supply['selected']) || $hasSupplyInput;
                if (!$isSelected) {
                    continue;
                }
                if (
                    !ctype_digit((string) $itemId) ||
                    !InventoryItem::whereKey((int) $itemId)->exists()
                ) {
                    $validator->errors()->add(
                        "supplies.$itemId.selected",
                        'Bahan baku yang dipilih tidak valid.'
                    );

                    continue;
                }
                if (!is_numeric($quantity) || (float) $quantity <= 0) {
                    $validator->errors()->add(
                        "supplies.$itemId.quantity",
                        'Jumlah pasokan harus lebih dari 0.'
                    );
                }
                if (!in_array($unit, $allowedUnits, true)) {
                    $validator->errors()->add(
                        "supplies.$itemId.unit",
                        'Pilih satuan pasokan yang valid.'
                    );
                }
            }
        });
        $validator->validate();
        $syncData = [];
        foreach ($supplies as $itemId => $supply) {
            if (!is_array($supply)) {
                continue;
            }
            $quantity = $supply['quantity'] ?? '';
            $unit = $supply['unit'] ?? '';
            $hasSupplyInput =
                trim((string) $quantity) !== '' ||
                trim((string) $unit) !== '';
            $isSelected =
                !empty($supply['selected']) || $hasSupplyInput;
            if (!$isSelected) {
                continue;
            }
            $syncData[(int) $itemId] = [
                'supply_quantity' => (float) $quantity,
                'supply_unit' => $unit,
            ];
        }
        return $syncData;
    }
}