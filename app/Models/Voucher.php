<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'discount_value',
        'max_discount',
        'min_spending',
        'start_date',
        'end_date',
        'usage_limit',
        'used_count',
        'is_active',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_active' => 'boolean',
            'discount_value' => 'float',
            'max_discount' => 'float',
            'min_spending' => 'float',
            'usage_limit' => 'integer',
            'used_count' => 'integer',
        ];
    }

    public function usages()
    {
        return $this->hasMany(VoucherUsage::class);
    }

    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $today = Carbon::today();
        if ($today->lt($this->start_date) || $today->gt($this->end_date)) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function getStatusBadgeAttribute(): array
    {
        $today = Carbon::today();

        if (!$this->is_active) {
            return ['label' => 'Nonaktif', 'class' => 'bg-secondary text-white'];
        }

        if ($today->lt($this->start_date)) {
            return ['label' => 'Akan Datang', 'class' => 'bg-info text-dark'];
        }

        if ($today->gt($this->end_date)) {
            return ['label' => 'Kedaluwarsa', 'class' => 'bg-danger text-white'];
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return ['label' => 'Habis Terpakai', 'class' => 'bg-warning text-dark'];
        }

        return ['label' => 'Aktif', 'class' => 'bg-success text-white'];
    }
}
