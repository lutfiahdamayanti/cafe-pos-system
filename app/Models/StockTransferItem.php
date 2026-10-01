<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransferItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_transfer_id',
        'warehouse_stock_id',
        'item_name',
        'quantity',
        'unit',
        'notes',
    ];

    public function transfer()
    {
        return $this->belongsTo(StockTransfer::class, 'stock_transfer_id');
    }

    public function warehouseStock()
    {
        return $this->belongsTo(CentralWarehouseStock::class, 'warehouse_stock_id');
    }
}
