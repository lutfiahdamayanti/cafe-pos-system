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

        'cancel_reason',
        'refund_reason',
        'refund_amount',


    ];

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }
}