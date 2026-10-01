<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CafeTable;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Ingredient;
use App\Models\MembershipTierRule;
use App\Models\Menu;
use App\Models\Outlet;
use Illuminate\Http\Request;

class AdvancedOperationController extends Controller
{
    /**
     * =========================================================================
     * 1. DIGITAL RECIPE MANAGEMENT
     * URL: /admin/operasional/resep
     * =========================================================================
     */
    public function digitalRecipes(Request $request)
    {
        $query = Menu::with(['category', 'recipeIngredients']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('taste_notes', 'like', "%{$search}%");
            });
        }

        $menus = $query->orderBy('name')->get();

        // Calculate recipe stats
        $totalMenus = Menu::count();
        $completeRecipesCount = 0;
        $totalRecipeCost = 0;
        $costedMenusCount = 0;

        foreach ($menus as $m) {
            $calculatedCost = $m->recipeIngredients->sum('cost');
            $m->calculated_cost = $calculatedCost > 0 ? $calculatedCost : ($m->food_cost > 0 ? $m->food_cost : ($m->price * 0.32));
            $m->has_sop = !empty($m->recipe_steps) && $m->recipeIngredients->count() > 0;
            if ($m->has_sop) {
                $completeRecipesCount++;
            }
            if ($m->calculated_cost > 0) {
                $totalRecipeCost += $m->calculated_cost;
                $costedMenusCount++;
            }
            $m->margin_pct = $m->price > 0 ? round((($m->price - $m->calculated_cost) / $m->price) * 100, 1) : 0;
        }

        $avgRecipeCost = $costedMenusCount > 0 ? round($totalRecipeCost / $costedMenusCount) : 0;
        $categories = Category::orderBy('name')->get();

        return view('admin.operasional.digital_recipes', compact(
            'menus',
            'categories',
            'totalMenus',
            'completeRecipesCount',
            'avgRecipeCost'
        ));
    }

    public function showRecipe(Menu $menu)
    {
        $menu->load(['category', 'recipeIngredients']);
        $totalCost = $menu->recipeIngredients->sum('cost');
        $effectiveCost = $totalCost > 0 ? $totalCost : ($menu->food_cost > 0 ? $menu->food_cost : ($menu->price * 0.32));
        $marginPct = $menu->price > 0 ? round((($menu->price - $effectiveCost) / $menu->price) * 100, 1) : 0;

        return view('admin.operasional.recipe_detail', compact('menu', 'effectiveCost', 'marginPct'));
    }

    public function updateRecipe(Request $request, Menu $menu)
    {
        $request->validate([
            'serving_temp' => 'nullable|string|max:100',
            'taste_notes' => 'nullable|string|max:255',
            'recipe_steps' => 'nullable|string',
            'food_cost' => 'nullable|numeric|min:0',
        ]);

        $menu->update([
            'serving_temp' => $request->serving_temp,
            'taste_notes' => $request->taste_notes,
            'recipe_steps' => $request->recipe_steps,
            'food_cost' => $request->food_cost ?? $menu->food_cost,
        ]);

        // Update Ingredients
        if ($request->has('ingredients')) {
            $menu->recipeIngredients()->delete();
            foreach ($request->ingredients as $ing) {
                if (!empty($ing['name']) && !empty($ing['quantity'])) {
                    Ingredient::create([
                        'menu_id' => $menu->id,
                        'name' => $ing['name'],
                        'quantity' => $ing['quantity'],
                        'unit' => $ing['unit'] ?? 'gram',
                        'cost' => $ing['cost'] ?? 0,
                    ]);
                }
            }
        }

        return back()->with('success', "Resep digital & SOP pembuatan '{$menu->name}' berhasil diperbarui!");
    }

    /**
     * =========================================================================
     * 2. QR TABLE ORDERING
     * URL: /admin/operasional/qr-meja
     * =========================================================================
     */
    public function qrTableOrdering(Request $request)
    {
        $query = CafeTable::with('outlet');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('zone')) {
            $query->where('zone', $request->zone);
        }

        if ($request->filled('search')) {
            $query->where('table_number', 'like', "%{$request->search}%");
        }

        $tables = $query->orderBy('table_number')->get();

        $totalTables = CafeTable::count();
        $availableCount = CafeTable::where('status', 'available')->count();
        $occupiedCount = CafeTable::where('status', 'occupied')->count();
        $reservedCount = CafeTable::where('status', 'reserved')->count();
        $totalSeatingCapacity = CafeTable::sum('capacity');

        $allOutlets = Outlet::where('status', 'active')->orderBy('name')->get();
        $zones = CafeTable::distinct('zone')->pluck('zone');

        return view('admin.operasional.qr_meja', compact(
            'tables',
            'totalTables',
            'availableCount',
            'occupiedCount',
            'reservedCount',
            'totalSeatingCapacity',
            'allOutlets',
            'zones'
        ));
    }

    public function storeTable(Request $request)
    {
        $request->validate([
            'table_number' => 'required|string|max:50|unique:cafe_tables,table_number',
            'capacity' => 'required|integer|min:1',
            'zone' => 'required|string|max:100',
            'outlet_id' => 'nullable|exists:outlets,id',
            'status' => 'required|in:available,occupied,reserved',
        ]);

        CafeTable::create($request->all());

        return back()->with('success', "Meja '{$request->table_number}' berhasil ditambahkan ke sistem QR order!");
    }

    public function updateTable(Request $request, CafeTable $table)
    {
        $request->validate([
            'table_number' => "required|string|max:50|unique:cafe_tables,table_number,{$table->id}",
            'capacity' => 'required|integer|min:1',
            'zone' => 'required|string|max:100',
            'outlet_id' => 'nullable|exists:outlets,id',
            'status' => 'required|in:available,occupied,reserved',
            'current_customer' => 'nullable|string|max:100',
        ]);

        $table->update($request->all());

        return back()->with('success', "Data meja '{$table->table_number}' berhasil diperbarui!");
    }

    public function updateTableStatus(Request $request, CafeTable $table)
    {
        $request->validate([
            'status' => 'required|in:available,occupied,reserved',
        ]);

        $updateData = ['status' => $request->status];
        if ($request->status === 'available') {
            $updateData['current_customer'] = null;
        }

        $table->update($updateData);

        return back()->with('success', "Status meja '{$table->table_number}' diubah menjadi '{$request->status}'!");
    }

    public function destroyTable(CafeTable $table)
    {
        $table->delete();
        return back()->with('success', "Meja berhasil dihapus!");
    }

    /**
     * =========================================================================
     * 3. LEVEL MEMBERSHIP LANJUTAN
     * URL: /admin/operasional/membership-lanjutan
     * =========================================================================
     */
    public function membershipLanjutan(Request $request)
    {
        $tierRules = MembershipTierRule::orderBy('min_spending')->get();

        $tierCounts = [
            'Bronze' => Customer::where('tier', 'Bronze')->count(),
            'Silver' => Customer::where('tier', 'Silver')->count(),
            'Gold' => Customer::where('tier', 'Gold')->count(),
            'Platinum' => Customer::where('tier', 'Platinum')->count(),
        ];

        $totalMembers = Customer::count();

        // VIP Customers per tier preview
        $customers = Customer::orderByDesc('total_spending')->limit(15)->get();

        return view('admin.operasional.membership_lanjutan', compact(
            'tierRules',
            'tierCounts',
            'totalMembers',
            'customers'
        ));
    }

    public function updateTierRule(Request $request, MembershipTierRule $rule)
    {
        $request->validate([
            'min_spending' => 'required|numeric|min:0',
            'min_orders' => 'required|integer|min:0',
            'point_multiplier' => 'required|numeric|min:1',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'validity_months' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $rule->update([
            'min_spending' => $request->min_spending,
            'min_orders' => $request->min_orders,
            'point_multiplier' => $request->point_multiplier,
            'discount_percent' => $request->discount_percent,
            'free_birthday_drink' => $request->has('free_birthday_drink'),
            'priority_table' => $request->has('priority_table'),
            'free_upsize' => $request->has('free_upsize'),
            'validity_months' => $request->validity_months,
            'description' => $request->description,
        ]);

        return back()->with('success', "Konfigurasi aturan Tier '{$rule->tier_name}' berhasil disimpan!");
    }

    public function recalculateAdvancedTiers(Request $request)
    {
        $rules = MembershipTierRule::orderByDesc('min_spending')->get();
        $customers = Customer::all();
        $upgradedCount = 0;

        foreach ($customers as $c) {
            $newTier = 'Bronze';
            foreach ($rules as $r) {
                if ($c->total_spending >= $r->min_spending && ($c->visit_count ?? 1) >= $r->min_orders) {
                    $newTier = $r->tier_name;
                    break;
                }
            }

            if ($c->tier !== $newTier) {
                $c->update(['tier' => $newTier]);
                $upgradedCount++;
            }
        }

        return back()->with('success', "Rekalkulasi selesai! {$upgradedCount} member berhasil disesuaikan dengan kriteria tier terbaru.");
    }
}
