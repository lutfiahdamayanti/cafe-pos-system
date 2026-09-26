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
        'discount',
        'tax',
        'service',
        'total',
        'status',
        'action_status',

        'cooking_started_at',
        'ready_at',

        'queue_number',
        'estimated_time',
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