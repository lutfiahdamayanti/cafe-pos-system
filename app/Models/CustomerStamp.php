<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerStamp extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'stamp_program_id',
        'current_stamps',
        'total_completed_cards',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function program()
    {
        return $this->belongsTo(StampProgram::class, 'stamp_program_id');
    }
}
