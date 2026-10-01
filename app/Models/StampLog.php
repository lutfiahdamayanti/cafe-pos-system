<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StampLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'stamp_program_id',
        'stamps',
        'action',
        'description',
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
