<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = [
        'menu_id',
        'inventory_item_id',
        'name',
        'quantity',
        'unit',
        'cost',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }
}