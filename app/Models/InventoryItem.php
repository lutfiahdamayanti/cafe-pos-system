<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Supplier;

class InventoryItem extends Model
{
    protected $fillable = [
        'name',
        'category',
        'unit',
        'stock',
        'minimum_stock',
        'cost_per_unit',
        'is_active',
    ];

    protected $casts = [
        'stock' => 'decimal:2',
        'minimum_stock' => 'decimal:2',
        'cost_per_unit' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function suppliers()
    {
        return $this->belongsToMany(
            Supplier::class,
            'inventory_item_supplier',
            'inventory_item_id',
            'supplier_id'
        )->withPivot('supply_quantity', 'supply_unit')
        ->withTimestamps();
    }
}