<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function inventoryItems()
    {
        return $this->belongsToMany(
            InventoryItem::class,
            'inventory_item_supplier',
            'supplier_id',
            'inventory_item_id'
        )->withPivot('supply_quantity', 'supply_unit')
        ->withTimestamps();
    }
}