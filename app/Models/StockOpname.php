<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\InventoryItem;

class StockOpname extends Model
{
    protected $fillable = [
        'inventory_item_id',
        'item_name',
        'unit',
        'system_stock',
        'actual_stock',
        'difference',
        'notes',
    ];

    protected $casts = [
        'system_stock' => 'decimal:2',
        'actual_stock' => 'decimal:2',
        'difference' => 'decimal:2',
    ];

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }
}