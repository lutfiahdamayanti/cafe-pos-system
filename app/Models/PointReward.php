<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'points_required',
        'reward_type',
        'discount_value',
        'stock',
        'image',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'points_required' => 'integer',
            'stock' => 'integer',
            'discount_value' => 'float',
            'is_active' => 'boolean',
        ];
    }

    public function claims()
    {
        return $this->hasMany(PointRewardClaim::class);
    }
}
