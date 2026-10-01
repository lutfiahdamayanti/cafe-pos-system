<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashbackLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'cashback_rule_id',
        'customer_id',
        'order_id',
        'amount',
        'points_rewarded',
        'notes',
    ];

    public function rule()
    {
        return $this->belongsTo(CashbackRule::class, 'cashback_rule_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
