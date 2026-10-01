<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CafeTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'outlet_id',
        'table_number',
        'capacity',
        'zone',
        'status',
        'current_customer',
        'notes',
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function getQrUrlAttribute(): string
    {
        return url('/?table=' . urlencode($this->table_number));
    }
}
