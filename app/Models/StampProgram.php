<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StampProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'target_stamps',
        'min_purchase',
        'reward_description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'target_stamps' => 'integer',
            'min_purchase' => 'float',
            'is_active' => 'boolean',
        ];
    }

    public function customerStamps()
    {
        return $this->hasMany(CustomerStamp::class);
    }
}
