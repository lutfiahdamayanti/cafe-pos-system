<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashbackRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'value',
        'reward_as',
        'min_spending',
        'max_cashback',
        'tier_eligibility',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'float',
            'min_spending' => 'float',
            'max_cashback' => 'float',
            'start_date' => 'date',
            'end_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function logs()
    {
        return $this->hasMany(CashbackLog::class);
    }
}
