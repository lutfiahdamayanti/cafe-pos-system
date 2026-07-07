<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'menu_id',
        'qty',
        'size',
        'sugar_level',
        'ice_level',
        'note',
        'price',
        'total',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}