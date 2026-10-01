<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'referrer_id',
        'referee_id',
        'referrer_points_rewarded',
        'referee_points_rewarded',
        'status',
    ];

    public function referrer()
    {
        return $this->belongsTo(Customer::class, 'referrer_id');
    }

    public function referee()
    {
        return $this->belongsTo(Customer::class, 'referee_id');
    }
}
