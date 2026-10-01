<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipTierRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'tier_name',
        'min_spending',
        'min_orders',
        'point_multiplier',
        'discount_percent',
        'free_birthday_drink',
        'priority_table',
        'free_upsize',
        'validity_months',
        'description',
    ];

    protected $casts = [
        'min_spending' => 'decimal:2',
        'point_multiplier' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'free_birthday_drink' => 'boolean',
        'priority_table' => 'boolean',
        'free_upsize' => 'boolean',
    ];
}
