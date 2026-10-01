<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointRewardClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'point_reward_id',
        'customer_id',
        'points_spent',
        'claim_code',
        'status',
        'notes',
    ];

    public function reward()
    {
        return $this->belongsTo(PointReward::class, 'point_reward_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
