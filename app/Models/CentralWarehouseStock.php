<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentralWarehouseStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'sku',
        'category',
        'stock_quantity',
        'unit',
        'min_stock',
        'unit_cost',
        'supplier',
        'notes',
    ];

    public function transferItems()
    {
        return $this->hasMany(StockTransferItem::class, 'warehouse_stock_id');
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->min_stock;
    }
}
