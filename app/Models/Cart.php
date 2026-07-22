<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'menu_id',
        'qty',
        'options',
        'note',
        'price',
        'total',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}