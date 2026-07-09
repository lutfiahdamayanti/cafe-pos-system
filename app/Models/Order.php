<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [

        'order_number',
        'customer_name',
        'phone',
        'table_number',
        'visit_type',
        'payment',
        'note',
        'subtotal',
        'tax',
        'service',
        'total',
        'status',

        'cooking_started_at',
        'ready_at',

    ];

    protected $casts = [

        'cooking_started_at' => 'datetime',

        'ready_at' => 'datetime',

    ];

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }
}